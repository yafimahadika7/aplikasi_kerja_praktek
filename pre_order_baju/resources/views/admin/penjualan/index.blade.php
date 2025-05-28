<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Penjualan</title>
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

        .sidebar a,
        .sidebar form button {
            color: white;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
        }

        .sidebar a:hover,
        .sidebar form button:hover {
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

            .topbar,
            .content {
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
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
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

        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'finance')
            <a href="{{ route('admin.penjualan.index') }}">📈 Penjualan</a>
        @endif

        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'operation')
            <a href="{{ route('admin.tiketing.index') }}">💬 Tiketing</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 Logout</button>
        </form>
    </div>

    <!-- Topbar -->
    <div class="topbar" id="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <span>Manajemen Penjualan</span>
    </div>

    <!-- Content -->
    <div class="content" id="main-content">
        <h4>Laporan Penjualan</h4>

        <div class="d-flex justify-content-between mb-3">
            <form action="{{ route('admin.penjualan.index') }}" method="GET" class="d-flex flex-wrap align-items-end">
                <div class="me-2 mb-2">
                    <label for="from" class="form-label mb-1">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="me-2 mb-2">
                    <label for="to" class="form-label mb-1">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="me-2 mb-2">
                    <label for="search" class="form-label mb-1">Nama / Email</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Cari nama/email..."
                        value="{{ request('search') }}">
                </div>
                <div class="me-2 mb-2">
                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                </div>
                <div class="mb-2">
                    <a href="{{ route('admin.penjualan.export', request()->all()) }}" class="btn btn-success">⬇️ Export
                        Excel</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Total</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalAll = 0; @endphp
                    @forelse ($transaksis as $trx)
                        <tr>
                            <td>{{ $trx->created_at->format('Y-m-d') }}</td>
                            <td>{{ $trx->nama }}</td>
                            <td>{{ $trx->email }}</td>
                            <td>{{ $trx->telepon }}</td>
                            <td>Rp{{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td>{{ $trx->metode_pembayaran }}</td>
                        </tr>
                        @php $totalAll += $trx->total; @endphp
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-warning">
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td colspan="2" class="fw-bold">Rp{{ number_format($totalAll, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
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