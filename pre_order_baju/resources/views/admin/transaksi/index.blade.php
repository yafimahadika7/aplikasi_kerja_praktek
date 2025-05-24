<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Transaksi</title>
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
    <span>Manajemen Transaksi</span>
</div>

<div class="content" id="main-content">
    <h4>Daftar Transaksi</h4>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <input type="checkbox" id="autoRefresh"> <label for="autoRefresh">Auto Refresh</label>
        </div>
        <button class="btn btn-sm btn-outline-primary" onclick="refreshPage()">🔄 Refresh</button>
    </div>

    <div class="d-flex justify-content-between flex-wrap mb-3">
        <form method="GET" class="d-flex flex-wrap align-items-end">
            <div class="me-2 mb-2">
                <label for="search" class="form-label mb-1">Nama / Email</label>
                <input type="text" name="search" class="form-control" placeholder="Cari nama/email..." value="{{ request('search') }}">
            </div>
            <div class="me-2 mb-2">
                <label for="from" class="form-label mb-1">Dari Tanggal</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="me-2 mb-2">
                <label for="to" class="form-label mb-1">Sampai Tanggal</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="me-2 mb-2">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
            </div>
        </form>

        <div class="mb-2">
            <label class="form-label mb-1 d-block">&nbsp;</label>
            <a href="{{ route('admin.transaksi.export', request()->all()) }}" class="btn btn-success">⬇️ Export Excel</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white">
            <thead class="table-dark text-center">
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Email</th>
                
                    <th>Metode</th>
                    <th>Pembelian</th>
                    <th>Serial Number</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $trx)
                <tr>
                    <td>{{ $trx->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $trx->nama }}</td>
                    <td>{{ $trx->email }}</td>
                    
                    <td>{{ $trx->metode_pembayaran }}</td>
                    <td>
                        @php
                            $items = json_decode($trx->items, true);
                        @endphp

                        @if ($items)
                            <ul class="mb-0 ps-3">
                                @foreach ($items as $item)
                                    <li>
                                        {{ $item['nama'] }} (Ukuran: {{ $item['ukuran'] }}, Jumlah: {{ $item['jumlah'] }}, 
                                        Harga: Rp{{ number_format($item['harga'], 0, ',', '.') }})
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <em>Tidak ada item</em>
                        @endif
                    </td>
                    <td>{{ $trx->serial_number ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.transaksi.update', $trx->id) }}">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="pending" {{ $trx->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="proses" {{ $trx->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="sukses" {{ $trx->status == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                <option value="gagal" {{ $trx->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
                            </select>
                            <input type="hidden" name="serial_number" value="{{ $trx->serial_number }}">
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.transaksi.edit', $trx->id) }}" class="btn btn-sm btn-info">Update SN</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('main-content');
    const topbar = document.getElementById('topbar');

    sidebar.classList.toggle('hide');
    content.classList.toggle('collapsed');
    topbar.classList.toggle('collapsed');
}

let autoRefreshInterval = null;

document.addEventListener('DOMContentLoaded', function () {
    const checkbox = document.getElementById('autoRefresh');
    const isAuto = localStorage.getItem('autoRefreshEnabled') === 'true';

    if (isAuto) {
        checkbox.checked = true;
        startAutoRefresh();
    }

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            localStorage.setItem('autoRefreshEnabled', 'true');
            startAutoRefresh();
        } else {
            localStorage.setItem('autoRefreshEnabled', 'false');
            stopAutoRefresh();
        }
    });
});

function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        location.reload();
    }, 10000);
}

function stopAutoRefresh() {
    clearInterval(autoRefreshInterval);
}

function refreshPage() {
    location.reload();
}
</script>

</body>
</html>