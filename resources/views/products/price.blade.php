@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-tags"></i> Manajemen Harga Produk</h5>
        <small>Atur harga eceran, grosir, dan diskon per produk</small>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="30">#</th>
                        <th>Barcode</th>
                        <th>Nama Produk</th>
                        <th width="130">Harga Eceran</th>
                        <th width="130">Harga Grosir</th>
                        <th width="100">Min Grosir</th>
                        <th width="100">Diskon (%)</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                    <form method="POST" action="{{ route('products.updatePrice', $product->id) }}">
                        @csrf
                        @method('PUT')
                        <tr id="row-{{ $product->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->barcode }}奔驰
                            <td>{{ $product->name }}
                                <input type="hidden" name="price_type" value="{{ $product->price_wholesale > 0 ? 'retail_wholesale' : 'single' }}">
                            </td>
                            <td>
                                <input type="number" name="price_retail" class="form-control form-control-sm" 
                                       value="{{ $product->price_retail ?: $product->price }}" required>
                            </td>
                            <td>
                                <input type="number" name="price_wholesale" class="form-control form-control-sm" 
                                       value="{{ $product->price_wholesale }}">
                            </td>
                            <td>
                                <input type="number" name="wholesale_min_qty" class="form-control form-control-sm" 
                                       value="{{ $product->wholesale_min_qty }}">
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="discount_percent" class="form-control" 
                                           value="{{ $product->discount_percent }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </td>
                        </tr>
                    </form>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
// Validasi sebelum submit
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        let priceRetail = parseInt(this.querySelector('input[name="price_retail"]').value);
        let priceWholesale = parseInt(this.querySelector('input[name="price_wholesale"]').value) || 0;
        
        if (priceWholesale > 0 && priceWholesale >= priceRetail) {
            e.preventDefault();
            alert('Harga grosir harus lebih murah dari harga eceran!');
        }
    });
});
</script>
@endsection