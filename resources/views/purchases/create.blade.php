@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5><i class="fas fa-plus"></i> Tambah Stok Barang</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('purchases.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Produk</label>
                    <select name="product_id" class="form-control" required>
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} - Stok: {{ $product->stock }} 
                            (Modal: Rp {{ number_format($product->avg_cost_price ?? 0, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Jumlah</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Jumlah" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Harga Beli per Item</label>
                    <input type="number" name="buy_price" id="buy_price" class="form-control" placeholder="Harga beli" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Total Harga</label>
                    <input type="text" id="total_price" class="form-control" readonly>
                </div>
                <div class="col-12 mb-3">
                    <label>Catatan</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('quantity')?.addEventListener('input', calculateTotal);
document.getElementById('buy_price')?.addEventListener('input', calculateTotal);

function calculateTotal() {
    let qty = parseInt(document.getElementById('quantity')?.value) || 0;
    let price = parseInt(document.getElementById('buy_price')?.value) || 0;
    let total = qty * price;
    document.getElementById('total_price').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}
</script>
@endsection