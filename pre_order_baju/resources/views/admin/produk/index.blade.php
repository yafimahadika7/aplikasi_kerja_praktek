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
        <span>Manajemen Produk</span>
    </div>

    <!-- Content -->
    <div class="content" id="main-content">
        <h4>Daftar Produk</h4>

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>

            <form action="{{ route('admin.produk.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari nama produk..." value="{{ request('search') }}">
                <select name="filter" class="form-select me-2">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Shirt" {{ request('filter') == 'Shirt' ? 'selected' : '' }}>Shirt</option>
                    <option value="Blouse" {{ request('filter') == 'Blouse' ? 'selected' : '' }}>Blouse</option>
                    <option value="Tunic" {{ request('filter') == 'Tunic' ? 'selected' : '' }}>Tunic</option>
                    <option value="Outerwear" {{ request('filter') == 'Outerwear' ? 'selected' : '' }}>Outerwear</option>
                    <option value="Dress" {{ request('filter') == 'Dress' ? 'selected' : '' }}>Dress</option>
                    <option value="Skirt" {{ request('filter') == 'Skirt' ? 'selected' : '' }}>Skirt</option>
                    <option value="Pants" {{ request('filter') == 'Pants' ? 'selected' : '' }}>Pants</option>
                    <option value="One Set" {{ request('filter') == 'One Set' ? 'selected' : '' }}>One Set</option>
                    <option value="Prayer Set" {{ request('filter') == 'Prayer Set' ? 'selected' : '' }}>Prayer Set</option>
                    <option value="Muslim Shirt" {{ request('filter') == 'Muslim Shirt' ? 'selected' : '' }}>Muslim Shirt</option>
                </select>
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produk as $item)
                        <tr class="text-center">
                            <td>
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" width="80">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kategori }}</td>
                            <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $stok = json_decode($item->stock, true);
                                @endphp

                                @if (is_array($stok))
                                    @foreach ($stok as $ukuran => $jumlah)
                                        <div>{{ $ukuran }}: {{ $jumlah }}</div>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.produk.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus produk ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada produk</td>
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