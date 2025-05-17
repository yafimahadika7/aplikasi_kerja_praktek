<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            position: relative;
            background-image: url('/images/BG-01.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            min-height: 100vh;
            padding-top: 100px;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: transparent;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            z-index: 100;
        }

        .topbar .brand {
            font-weight: bold;
            font-size: 1.75rem;
        }

        .category-scroll {
            overflow-x: auto;
            white-space: nowrap;
            padding: 0 1rem;
        }

        .category-btn {
            display: inline-block;
            margin: 5px 24px;
            text-align: center;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .category-icon {
            background-color: #f0f0f0;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .category-icon img {
            width: 35px;
            height: 35px;
        }

        .category-btn.active .category-icon,
        .category-btn:hover .category-icon {
            background-color: #333;
        }

        .category-btn.active,
        .category-btn:hover {
            color: white;
        }

        .product-card {
            border-radius: 8px;
            padding: 0;
            border: none;
            width: 180px;
            margin: auto;
        }

        .product-card img {
            width: 100%;
            height: 240px;
            object-fit: contain;
            border-radius: 8px 8px 0 0;
        }

        .product-wrapper {
            padding-left: 8px;
            padding-right: 8px;
        }

        .card-body {
            padding: 10px;
            color: black;
        }

        .search-bar {
            max-width: 500px;
            margin: 0 auto 1.5rem auto;
        }

        .container > h2 {
            margin-bottom: 1rem;
            margin-top: -20px;
        }

        .btn-group-custom .btn {
            width: 50%;
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            color: black !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <a href="{{ url('/') }}" class="brand text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> <span class="fw-bold">bellybee</span>
        </a>
        <form method="GET" action="{{ route('produk.index') }}" class="d-flex" style="flex-grow:1; max-width: 500px; margin: 0 1rem;">
            <input type="text" name="search" class="form-control me-2" placeholder="Search product, trend, or brand..." value="{{ request('search') }}">
            <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('keranjang.index') }}" class="text-white text-decoration-none">
            <i class="bi bi-bag fs-5"></i>
        </a>
    </div>

    <div class="container py-4">
        <h2 class="text-center">Our Products</h2>

        @php
            $kategoriList = ['Dress', 'Shirt', 'Blouse', 'Tunic', 'Outerwear', 'Skirt', 'Pants', 'One Set', 'Hijab', 'Prayer Set'];
        @endphp

        <div class="category-scroll my-3">
            @foreach ($kategoriList as $kategori)
                @php
                    $iconName = strtolower(str_replace(' ', '', $kategori)) . '.png';
                @endphp
                <a href="{{ route('produk.index', ['filter' => $kategori]) }}"
                   class="category-btn {{ request('filter') === $kategori ? 'active' : '' }}">
                    <div class="category-icon">
                        <img src="{{ asset('icons/' . $iconName) }}" alt="{{ $kategori }}">
                    </div>
                    <div>{{ $kategori }}</div>
                </a>
            @endforeach
        </div>

        <div class="row justify-content-start">
            @if (request('filter'))
                @forelse ($produk as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 mb-4 product-wrapper">
                        <div class="card product-card shadow-sm">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                            @else
                                <img src="https://via.placeholder.com/300x220?text=No+Image" alt="No Image">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->nama }}</h5>
                                <p class="text-muted mb-1">{{ $item->kategori }}</p>
                                <p class="fw-bold">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                                <div class="d-flex justify-content-between btn-group-custom">
                                    <a href="#" class="btn btn-primary">Beli</a>
                                    <button class="btn btn-outline-light btn-keranjang" 
                                        data-produk='{{ json_encode([
                                            "id" => $item->id,
                                            "nama" => $item->nama,
                                            "kategori" => $item->kategori,
                                            "deskripsi" => $item->deskripsi,
                                            "gambar" => asset("storage/" . $item->gambar)
                                        ]) }}'>Keranjang</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Produk tidak ditemukan dalam kategori ini.</p>
                @endforelse
            @else
                <p class="text-center">Silakan pilih kategori di atas untuk melihat produk.</p>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Tambah ke Keranjang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column flex-md-row align-items-start">
                    <img id="modalImage" src="" class="img-fluid me-md-4 mb-3 mb-md-0" style="max-width: 300px;">
                    <div class="flex-grow-1">
                        <h5 id="modalNama" class="text-dark fw-bold"></h5>
                        <p id="modalKategori" class="text-dark mb-1"></p>
                        <p id="modalDeskripsi" class="text-dark small"></p>
                        <div class="mb-3">
                            <label for="ukuran" class="form-label text-dark">Pilih Ukuran</label>
                            <select class="form-select" id="ukuran">
                                <option>S</option>
                                <option>M</option>
                                <option>L</option>
                                <option>XL</option>
                                <option>All Size</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label text-dark">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" min="1" value="1">
                        </div>
                        <button class="btn btn-primary" id="btnTambahKeranjang">Tambah ke Keranjang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentProdukId = null;

        document.querySelectorAll('.btn-keranjang').forEach(button => {
            button.addEventListener('click', function () {
                const product = JSON.parse(this.getAttribute('data-produk'));
                currentProdukId = product.id;
                document.getElementById('modalNama').innerText = product.nama;
                document.getElementById('modalKategori').innerText = product.kategori;
                document.getElementById('modalDeskripsi').innerText = product.deskripsi || '-';
                document.getElementById('modalImage').src = product.gambar;
                new bootstrap.Modal(document.getElementById('cartModal')).show();
            });
        });

        document.getElementById('btnTambahKeranjang').addEventListener('click', function () {
            const ukuran = document.getElementById('ukuran').value;
            const jumlah = document.getElementById('jumlah').value;

            fetch("{{ route('keranjang.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ produk_id: currentProdukId, ukuran, jumlah })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const toastEl = document.getElementById('toastSuccess');
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                    bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
                }
            });
        });
    </script>

    <div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
    <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000" data-bs-autohide="true">
        <div class="d-flex">
        <div class="toast-body">
            Produk berhasil ditambahkan ke keranjang.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    </div>

</body>
</html>