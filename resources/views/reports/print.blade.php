<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: monospace;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            margin-bottom: 10px;
        }
        .items {
            margin: 10px 0;
        }
        .item {
            margin-bottom: 5px;
        }
        .total {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        table {
            width: 100%;
        }
        .text-end {
            text-align: right;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3>TOKO MZ</h3>
        <p>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
        <p>{{ $transaction->invoice_number }}</p>
        <p>Kasir: {{ $transaction->cashier }}</p>
        <p>Pelanggan: {{ $transaction->customer_name ?? 'Umum' }}</p>
        <p>Tipe: {{ $transaction->type == 'retail' ? 'ECERAN' : 'GROSIR' }}</p>
    </div>
    
    <div class="items">
        <table>
            @foreach($transaction->items as $item)
            <tr>
                <td colspan="2"><strong>{{ $item->product->name }}</strong></td>
            </tr>
            <tr>
                <td>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    
    <div class="total">
        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-end">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($transaction->discount > 0)
            <tr>
                <td>Diskon</td>
                <td class="text-end">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="text-end"><strong>Rp {{ number_format($transaction->total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-end">Rp {{ number_format($transaction->paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-end">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Terima Kasih!</p>
        <p>Selamat Belanja Kembali</p>
    </div>
</body>
</html>