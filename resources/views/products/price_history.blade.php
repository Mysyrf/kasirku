@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Perubahan Harga</h5>
        <a href="{{ route('products.price') }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left"></i> Manajemen Harga
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="150">Waktu</th>
                        <th>Produk</th>
                        <th>Eceran (Lama &rarr; Baru)</th>
                        <th>Grosir (Lama &rarr; Baru)</th>
                        <th>Diskon (%)</th>
                        <th>Diedit Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d M Y, H:i') }}</td>
                        <td><strong>{{ $history->product->name ?? 'Produk Dihapus' }}</strong></td>
                        
                        <!-- Eceran -->
                        <td>
                            @if($history->old_retail_price != $history->new_retail_price)
                                <del class="text-muted">Rp {{ number_format($history->old_retail_price, 0, ',', '.') }}</del><br>
                                <strong>Rp {{ number_format($history->new_retail_price, 0, ',', '.') }}</strong>
                                @if($history->new_retail_price > $history->old_retail_price)
                                    @php $pct = $history->old_retail_price > 0 ? round((($history->new_retail_price - $history->old_retail_price) / $history->old_retail_price) * 100, 1) : 100; @endphp
                                    <span class="badge bg-success"><i class="fas fa-arrow-up"></i> {{ $pct }}%</span>
                                @elseif($history->new_retail_price < $history->old_retail_price)
                                    @php $pct = $history->old_retail_price > 0 ? round((($history->old_retail_price - $history->new_retail_price) / $history->old_retail_price) * 100, 1) : 0; @endphp
                                    <span class="badge bg-danger"><i class="fas fa-arrow-down"></i> {{ $pct }}%</span>
                                @endif
                            @else
                                <span class="text-muted">Rp {{ number_format($history->new_retail_price, 0, ',', '.') }}</span>
                            @endif
                        </td>

                        <!-- Grosir -->
                        <td>
                            @if($history->old_wholesale_price != $history->new_wholesale_price)
                                <del class="text-muted">Rp {{ number_format($history->old_wholesale_price, 0, ',', '.') }}</del><br>
                                <strong>Rp {{ number_format($history->new_wholesale_price, 0, ',', '.') }}</strong>
                                @if($history->new_wholesale_price > $history->old_wholesale_price)
                                    @php $pct = $history->old_wholesale_price > 0 ? round((($history->new_wholesale_price - $history->old_wholesale_price) / $history->old_wholesale_price) * 100, 1) : 100; @endphp
                                    <span class="badge bg-success"><i class="fas fa-arrow-up"></i> {{ $pct }}%</span>
                                @elseif($history->new_wholesale_price < $history->old_wholesale_price)
                                    @php $pct = $history->old_wholesale_price > 0 ? round((($history->old_wholesale_price - $history->new_wholesale_price) / $history->old_wholesale_price) * 100, 1) : 0; @endphp
                                    <span class="badge bg-danger"><i class="fas fa-arrow-down"></i> {{ $pct }}%</span>
                                @endif
                            @else
                                <span class="text-muted">Rp {{ number_format($history->new_wholesale_price, 0, ',', '.') }}</span>
                            @endif
                        </td>

                        <!-- Diskon -->
                        <td>
                            @if($history->old_discount_percent != $history->new_discount_percent)
                                <del class="text-muted">{{ $history->old_discount_percent }}%</del> &rarr; 
                                <strong>{{ $history->new_discount_percent }}%</strong>
                                @if($history->new_discount_percent > $history->old_discount_percent)
                                    <span class="badge bg-success"><i class="fas fa-arrow-up"></i></span>
                                @elseif($history->new_discount_percent < $history->old_discount_percent)
                                    <span class="badge bg-danger"><i class="fas fa-arrow-down"></i></span>
                                @endif
                            @else
                                <span class="text-muted">{{ $history->new_discount_percent }}%</span>
                            @endif
                        </td>

                        <td><span class="badge bg-secondary"><i class="fas fa-user"></i> {{ $history->changed_by }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                            <p>Belum ada riwayat perubahan harga</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $histories->links() }}
        </div>
    </div>
</div>
@endsection
