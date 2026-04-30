<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'old_retail_price',
        'new_retail_price',
        'old_wholesale_price',
        'new_wholesale_price',
        'old_discount_percent',
        'new_discount_percent',
        'changed_by'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
