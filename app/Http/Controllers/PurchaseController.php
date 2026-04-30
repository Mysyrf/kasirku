<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('product')->latest()->paginate(20);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'buy_price' => 'required|integer|min:0',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $product = Product::find($request->product_id);
        $totalPrice = $request->quantity * $request->buy_price;

        // Simpan pembelian
        $purchase = Purchase::create([
            'product_id' => $request->product_id,
            'invoice_number' => $this->generateInvoiceNumber(),
            'quantity' => $request->quantity,
            'buy_price' => $request->buy_price,
            'total_price' => $totalPrice,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes
        ]);

        // Update stok dan harga modal rata-rata
        $this->updateAverageCost($product, $request->quantity, $request->buy_price);

        return redirect()->route('purchases.index')
            ->with('success', 'Stok berhasil ditambahkan');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('product');
        return view('purchases.show', compact('purchase'));
    }

    private function generateInvoiceNumber()
    {
        $date = date('Ymd');
        $last = Purchase::whereDate('created_at', date('Y-m-d'))->latest()->first();
        
        if (!$last) {
            $number = 1;
        } else {
            $lastNumber = explode('/', $last->invoice_number);
            $number = end($lastNumber) + 1;
        }
        
        return 'PO/' . $date . '/' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function updateAverageCost($product, $newQuantity, $newBuyPrice)
    {
        $totalValue = ($product->avg_cost_price * $product->stock) + ($newBuyPrice * $newQuantity);
        $totalStock = $product->stock + $newQuantity;
        
        $newAvgCost = $totalStock > 0 ? floor($totalValue / $totalStock) : 0;
        
        $product->update([
            'avg_cost_price' => $newAvgCost,
            'last_buy_price' => $newBuyPrice,
            'stock' => $totalStock
        ]);
    }
}