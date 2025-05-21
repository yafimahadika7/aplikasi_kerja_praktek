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
            opacity: 0;
            animation: fadeInBody 1s ease forwards;
        }

        @keyframes fadeInBody {
            to { opacity: 1; }
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
            animation: fadeInDown 1s ease;
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

        .product-wrapper {
            padding-left: 8px;
            padding-right: 8px;
            animation: fadeInUp 0.6s ease both;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
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

        .card-body {
            padding: 10px;
            color: black;
        }

        .btn-group-modern {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-modern {
            flex: 1;
            padding: 6px 10px;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            color: #fff;
            background-color: #007bff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-modern:hover {
            background-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(220,53,69,0.4);
        }

        .result-alert {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            color: black;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            z-index: 1100;
            text-align: center;
        }
    </style>
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
    <a href="/keranjang" class="text-white text-decoration-none">
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
                            <div class="btn-group-modern">
                                <button class="btn-modern btn-beli"
                                        data-nama="{{ $item->nama }}"
                                        data-harga="{{ $item->harga }}"
                                        data-gambar="{{ asset('storage/' . $item->gambar) }}"
                                        data-ukuran="All Size">
                                    Beli
                                </button>
                                <button class="btn-modern btn-keranjang"
                                        data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama }}"
                                        data-kategori="{{ $item->kategori }}"
                                        data-deskripsi="{{ $item->deskripsi }}"
                                        data-gambar="{{ asset('storage/' . $item->gambar) }}"
                                        data-harga="{{ $item->harga }}">
                                    Keranjang
                                </button>
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

<!-- Modal Pilih Ukuran & Jumlah -->
<div class="modal fade" id="modalUkuranJumlah" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark">Pilih Ukuran & Jumlah</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body d-flex flex-column flex-md-row align-items-start">
        <img id="previewBeliGambar" src="" class="img-fluid me-md-4 mb-3 mb-md-0" style="max-width: 250px;">
        <div class="flex-grow-1 text-dark">
          <h5 id="previewBeliNama"></h5>
          <div class="mb-3">
            <label class="form-label">Ukuran</label>
            <select id="pilihUkuran" class="form-select">
              <option>S</option>
              <option>M</option>
              <option>L</option>
              <option>XL</option>
              <option>All Size</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlahProduk" min="1" value="1">
          </div>
          <button class="btn btn-primary w-100" id="lanjutIsiData">Lanjut</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Keranjang -->
<div class="modal fade" id="modalKeranjang" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah ke Keranjang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column flex-md-row align-items-start">
                <img id="previewImage" src="" class="img-fluid me-md-4 mb-3 mb-md-0" style="max-width: 250px;">
                <div class="flex-grow-1">
                    <h5 id="previewNama" class="text-dark fw-bold"></h5>
                    <p id="previewDeskripsi" class="text-muted small"></p>
                    <div class="mb-3">
                        <label for="inputUkuran" class="form-label">Ukuran</label>
                        <select id="inputUkuran" class="form-select">
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                            <option>All Size</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="inputJumlah" class="form-label">Jumlah</label>
                        <input type="number" id="inputJumlah" class="form-control" min="1" value="1">
                    </div>
                    <button class="btn btn-primary" id="confirmTambahKeranjang">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Checkout Data Diri -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="checkoutModalLabel">Isi Data Diri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-dark">
        <form id="checkoutForm">
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="tel" class="form-control" name="telepon" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select class="form-select" name="metode_pembayaran" required>
              <option value="BCA">Virtual Account BCA</option>
              <option value="MANDIRI">Virtual Account Mandiri</option>
              <option value="BNI">Virtual Account BNI</option>
              <option value="BRI">Virtual Account BRI</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100">Beli Sekarang</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="resultAlert" class="result-alert" style="display: none;">
    <h5 class="text-success">Transaksi berhasil!</h5>
    <p>VA: <span id="vaNumber"></span></p>
    <p>Expired: <span id="expiredAt"></span></p>
    <button class="btn btn-sm btn-primary mt-2" onclick="window.location.reload()">OK</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let selectedProduct = null;

document.querySelectorAll('.btn-beli').forEach(btn => {
    btn.addEventListener('click', () => {
        selectedProduct = {
            nama: btn.dataset.nama,
            harga: parseInt(btn.dataset.harga),
            gambar: btn.dataset.gambar
        };

        // Tampilkan ke modal
        document.getElementById('previewBeliGambar').src = selectedProduct.gambar;
        document.getElementById('previewBeliNama').innerText = selectedProduct.nama;

        const modalUkuran = new bootstrap.Modal(document.getElementById('modalUkuranJumlah'));
        modalUkuran.show();
    });
});

document.getElementById('lanjutIsiData').addEventListener('click', () => {
    selectedProduct.ukuran = document.getElementById('pilihUkuran').value;
    selectedProduct.jumlah = parseInt(document.getElementById('jumlahProduk').value);

    const modalUkuran = bootstrap.Modal.getInstance(document.getElementById('modalUkuranJumlah'));
    modalUkuran.hide();

    const modalCheckout = new bootstrap.Modal(document.getElementById('checkoutModal'));
    modalCheckout.show();
});

document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => data[key] = value);

    data.items = [selectedProduct];
    data.total = selectedProduct.harga * selectedProduct.jumlah;

    fetch("/transaksi", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        document.getElementById('vaNumber').innerText = result.va_number;
        document.getElementById('expiredAt').innerText = result.expired_at;
        document.getElementById('resultAlert').style.display = 'block';
        fetch("http://localhost:8000/tes-email")
                .then(() => console.log("Tes email dipanggil"));

            const alertBox = document.getElementById('resultAlert');
            alertBox.innerHTML = `
                <h5 class="text-success">Transaksi berhasil!</h5>
                <p>VA: ${result.va_number}</p>
                <p>Expired: ${result.expired_at}</p>
                <button class="btn btn-sm btn-primary mt-2" onclick="window.location.href='/produk'">OK</button>
            `;
            alertBox.style.display = 'block';
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat transaksi.");
    });
});
</script>

