@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h3>Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p>Role: {{ Auth::user()->role == 'admin' ? 'Administrator' : 'Kasir' }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6><i class="fas fa-cubes"></i> Total Produk</h6>
                <h3>{{ $totalProducts }}</h3>
                <small>produk terdaftar</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6><i class="fas fa-boxes"></i> Total Stok</h6>
                <h3>{{ $totalStock }}</h3>
                <small>unit tersedia</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h6><i class="fas fa-hand-holding-usd"></i> Penjualan Hari Ini</h6>
                <h3>Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                <small>pendapatan</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6><i class="fas fa-wallet"></i> Pemasukan Bulan Ini</h6>
                <h3>Rp {{ number_format($monthSales, 0, ',', '.') }}</h3>
                <small>penjualan</small>
            </div>
        </div>
    </div>

    <!-- Baris Kedua Statistik -->
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6><i class="fas fa-shopping-cart"></i> Pengeluaran Hari Ini</h6>
                <h3>Rp {{ number_format($todayExpense, 0, ',', '.') }}</h3>
                <small>belanja stok</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white" style="opacity: 0.9;">
            <div class="card-body">
                <h6><i class="fas fa-shopping-basket"></i> Pengeluaran Bulan Ini</h6>
                <h3>Rp {{ number_format($monthExpense, 0, ',', '.') }}</h3>
                <small>belanja stok</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card {{ $todayProfit >= 0 ? 'bg-success' : 'bg-secondary' }} text-white">
            <div class="card-body">
                <h6><i class="fas fa-chart-line"></i> Laba Hari Ini</h6>
                <h3>Rp {{ number_format($todayProfit, 0, ',', '.') }}</h3>
                <small>pemasukan - pengeluaran</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card {{ $monthProfit >= 0 ? 'bg-success' : 'bg-secondary' }} text-white" style="opacity: 0.9;">
            <div class="card-body">
                <h6><i class="fas fa-chart-bar"></i> Laba Bulan Ini</h6>
                <h3>Rp {{ number_format($monthProfit, 0, ',', '.') }}</h3>
                <small>pemasukan - pengeluaran</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Menu Cepat -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-link"></i> Menu Cepat
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('cashier') }}" class="btn btn-primary w-100">
                            <i class="fas fa-cash-register"></i> Kasir
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('reports.index') }}" class="btn btn-info w-100">
                            <i class="fas fa-chart-line"></i> Laporan
                        </a>
                    </div>
                    @if(Auth::user()->role == 'admin')
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('products.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-boxes"></i> Produk
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="{{ route('purchases.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-truck"></i> Stok Masuk
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informasi User -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Informasi
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td>Login sebagai</td>
                        <td><strong>{{ Auth::user()->name }}</strong></td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>
                            @if(Auth::user()->role == 'admin')
                                <span class="badge bg-primary">Administrator</span>
                            @else
                                <span class="badge bg-secondary">Kasir</span>
                            @endif
                         </td>
                    </tr>
                    <tr>
                        <td>Last Login</td>
                        <td>{{ date('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi Stok Menipis -->
@if($lowStockProducts->count() > 0)
<div class="row mt-3">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-exclamation-triangle"></i> Notifikasi Stok Menipis
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Produk</th><th>Stok Saat Ini</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><span class="text-danger">{{ $product->stock }}</span></td>
                            <td>
                                <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-primary">
                                    Tambah Stok
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Transaksi Terbaru -->
@if($recentTransactions->count() > 0)
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history"></i> Transaksi Terbaru
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Invoice</th><th>Tanggal</th><th>Total</th><th>Kasir</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->invoice_number }}</td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            <td>{{ $transaction->cashier }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Analisa Produk Laku & Kurang Diminati -->
<div class="row mt-3">
    <!-- Produk Paling Laku -->
    <div class="col-md-6 mb-3">
        <div class="card border-success h-100">
            <div class="card-header bg-success text-white">
                <i class="fas fa-arrow-up"></i> Barang Paling Laku
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Produk</th><th>Total Terjual</th><th>Stok Tersisa</th></tr>
                    </thead>
                    <tbody>
                        @forelse($bestSelling as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><strong>{{ $product->transaction_items_sum_quantity ?? 0 }}</strong> {{ $product->unit }}</td>
                            <td>{{ $product->stock }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Produk Kurang Diminati -->
    <div class="col-md-6 mb-3">
        <div class="card border-danger h-100">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-arrow-down"></i> Barang Kurang Diminati
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Produk</th><th>Total Terjual</th><th>Stok Tersisa</th></tr>
                    </thead>
                    <tbody>
                        @forelse($leastSelling as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><strong>{{ $product->transaction_items_sum_quantity ?? 0 }}</strong> {{ $product->unit }}</td>
                            <td><span class="text-danger">{{ $product->stock }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada data penjualan</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <small class="text-muted"><i class="fas fa-info-circle"></i> Pertimbangkan untuk memberikan diskon atau mengurangi restok untuk barang-barang ini.</small>
            </div>
        </div>
    </div>
</div>
@endsection