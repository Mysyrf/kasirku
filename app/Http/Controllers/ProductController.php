<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:products',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|in:pcs,kg,liter,pack'
        ]);

        $product = Product::create([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price ?? 0,
            'stock' => $request->stock ?? 0,
            'unit' => $request->unit
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return response()->json([
            'id' => $product->id,
            'barcode' => $product->barcode,
            'name' => $product->name,
            'category_id' => $product->category_id,
            'category_name' => $product->category->name ?? '-',
            'price' => $product->price,
            'stock' => $product->stock,
            'unit' => $product->unit
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'barcode' => 'required|unique:products,barcode,' . $product->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|in:pcs,kg,liter,pack'
        ]);

        $product->update([
            'barcode' => $request->barcode,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'unit' => $request->unit
        ]);

        return redirect()->back()->with('success', 'Data produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
    public function priceManagement()
    {
        $products = Product::with('category')->latest()->paginate(20);
        return view('products.price', compact('products'));
    }

public function updatePrice(Request $request, Product $product)
{
    $priceRetail = $request->price_retail;
    $priceWholesale = $request->price_wholesale ?? 0;
    $wholesaleMinQty = $request->wholesale_min_qty ?? 10;
    
    $priceType = ($priceWholesale > 0) ? 'retail_wholesale' : 'single';
    
    $product->update([
        'price' => $priceRetail,
        'price_retail' => $priceRetail,
        'price_wholesale' => $priceWholesale,
        'wholesale_min_qty' => $wholesaleMinQty,
        'price_type' => $priceType
    ]);

    return redirect()->route('products.index')->with('success', 'Harga ' . $product->name . ' berhasil diupdate!');
}
public function bulkUpdatePrice(Request $request)
{
    $products = $request->products;
    
    if (!$products) {
        return redirect()->back()->with('error', 'Tidak ada data yang diupdate');
    }
    
    foreach ($products as $id => $data) {
        $product = Product::find($id);
        if ($product) {
            $priceType = $data['price_type'] ?? 'single';
            $priceRetail = $data['price_retail'] ?? $product->price;
            $priceWholesale = $data['price_wholesale'] ?? 0;
            $wholesaleMinQty = $data['wholesale_min_qty'] ?? 10;
            $discountPercent = $data['discount_percent'] ?? 0;
            
            $updateData = [
                'price_type' => $priceType,
                'discount_percent' => $discountPercent,
                'wholesale_min_qty' => $wholesaleMinQty
            ];
            
            if ($priceType == 'retail_wholesale') {
                $updateData['price_retail'] = $priceRetail;
                $updateData['price_wholesale'] = $priceWholesale;
                $updateData['price'] = $priceRetail;
            } else {
                $updateData['price'] = $priceRetail;
                $updateData['price_retail'] = $priceRetail;
                $updateData['price_wholesale'] = 0;
            }
            
            $product->update($updateData);
        }
    }
    
    return redirect()->back()->with('success', 'Semua harga berhasil diupdate!');
}
}