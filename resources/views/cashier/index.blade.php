@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Kolom Kiri: Daftar Produk -->
    <div class="col-md-7">
        <div class="card">
        <!-- Banner Scan Barcode -->
        <div class="alert alert-info mb-3">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <i class="fas fa-barcode"></i> <strong>Scan Barcode</strong>
                </div>
                <div class="col-md-7">
                    <div class="input-group">
                        <input type="text" id="barcodeInput" class="form-control" 
                            placeholder="Tempelkan barcode di sini..." autofocus>
                        <button class="btn btn-primary" id="btnScanBarcode">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>


            <div class="card-header">
                <i class="fas fa-box"></i> Daftar Produk
                <div class="float-end">
                    <input type="text" id="searchProduct" class="form-control form-control-sm" placeholder="Cari produk..." style="width: 200px;">
                </div>
            </div>
            <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                <div class="row" id="productList">
                    @foreach($products as $product)
                    <div class="col-md-6 mb-3">
                        <div class="card product-card" data-product='@json($product)'>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="card-title">{{ $product->name }}</h6>
                                    <span class="badge bg-secondary">{{ $product->unit }}</span>
                                </div>
                                <p class="text-muted small mb-1">{{ $product->category->name ?? 'Umum' }}</p>
                                <p class="text-muted small">Stok: {{ $product->stock }}</p>
                                <h5 class="text-primary mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                                <div class="input-group input-group-sm">
                                    <input type="number" class="form-control qty" value="1" min="1" max="{{ $product->stock }}">
                                    <button class="btn btn-primary btn-add">
                                        <i class="fas fa-cart-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Kolom Kanan: Keranjang Belanja -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-shopping-cart"></i> Keranjang Belanja
            </div>
            <div class="card-body" style="max-height: 60vh; overflow-y: auto;" id="cartContainer">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-cart-plus fa-3x mb-3"></i>
                    <p>Keranjang masih kosong</p>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2">
                    <button class="btn btn-danger btn-sm" id="clearCartBtn">
                        <i class="fas fa-trash"></i> Kosongkan Keranjang
                    </button>
                    <button class="btn btn-primary" id="checkoutBtn" disabled>
                        <i class="fas fa-money-bill"></i> Checkout & Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave"></i> Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="customerName" placeholder="Nama (opsional)">
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Belanja</label>
                    <h3 id="modalTotal" class="text-primary">Rp 0</h3>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Bayar</label>
                    <input type="number" class="form-control" id="paidAmount" placeholder="Masukkan jumlah uang">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kembalian</label>
                    <h4 id="changeAmount" class="text-success">Rp 0</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="processPaymentBtn">
                    <i class="fas fa-check"></i> Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Struk -->
<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-print"></i> Transaksi Berhasil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="receiptContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printReceipt()">
                    <i class="fas fa-print"></i> Cetak Struk
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ============ FUNGSI PRINT STRUK ============
function printReceipt() {
    var content = document.getElementById("receiptContent").innerHTML;
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
    
    var doc = iframe.contentWindow.document;
    doc.write('<html><head><title>Struk Pembayaran</title>');
    // Style khusus untuk iframe print
    doc.write('<style>body { font-family: monospace; padding: 10px; color: #000; } @page { margin: 0; } </style>');
    doc.write('</head><body>');
    doc.write(content);
    doc.write('</body></html>');
    doc.close();
    
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
    
    setTimeout(function() {
        document.body.removeChild(iframe);
    }, 1000);
}

// ============ BARCODE SCANNER ============

// Auto focus ke input barcode
function setFocusToBarcode() {
    $('#barcodeInput').focus();
}

// Scan barcode dengan Enter
$('#barcodeInput').on('keypress', function(e) {
    
    if (e.which === 13) {
        e.preventDefault();
        let barcode = $(this).val().trim();
        
        if (barcode !== '') {
            processBarcodeSearch(barcode);
        }
    }
});

// Tombol cari manual
$('#btnScanBarcode').click(function() {
    let barcode = $('#barcodeInput').val().trim();
    if (barcode !== '') {
        processBarcodeSearch(barcode);
    } else {
        toastr.warning('Masukkan barcode terlebih dahulu');
    }
});

function processBarcodeSearch(barcode) {
    $.ajax({
        url: '/product/by-barcode/' + barcode,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Tampilkan modal konfirmasi quantity
                showQuantityModal(response.product);
                $('#barcodeInput').val('');
                setFocusToBarcode();
            } else {
                toastr.error('Produk dengan barcode ' + barcode + ' tidak ditemukan!');
                $('#barcodeInput').val('');
                setFocusToBarcode();
            }
        },
        error: function() {
            toastr.error('Terjadi kesalahan, coba lagi!');
            $('#barcodeInput').val('');
            setFocusToBarcode();
        }
    });
}


function addToCartByProduct(product, quantity) {
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            product_id: product.id,
            quantity: quantity
        },
        success: function(response) {
            if (response.success) {
                loadCart();
                toastr.success(quantity + ' ' + product.name + ' ditambahkan');
            } else {
                toastr.error(response.message);
            }
        }
    });
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// Klik di mana saja akan focus ke barcode, kecuali jika klik pada input lain atau modal sedang terbuka
$(document).on('click', function(e) {
    if (!$(e.target).is('input, textarea, select, button') && !$(e.target).closest('.modal').length) {
        $('#barcodeInput').focus();
    }
});

// Inisialisasi
$(document).ready(function() {
    setFocusToBarcode();
});

// Fungsi untuk menambah langsung ke keranjang
function addToCartByProduct(product) {
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            product_id: product.id,
            quantity: 1
        },
        success: function(response) {
            if (response.success) {
                loadCart();
                toastr.success(product.name + ' ditambahkan');
            } else {
                toastr.error(response.message);
            }
        }
    });
}

