<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            width: 220px;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .sidebar.hide {
            left: -220px;
        }
        .sidebar a, .sidebar form button {
            color: white;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
        }
        .sidebar a:hover, .sidebar form button:hover {
            background-color: #495057;
        }
        .topbar {
            height: 60px;
            background-color: #f8f9fa;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-left: 220px;
            transition: margin-left 0.3s ease;
        }
        .topbar.collapsed {
            margin-left: 0;
        }
        .content {
            margin-left: 220px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }
        .content.collapsed {
            margin-left: 0;
        }
        .toggle-btn {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .sidebar {
                left: -220px;
            }
            .sidebar.show {
                left: 0;
            }
            .topbar, .content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="text-center mb-3">
            <strong>{{ Auth::user()->name }}</strong><br>
            <small class="{{ Auth::user()->role === 'admin' ? 'text-warning' : 'text-white' }}">
                {{ ucfirst(Auth::user()->role) }}
            </small>
        </div>
        @if (in_array(Auth::user()->role, ['admin', 'operation', 'finance', 'produk']))
            <a href="#">📊 Dashboard</a>
        @endif

        @if (in_array(Auth::user()->role, ['admin', 'operation']))
            <a href="{{ route('admin.transaksi.index') }}">💳 Transaksi</a>
        @endif

        @if (in_array(Auth::user()->role, ['admin', 'produk']))
            <a href="{{ route('admin.produk.index') }}">🛍️ Produk</a>
        @endif

        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}">👤 User</a>
        @endif

        @if (in_array(Auth::user()->role, ['admin', 'finance']))
            <a href="#">📈 Penjualan</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>

    <!-- Topbar -->
    <div class="topbar" id="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <span>Manajemen Transaksi</span>
    </div>

    <div class="content" id="main-content">
        <h4>Daftar Transaksi</h4>

        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari nama/email/VA..." value="{{ request('search') }}">
                    <input type="date" name="from" class="form-control me-2" value="{{ request('from') }}">
                    <input type="date" name="to" class="form-control me-2" value="{{ request('to') }}">
                    <button class="btn btn-outline-secondary" type="submit">Filter</button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>VA</th>
                        <th>Expired</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                    <tr>
                        <td>{{ $trx->nama }}</td>
                        <td>{{ $trx->email }}</td>
                        <td>{{ $trx->telepon }}</td>
                        <td>{{ $trx->metode_pembayaran }}</td>
                        <td>Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td>{{ $trx->va_number }}</td>
                        <td>{{ $trx->expired_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.transaksi.update', $trx->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $trx->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="proses" {{ $trx->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                    <option value="sukses" {{ $trx->status == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                    <option value="gagal" {{ $trx->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </select>

                            </form>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="alert(JSON.stringify({!! json_encode($trx->items) !!}, null, 2))">Lihat Item</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('main-content');
            const topbar = document.getElementById('topbar');

            sidebar.classList.toggle('hide');
            content.classList.toggle('collapsed');
            topbar.classList.toggle('collapsed');
        }
    </script>

</body>
</html>