@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between">
        <h5><i class="fas fa-receipt"></i> Detail Transaksi</h5>
        <div>
            <a href="{{ route('reports.print', $transaction->id) }}" class="btn btn-light btn-sm" target="_blank">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="{{ route('reports.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">No. Invoice</th>
                        <td>: {{ $transaction->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td>: {{ $transaction->customer_name ?? 'Umum' }}</td>
                    </tr>
                    <tr>
                        <th>Tipe Transaksi</th>
                        <td>: {{ $transaction->type == 'retail' ? 'Eceran' : 'Grosir' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Kasir</th>
                        <td>: {{ $transaction->cashier }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: <span class="badge bg-success">Selesai</span></td>
                    </tr>
                    <tr>
                        <th>Metode Bayar</th>
                        <td>: Tunai</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <h6 class="mt-4">Detail Produk</h6>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <th colspan="4" class="text-end">Subtotal:</th>
                        <th>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</th>
                    </tr>
                    @if($transaction->discount > 0)
                    <tr>
                        <th colspan="4" class="text-end">Diskon:</th>
                        <th>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</th>
                    </tr>
                    @endif
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th class="text-primary">Rp {{ number_format($transaction->total, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Bayar:</th>
                        <th>Rp {{ number_format($transaction->paid, 0, ',', '.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end">Kembali:</th>
                        <th>Rp {{ number_format($transaction->change, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection