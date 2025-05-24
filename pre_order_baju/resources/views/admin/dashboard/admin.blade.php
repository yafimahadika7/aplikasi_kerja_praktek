<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
    <div class="topbar" id="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <span>Dashboard</span>
    </div>
    <div class="content" id="main-content">
        <h4 class="mb-4">Statistik Ringkas</h4>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card text-bg-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Produk</h5>
                        <h3>{{ $totalProduk ?? '...' }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success shadow">
                    <div class="card-body">
                        <h5 class="card-title">Total Transaksi</h5>
                        <h3>{{ $totalTransaksi ?? '...' }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info shadow">
                    <div class="card-body">
                        <h5 class="card-title">User Terdaftar</h5>
                        <h3>{{ $totalUser ?? '...' }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning shadow">
                    <div class="card-body">
                        <h5 class="card-title">Penjualan Hari Ini</h5>
                        <h3>Rp{{ number_format($penjualanHariIni ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">Statistik Status Transaksi</h5>
                    <div style="max-width: 400px; margin: auto;">
                        <canvas id="statusChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Proses', 'Sukses', 'Gagal'],
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: [
                        {{ $statusCounts['pending'] ?? 0 }},
                        {{ $statusCounts['proses'] ?? 0 }},
                        {{ $statusCounts['sukses'] ?? 0 }},
                        {{ $statusCounts['gagal'] ?? 0 }}
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>

</body>

</html>