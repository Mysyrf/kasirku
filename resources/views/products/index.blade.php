@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-boxes"></i> Manajemen Produk</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Barcode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th colspan="3">Harga</th>
                        <th>Stok</th>
                        <th>Unit</th>
                        <th>Aksi</th>
                    </tr>
                    <tr class="table-secondary">
                        <th colspan="3"></th>
                        <th>Eceran</th>
                        <th>Grosir</th>
                        <th>Min Grosir</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr id="product-row-{{ $product->id }}">
                        <form method="POST" action="{{ route('products.updatePrice', $product->id) }}" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <td>{{ $product->barcode }}</td>
                            <td>{{ $product->name }}
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <input type="number" name="price_retail" class="form-control form-control-sm" 
                                       value="{{ $product->price_retail ?: $product->price }}" style="width: 100px" required>
                            </td>
                            <td>
                                <input type="number" name="price_wholesale" class="form-control form-control-sm" 
                                       value="{{ $product->price_wholesale }}" style="width: 100px">
                            </td>
                            <td>
                                <input type="number" name="wholesale_min_qty" class="form-control form-control-sm" 
                                       value="{{ $product->wholesale_min_qty }}" style="width: 80px">
                            </td>
                            <td>{{ $product->stock }}
                            <td>{{ $product->unit }}
                            <td>
                                <button type="submit" class="btn btn-sm btn-success" title="Simpan Harga">
                                    <i class="fas fa-save"></i>
                                </button>
                        </form>
                                <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $product->id }}" 
                                        data-bs-toggle="modal" data-bs-target="#productModal" title="Edit Data Produk">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('products.destroy', $product->id) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal FORM EDIT DATA PRODUK (BUKAN HARGA) -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Edit Data Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" method="POST">
                    @csrf
                    <input type="hidden" id="product_id" name="product_id">
                    <div class="mb-3">
                        <label>Barcode</label>
                        <div class="input-group">
                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Ketik manual atau klik Auto" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()">
                                <i class="fas fa-magic"></i> Auto
                            </button>
                        </div>
                        <small class="text-muted">Scan barcode, ketik manual, atau buat otomatis</small>
                    </div>
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Unit</label>
                        <select name="unit" id="unit" class="form-control">
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kg</option>
                            <option value="liter">Liter</option>
                            <option value="pack">Pack</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Untuk mengubah harga, langsung edit di tabel.
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('productForm').reset();
    document.getElementById('product_id').value = '';
    document.getElementById('modalTitle').innerHTML = 'Tambah Produk';
    document.getElementById('productForm').action = '{{ route("products.store") }}';
    // Hapus method PUT jika ada
    let methodInput = document.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
}

// Edit produk (DATA PRODUK, BUKAN HARGA)
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.getAttribute('data-id');
        
        fetch('/products/' + id)
            .then(response => response.json())
            .then(product => {
                document.getElementById('product_id').value = product.id;
                document.getElementById('barcode').value = product.barcode;
                document.getElementById('name').value = product.name;
                document.getElementById('category_id').value = product.category_id;
                document.getElementById('unit').value = product.unit;
                document.getElementById('modalTitle').innerHTML = 'Edit Data Produk';
                
                // Ubah action form untuk update
                let form = document.getElementById('productForm');
                form.action = '/products/' + product.id;
                
                // Tambah method PUT jika belum ada
                if (!document.querySelector('input[name="_method"]')) {
                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                }
            });
    });
});

// Auto Generate Barcode (Format: 899 + 9 angka random)
function generateBarcode() {
    let randomPart = Math.floor(Math.random() * 1000000000).toString().padStart(9, '0');
    let generatedBarcode = '899' + randomPart;
    document.getElementById('barcode').value = generatedBarcode;
}

// Auto-focus barcode saat modal dibuka
document.getElementById('productModal').addEventListener('shown.bs.modal', function () {
    document.getElementById('barcode').focus();
});

// Cegah submit form jika menekan Enter di input barcode (karena scanner otomatis menekan Enter)
document.getElementById('barcode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        // Pindah fokus ke input nama produk
        document.getElementById('name').focus();
    }
});
</script>
@endsection