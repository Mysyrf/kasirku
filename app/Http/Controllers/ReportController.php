<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $startDate = $request->get('start_date', date('Y-m-d'));
        $endDate = $request->get('end_date', date('Y-m-d'));
        
        $transactions = Transaction::with('items.product')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Statistik
        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $retailCount = $transactions->where('type', 'retail')->count();
        $wholesaleCount = $transactions->where('type', 'wholesale')->count();
        $totalDiscount = $transactions->sum('discount');
        
        return view('reports.index', compact(
            'transactions', 'totalRevenue', 'totalTransactions',
            'retailCount', 'wholesaleCount', 'totalDiscount',
            'startDate', 'endDate'
        ));
    }
    
    public function show(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('reports.show', compact('transaction'));
    }
    
    public function print(Transaction $transaction)
    {
        $transaction->load('items.product');
        return view('reports.print', compact('transaction'));
    }
}