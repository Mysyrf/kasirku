<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $todaySales = Transaction::whereDate('created_at', today())->sum('total');
        $monthSales = Transaction::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('total');
        
        $todayExpense = \App\Models\Purchase::whereDate('purchase_date', today())->sum('total_price');
        $monthExpense = \App\Models\Purchase::whereMonth('purchase_date', date('m'))->whereYear('purchase_date', date('Y'))->sum('total_price');
        
        $todayProfit = $todaySales - $todayExpense;
        $monthProfit = $monthSales - $monthExpense;
        
        $recentTransactions = Transaction::with('items.product')
            ->latest()
            ->limit(5)
            ->get();
        
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->limit(10)
            ->get();
        
        $bestSelling = Product::withSum('transactionItems', 'quantity')
            ->orderByRaw('COALESCE(transaction_items_sum_quantity, 0) DESC')
            ->limit(5)
            ->get();
            
        $leastSelling = Product::withSum('transactionItems', 'quantity')
            ->orderByRaw('COALESCE(transaction_items_sum_quantity, 0) ASC')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalProducts', 'totalStock', 'todaySales', 
            'monthSales', 'todayExpense', 'monthExpense',
            'todayProfit', 'monthProfit',
            'recentTransactions', 'lowStockProducts',
            'bestSelling', 'leastSelling'
        ));
    }
}