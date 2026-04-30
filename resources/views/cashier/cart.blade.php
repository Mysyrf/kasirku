<?php
$cart = session()->get('cart', []);
$subtotal = 0;
$totalDiscount = 0;
?>

@if(count($cart) > 0)
    @foreach($cart as $item)
    <div class="cart-item">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <strong>{{ $item['name'] }}</strong>
                <div class="text-muted small">
                    @if(isset($item['discount_percent']) && $item['discount_percent'] > 0)
                        <span class="text-danger">Diskon {{ $item['discount_percent'] }}%</span><br>
                    @endif
                    @php
                        $itemTotal = $item['subtotal'] + ($item['discount'] ?? 0);
                        $subtotal += $itemTotal;
                        $totalDiscount += $item['discount'] ?? 0;
                    @endphp
                    Harga: Rp {{ number_format($item['price_per_item'], 0, ',', '.') }}
                </div>
                <div class="mt-1">
                    <input type="number" 
                           class="form-control form-control-sm cart-qty" 
                           data-id="{{ $item['id'] }}"
                           value="{{ $item['quantity'] }}" 
                           min="1" 
                           style="width: 70px; display: inline-block;">
                    <span class="ms-2">
                        = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                    </span>
                    @if($item['discount'] > 0)
                        <small class="text-success d-block">(termasuk diskon)</small>
                    @endif
                </div>
            </div>
            <button class="btn btn-sm btn-danger remove-item" data-id="{{ $item['id'] }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endforeach
    
    <div class="mt-3 pt-3 border-top">
        @if($totalDiscount > 0)
        <div class="d-flex justify-content-between text-success">
            <span>Diskon Total:</span>
            <span>-Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="d-flex justify-content-between">
            <strong>Total:</strong>
            <strong class="text-primary" id="cartTotal" data-total="{{ $subtotal - $totalDiscount }}">
                Rp {{ number_format($subtotal - $totalDiscount, 0, ',', '.') }}
            </strong>
        </div>
    </div>
@else
    <div class="text-center text-muted py-5">
        <i class="fas fa-cart-plus fa-3x mb-3"></i>
        <p>Keranjang masih kosong</p>
    </div>
@endif