$(document).ready(function() {
    loadCart();
    
    // Cari produk
    $('#searchProduct').on('keyup', function() {
        let search = $(this).val().toLowerCase();
        $('#productList .col-md-6').each(function() {
            let name = $(this).find('.card-title').text().toLowerCase();
            $(this).toggle(name.includes(search));
        });
    });
    
    // Tambah ke keranjang
    $(document).on('click', '.btn-add', function() {
        let card = $(this).closest('.product-card');
        let product = card.data('product');
        let quantity = parseInt(card.find('.qty').val());
        
        if (quantity > product.stock) {
            toastr.error('Stok tidak mencukupi! Stok tersedia: ' + product.stock);
            return;
        }
        
        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product.id,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    loadCart();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
    
    // Load keranjang
    function loadCart() {
        $.ajax({
            url: '/cart',
            method: 'GET',
            success: function(response) {
                $('#cartContainer').html(response.cart_html);
                
                // Cek total
                let total = $('#cartTotal').data('total');
                if (total > 0) {
                    $('#checkoutBtn').prop('disabled', false);
                } else {
                    $('#checkoutBtn').prop('disabled', true);
                }
            }
        });
    }
    
    // Update quantity
    $(document).on('change', '.cart-qty', function() {
        let productId = $(this).data('id');
        let quantity = $(this).val();
        
        $.ajax({
            url: '/cart/update',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    loadCart();
                } else {
                    toastr.error(response.message);
                    loadCart();
                }
            }
        });
    });
    
    // Hapus item
    $(document).on('click', '.remove-item', function() {
        let productId = $(this).data('id');
        
        $.ajax({
            url: '/cart/remove/' + productId,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                if (response.success) {
                    loadCart();
                    toastr.info('Item dihapus dari keranjang');
                }
            }
        });
    });
    
    // Kosongkan keranjang
    $('#clearCartBtn').click(function() {
        if (confirm('Yakin ingin mengosongkan keranjang?')) {
            $.ajax({
                url: '/cart/clear',
                method: 'POST',
                data: {_token: '{{ csrf_token() }}'},
                success: function(response) {
                    loadCart();
                    toastr.info('Keranjang dikosongkan');
                }
            });
        }
    });
    
    // Checkout
    $('#checkoutBtn').click(function() {
        let total = $('#cartTotal').data('total');
        if (total == 0) {
            toastr.warning('Keranjang masih kosong!');
            return;
        }
        
        $('#modalTotal').text(formatRupiah(total));
        $('#paymentModal').modal('show');
    });
    
    // Hitung kembalian
    $('#paidAmount').on('input', function() {
        let total = parseInt($('#cartTotal').data('total'));
        let paid = parseInt($(this).val()) || 0;
        let change = paid - total;
        
        if (change >= 0) {
            $('#changeAmount').text(formatRupiah(change));
        } else {
            $('#changeAmount').text('Rp 0');
        }
    });
    
    // Proses pembayaran
    $('#processPaymentBtn').click(function() {
        let paid = parseInt($('#paidAmount').val()) || 0;
        let total = parseInt($('#cartTotal').data('total'));
        
        if (paid < total) {
            toastr.error('Uang tidak mencukupi!');
            return;
        }
        
        $.ajax({
            url: '/transaction/process',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                paid: paid,
                customer_name: $('#customerName').val()
            },
            success: function(response) {
                if (response.success) {
                    $('#paymentModal').modal('hide');
                    $('#receiptContent').html(generateReceipt(response.data));
                    $('#receiptModal').modal('show');
                    loadCart();
                    $('#paidAmount').val('');
                    $('#customerName').val('');
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
    
    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }
    
    function generateReceipt(data) {
        let itemsHtml = '';
        data.items.forEach(item => {
            itemsHtml += `
                <div style="margin-bottom: 10px;">
                    <div><strong>${item.name}</strong></div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>${item.quantity} x ${formatRupiah(item.price_per_item)}</span>
                        <span>${formatRupiah(item.subtotal)}</span>
                    </div>
                </div>
            `;
        });
        
        return `
            <div style="font-family: monospace; font-size: 12px;">
                <div style="text-align: center; margin-bottom: 15px;">
                    <h4>TOKO RETAIL</h4>
                    <p>${new Date().toLocaleString()}</p>
                    <p><strong>No. Invoice: ${data.invoice_number}</strong></p>
                    <p>Pelanggan: ${data.customer_name || 'Umum'}</p>
                    <p>Tipe: ${data.transaction_type == 'wholesale' ? 'GROSIR' : 'ECERAN'}</p>
                </div>
                <div style="border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 10px 0;">
                    ${itemsHtml}
                </div>
                <div style="margin-top: 10px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Subtotal:</span>
                        <span>${formatRupiah(data.subtotal)}</span>
                    </div>
                    ${data.discount_percent > 0 ? `
                        <div style="display: flex; justify-content: space-between;">
                            <span>Diskon ${data.discount_percent}%:</span>
                            <span>-${formatRupiah(data.discount_amount)}</span>
                        </div>
                    ` : ''}
                    <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 5px;">
                        <span>TOTAL:</span>
                        <span>${formatRupiah(data.total)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Bayar:</span>
                        <span>${formatRupiah(data.paid)}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Kembali:</span>
                        <span>${formatRupiah(data.change)}</span>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px; border-top: 1px dashed #000; padding-top: 10px;">
                    <p>Terima Kasih!<br>Selamat Belanja Kembali</p>
                    <p>Kasir: ${data.cashier}</p>
                </div>
            </div>
        `;
    }
});
</script>
@endpush