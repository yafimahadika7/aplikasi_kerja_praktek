<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>VA Info</title>
</head>
<body>
    <h3>Halo {{ $transaksi->nama }}</h3>
    <p>Terima kasih telah melakukan pemesanan di Bellybee.</p>
    <p>Berikut rincian pembayaran Anda:</p>

    <ul>
        <li>Bank: {{ $transaksi->metode_pembayaran }}</li>
        <li>Virtual Account: <strong>{{ $transaksi->va_number }}</strong></li>
        <li>Total: <strong>Rp{{ number_format($transaksi->total, 0, ',', '.') }}</strong></li>
        <li>Berlaku sampai: {{ \Carbon\Carbon::parse($transaksi->expired_at)->format('d-m-Y H:i') }}</li>
    </ul>

    <p>Segera lakukan pembayaran sebelum VA expired.</p>
</body>
</html>