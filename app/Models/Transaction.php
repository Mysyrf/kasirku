<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'type',
        'subtotal',
        'discount',
        'total',
        'paid',
        'change',
        'cashier'
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $date = date('Ymd');
        $lastTransaction = self::whereDate('created_at', date('Y-m-d'))
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