@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-chart-line"></i> Laporan & Riwayat Transaksi</h5>
    </div>
    <div class="card-body">
        <!-- Form Filter -->
        <form method="GET" action="{{ route('reports.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
        
        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Pendapatan</h6>
                        <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Transaksi</h6>
                        <h3>{{ $totalTransactions }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6>Eceran / Grosir</h6>
                        <h3>{{ $retailCount }} / {{ $wholesaleCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6>Total Diskon</h6>
                        <h3>Rp {{ number_format($totalDiscount, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tabel Transaksi -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Tipe</th>
                        <th>Subtotal</th>
                        <th>Diskon</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->invoice_number }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $transaction->customer_name ?? 'Umum' }}</td>
                        <td>
                            @if($transaction->type == 'retail')
                                <span class="badge bg-info">Eceran</span>
                            @else
                                <span class="badge bg-warning">Grosir</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                        <td class="fw-bold">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('reports.show', $transaction->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('reports.print', $transaction->id) }}" class="btn btn-sm btn-secondary" target="_blank">
                                <i class="fas fa-print"></i> Cetak
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $transactions->links() }}
    </div>
</div>
@endsection