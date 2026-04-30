<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('cashier.index', compact('products'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi! Sisa: ' . $product->stock
            ]);
        }

        // Hitung harga berdasarkan quantity dan tipe produk
        $priceData = $product->calculatePrice($request->quantity);
        
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $newQty = $cart[$product->id]['quantity'] + $request->quantity;
            if ($product->stock < $newQty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Maksimal ' . $product->stock
                ]);
            }
            
            // Recalculate harga dengan quantity baru
            $newPriceData = $product->calculatePrice($newQty);
            $cart[$product->id]['quantity'] = $newQty;
            $cart[$product->id]['price_per_item'] = $newPriceData['price_per_item'];
            $cart[$product->id]['subtotal'] = $newPriceData['subtotal'];
            $cart[$product->id]['discount'] = $newPriceData['discount'];
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $request->quantity,
                'price_per_item' => $priceData['price_per_item'],
                'subtotal' => $priceData['subtotal'],
                'discount' => $priceData['discount'],
                'discount_percent' => $priceData['discount_percent'],
                'stock' => $product->stock,
                'unit' => $product->unit
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => $product->name . ' ditambahkan ke keranjang'
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = session()->get('cart', []);

        if ($request->quantity <= 0) {
            unset($cart[$request->product_id]);
        } elseif (isset($cart[$request->product_id])) {
            $product = Product::find($request->product_id);
            if ($product->stock >= $request->quantity) {
                $newPriceData = $product->calculatePrice($request->quantity);
                $cart[$request->product_id]['quantity'] = $request->quantity;
                $cart[$request->product_id]['price_per_item'] = $newPriceData['price_per_item'];
                $cart[$request->product_id]['subtotal'] = $newPriceData['subtotal'];
                $cart[$request->product_id]['discount'] = $newPriceData['discount'];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi! Maksimal ' . $product->stock
                ]);
            }
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true
        ]);
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true]);
    }

    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    public function getCart()
    {
        return response()->json([
            'cart_html' => view('cashier.cart')->render()
        ]);
    }

    public function processTransaction(Request $request)
    {
        $request->validate([
            'paid' => 'required|integer|min:0'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja kosong!'
            ]);
        }

        $subtotal = 0;
        $totalQuantity = 0;
        $hasWholesale = false;
        
        foreach ($cart as $item) {
            $subtotal += $item['subtotal'];
            $totalQuantity += $item['quantity'];
            
            // Cek apakah ada barang yang masuk kategori grosir
            $product = Product::find($item['id']);
            if ($product && $product->price_type == 'retail_wholesale' && $product->wholesale_min_qty > 0 && $item['quantity'] >= $product->wholesale_min_qty) {
                $hasWholesale = true;
            }
        }

        $transactionType = $hasWholesale ? 'wholesale' : 'retail';
        $discountPercent = 0;
        $discountAmount = 0;
        $total = $subtotal;

        if ($request->paid < $total) {
            return response()->json([
                'success' => false,
                'message' => 'Uang tidak mencukupi! Kurang Rp ' . number_format($total - $request->paid, 0, ',', '.')
            ]);
        }

        $change = $request->paid - $total;

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'customer_name' => $request->customer_name ?? 'Umum',
                'type' => $transactionType,
                'subtotal' => $subtotal,
                'discount' => $discountAmount,
                'total' => $total,
                'paid' => $request->paid,
                'change' => $change,
                'cashier' => auth()->user()->name ?? 'System'
            ]);

            foreach ($cart as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price_per_item'],
                    'subtotal' => $item['subtotal']
                ]);

                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            session()->forget('cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil!',
                'data' => [
                    'invoice_number' => $transaction->invoice_number,
                    'customer_name' => $transaction->customer_name,
                    'transaction_type' => $transaction->type,
                    'items' => array_values($cart),
                    'subtotal' => $subtotal,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'total' => $total,
                    'paid' => $request->paid,
                    'change' => $change,
                    'cashier' => auth()->user()->name ?? 'System'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

        public function getProductByBarcode($barcode)
    {
        $product = Product::where('barcode', $barcode)->first();
        
        if ($product) {
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan'
        ]);
    }

    private function generateInvoiceNumber()
    {
        $date = date('Ymd');
        $lastTransaction = Transaction::whereDate('created_at', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastTransaction) {
            $sequence = 1;
        } else {
            $lastNumber = explode('/', $lastTransaction->invoice_number);
            $sequence = end($lastNumber) + 1;
        }

        return 'INV/' . $date . '/' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}