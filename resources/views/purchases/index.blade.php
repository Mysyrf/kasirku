@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h5><i class="fas fa-truck"></i> Riwayat Stok Masuk</h5>
        <a href="{{ route('purchases.create') }}" class="btn btn-light btn-sm">
            <i class="fas fa-plus"></i> Tambah Stok
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No. PO</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->invoice_number }}</td>
                        <td>{{ $purchase->product->name ?? '-' }}</td>
                        <td>{{ $purchase->quantity }} {{ $purchase->product->unit ?? '' }}</td>
                        <td>Rp {{ number_format($purchase->buy_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                        <td>{{ date('d/m/Y', strtotime($purchase->purchase_date)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data stok masuk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $purchases->links() }}
    </div>
</div>
@endsection