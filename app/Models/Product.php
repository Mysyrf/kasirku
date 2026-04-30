<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // HANYA SATU DEKLARASI $fillable - di bagian atas
    protected $fillable = [
        'barcode', 
        'name', 
        'category_id', 
        'price',
        'price_retail', 
        'price_wholesale', 
        'wholesale_min_qty',
        'discount_percent', 
        'price_type', 
        'stock', 
        'unit',
        'avg_cost_price', 
        'last_buy_price'
    ];

    protected $casts = [
        'price' => 'integer',
        'price_retail' => 'integer',
        'price_wholesale' => 'integer',
        'stock' => 'integer',
        'avg_cost_price' => 'integer',
        'last_buy_price' => 'integer',
        'wholesale_min_qty' => 'integer',
        'discount_percent' => 'integer'
    ];

    // Relasi
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Method untuk mendapatkan harga berdasarkan quantity
    public function getPriceByQuantity($quantity)
    {
        if ($this->price_type == 'retail_wholesale') {
            if ($quantity >= $this->wholesale_min_qty && $this->price_wholesale > 0) {
                return $this->price_wholesale;
            }
            return $this->price_retail > 0 ? $this->price_retail : $this->price;
        }
        
        // Harga single dengan diskon
        $harga = $this->price;
        if ($this->discount_percent > 0) {
            $harga = $harga - ($harga * $this->discount_percent / 100);
        }
        return $harga;
    }

    // Method untuk menghitung total dengan harga progresif
    public function calculatePrice($quantity)
    {
        $harga = $this->getPriceByQuantity($quantity);
        $subtotal = $harga * $quantity;
        
        // Diskon khusus produk untuk single price
        if ($this->discount_percent > 0 && $this->price_type == 'single') {
            $diskon = $subtotal * $this->discount_percent / 100;
            return [
                'price_per_item' => $harga,
                'subtotal' => $subtotal - $diskon,
                'discount' => $diskon,
                'discount_percent' => $this->discount_percent
            ];
        }
        
        return [
            'price_per_item' => $harga,
            'subtotal' => $subtotal,
            'discount' => 0,
            'discount_percent' => 0
        ];
    }

    // Update harga modal rata-rata setelah pembelian
    public function updateAverageCost($newQuantity, $newBuyPrice)
    {
        $totalValue = ($this->avg_cost_price * $this->stock) + ($newBuyPrice * $newQuantity);
        $totalStock = $this->stock + $newQuantity;
        
        $newAvgCost = $totalStock > 0 ? floor($totalValue / $totalStock) : 0;
        
        $this->update([
            'avg_cost_price' => $newAvgCost,
            'last_buy_price' => $newBuyPrice,
            'stock' => $totalStock
        ]);
    }

    // Hitung keuntungan dari harga jual
    public function getProfitPerItem()
    {
        return $this->price - $this->avg_cost_price;
    }

    public function getProfitMargin()
    {
        if ($this->avg_cost_price == 0) return 0;
        return round(($this->price - $this->avg_cost_price) / $this->avg_cost_price * 100);
    }
}