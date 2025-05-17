<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 220px;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
        }
        .sidebar a {
            color: white;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            margin-left: 220px;
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">Admin Panel</h4>
        <a href="#">Dashboard</a>
        <a href="#">Transaksi</a>
        <a href="#">Produk</a>
        <a href="#">User</a>
        <a href="#">Penjualan</a>
        <a href="#">LogOut</a>
    </div>

    <div class="content">
        <h2>Selamat Datang di Dashboard Admin</h2>
        <p>Ini adalah halaman utama admin. Menu di sebelah kiri bisa dikembangkan sesuai fitur.</p>
    </div>
</body>
</html>