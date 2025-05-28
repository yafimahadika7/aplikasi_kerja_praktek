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
                    <label class="form-label mb-1">Nama / Email</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama/email..."
                        value="{{ request('search') }}">
                </div>
                <div class="me-2 mb-2">
                    <label class="form-label mb-1">Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="me-2 mb-2">
                    <label class="form-label mb-1">Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="me-2 mb-2">
                    <button type="submit" class="btn btn-outline-primary">Filter</button>
                </div>
            </form>

            <div class="mb-2">
                <label class="form-label mb-1 d-block">&nbsp;</label>
                <a href="{{ route('admin.transaksi.export', request()->all()) }}" class="btn btn-success">⬇️ Export
                    Excel</a>
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
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Harga</th>
                        <th>Serial Number</th>
                        <th>Nomor Resi</th>
                        <th>Status (Item)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksis as $trx)
                        @php
                            $items = DB::table('transaksi_items')->where('transaksi_id', $trx->id)->get();
                            $itemCount = count($items);
                        @endphp

                        @foreach($items as $index => $item)
                            <tr>
                                @if ($index === 0)
                                    <td rowspan="{{ $itemCount }}" class="text-center align-middle">
                                        {{ $trx->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td rowspan="{{ $itemCount }}" class="text-center align-middle">{{ $trx->nama }}</td>
                                    <td rowspan="{{ $itemCount }}" class="text-center align-middle">{{ $trx->email }}</td>
                                    <td rowspan="{{ $itemCount }}" class="text-center align-middle">{{ $trx->metode_pembayaran }}
                                    </td>
                                @endif

                                <td class="align-middle">{{ $item->nama_produk }}</td>
                                <td class="text-center align-middle">{{ $item->ukuran ?? '-' }}</td>
                                <td class="text-center align-middle">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-center align-middle">{{ $item->serial_number ?? '-' }}</td>
                                @if ($index === 0)
                                    <td rowspan="{{ $itemCount }}" class="text-center align-middle">
                                        {{ $item->nomor_resi ?? '-' }}
                                    </td>
                                @endif
                                <td class="text-center align-middle">
                                    <form method="POST" action="{{ route('admin.transaksi.item.update', $item->id) }}">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="gagal" {{ $item->status == 'gagal' ? 'selected' : '' }}>gagal
                                            </option>
                                            <option value="proses" {{ $item->status == 'proses' ? 'selected' : '' }}>Proses
                                            </option>
                                            <option value="sukses" {{ $item->status == 'sukses' ? 'selected' : '' }}>Sukses
                                            </option>
                                            <option value="retur" {{ $item->status == 'retur' ? 'selected' : '' }}>Retur</option>
                                        </select>
                                        <input type="hidden" name="serial_number" value="{{ $item->serial_number }}">
                                        <input type="hidden" name="nomor_resi" value="{{ $item->nomor_resi }}">
                                    </form>
                                </td>

                                @if ($index === 0)
                                    <td rowspan="{{ $itemCount }}" class="text-center align-middle">
                                        <!-- Tombol modal -->
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalResi{{ $trx->id }}">
                                            Kirim Resi
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        <!-- Modal per transaksi -->
                        <div class="modal fade" id="modalResi{{ $trx->id }}" tabindex="-1"
                            aria-labelledby="modalResiLabel{{ $trx->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('admin.transaksi.kirimResi', $trx->id) }}">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Kirim Nomor Resi (Transaksi: {{ $trx->va_number }})</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Tutup"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nomor_resi" class="form-label">Nomor Resi</label>
                                                <input type="text" name="nomor_resi" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status Baru</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="proses">Proses</option>
                                                    <option value="sukses">Sukses</option>
                                                    <option value="retur">Retur</option>
                                                    <option value="gagal">Gagal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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