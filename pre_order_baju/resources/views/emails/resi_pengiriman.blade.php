<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resi Pengiriman</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hai {{ $transaksi->nama }},</h2>
    <p>Pesanan Anda telah kami kirimkan menggunakan kurir standar.</p>
    <p><strong>Nomor Resi: {{ $transaksi->serial_number }}</strong></p>
    <p>Silakan pantau status pengiriman Anda secara berkala melalui situs atau aplikasi ekspedisi yang digunakan.</p>
    <p>Terima kasih telah berbelanja bersama kami.</p>

    <br>
    <p>Salam hangat,</p>
    <p><strong>Tim Pre Order Baju</strong></p>
</body>
</html>