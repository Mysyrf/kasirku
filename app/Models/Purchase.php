<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'invoice_number', 'quantity', 
        'buy_price', 'total_price', 'purchase_date', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function generateInvoiceNumber()
    {
        $date = date('Ymd');
        $last = self::whereDate('created_at', date('Y-m-d'))->latest()->first();
        
        if (!$last) {
            $number = 1;
        } else {
            $number = intval(substr($last->invoice_number, -4)) + 1;
        }
        
        return 'PO/' . $date . '/' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}