<script>
    const keranjangButtons = document.querySelectorAll('.btn-keranjang');
    const modal = new bootstrap.Modal(document.getElementById('modalKeranjang'));
    let currentProduct = {};

    keranjangButtons.forEach(button => {
        button.addEventListener('click', function () {
            currentProduct = {
                id: this.dataset.id,
                nama: this.dataset.nama,
                kategori: this.dataset.kategori,
                deskripsi: this.dataset.deskripsi,
                gambar: this.dataset.gambar,
                harga: parseInt(this.dataset.harga)
            };

            document.getElementById('previewImage').src = currentProduct.gambar;
            document.getElementById('previewNama').innerText = currentProduct.nama;
            document.getElementById('previewDeskripsi').innerText = currentProduct.deskripsi || '-';
            document.getElementById('inputUkuran').value = 'All Size';
            document.getElementById('inputJumlah').value = 1;

            modal.show();
        });
    });

    document.getElementById('confirmTambahKeranjang').addEventListener('click', function () {
        const ukuran = document.getElementById('inputUkuran').value;
        const jumlah = parseInt(document.getElementById('inputJumlah').value);

        let keranjang = JSON.parse(localStorage.getItem('keranjang')) || [];
        const existing = keranjang.find(p => p.id === currentProduct.id && p.ukuran === ukuran);

        if (existing) {
            existing.jumlah += jumlah;
        } else {
            keranjang.push({ ...currentProduct, ukuran, jumlah });
        }

        localStorage.setItem('keranjang', JSON.stringify(keranjang));
        modal.hide();
        const toast = new bootstrap.Toast(document.getElementById('toastSuccess'));
        toast.show();
    });
</script>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body">
                Produk berhasil ditambahkan ke keranjang!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
</body>
</html>