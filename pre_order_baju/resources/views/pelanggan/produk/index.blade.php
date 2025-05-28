<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Our Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            to {
                opacity: 1;
            }
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
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

        .btn-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            border-radius: 50%;
            transition: background 0.2s ease;
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.15);
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
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            border-radius: 8px;
            padding: 0;
            border: none;
            width: 180px;
            margin: auto;
        }

        /* .product-card img {
            width: 100%;
            height: 240px;
            object-fit: contain;
            border-radius: 8px 8px 0 0;
        } */

        .card-body {
            min-height: 160px;
            /* agar tinggi konten sama */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-title {
            min-height: 48px;
            /* sesuai tinggi 2 baris */
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .btn-group-modern {
            margin-top: auto;
            display: flex;
            gap: 6px;
            justify-content: center;
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-modern:hover {
            background-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(220, 53, 69, 0.4);
        }

        .product-card img {
            width: 100%;
            height: 220px;
            /* pastikan semua gambar sama tinggi */
            object-fit: cover;
            /* crop agar proporsional */
            border-radius: 8px 8px 0 0;
        }

        .text-justify {
            text-align: justify;
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
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            z-index: 1100;
            text-align: center;
        }

        .chat-bubble {
            max-width: 70%;
            padding: 8px 14px;
            margin-bottom: 10px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
            display: inline-block;
        }

        .chat-left {
            background-color: #e4e6eb;
            color: #000;
            align-self: flex-start;
            text-align: left;
            border-bottom-left-radius: 0;
        }

        .chat-right {
            background-color: #007bff;
            color: #fff;
            align-self: flex-end;
            text-align: right;
            margin-left: auto;
            border-bottom-right-radius: 0;
        }
    </style>
    </style>
</head>

<body>

    <div class="topbar">
        <!-- Brand -->
        <a href="{{ url('/') }}" class="brand text-white text-decoration-none d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> <span class="fw-bold">bellybee</span>
        </a>

        <!-- Search Form -->
        <form method="GET" action="{{ route('produk.index') }}" class="d-flex"
            style="flex-grow:1; max-width: 500px; margin: 0 1rem;">
            <input type="text" name="search" class="form-control me-2" placeholder="Search product, trend, or brand..."
                value="{{ request('search') }}">
            <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
        </form>

        <!-- Ikon Aksi Kanan: Tracking & Keranjang -->
        <div class="d-flex align-items-center gap-2">
            <!-- Tombol Tracking -->
            <button class="btn btn-icon text-white p-0" data-bs-toggle="modal" data-bs-target="#modalTracking"
                title="Lacak Resi">
                <i class="bi bi-truck fs-5"></i>
            </button>

            <!-- Link Keranjang -->
            <a href="/keranjang" class="text-white text-decoration-none btn btn-icon p-0" title="Keranjang">
                <i class="bi bi-bag fs-5"></i>
            </a>
        </div>
    </div>

    <!-- Modal 1: Input Nomor Resi -->
    <div class="modal fade" id="modalTracking" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">🔍 Lacak Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="inputResi" class="form-label">Masukkan Nomor Resi JNE</label>
                    <input type="text" id="inputResi" class="form-control" placeholder="Contoh: 010111234567890">
                    <button id="btnLacak" class="btn btn-primary w-100 mt-3">Lacak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Detail Tracking -->
    <div class="modal fade" id="modalTrackingDetail" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">📦 Detail Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <p><strong>Status:</strong> <span id="trackingStatus"></span></p>
                    <p><strong>No. Resi:</strong> <span id="trackingResi"></span></p>

                    <h6 class="mt-4">Riwayat Pengiriman:</h6>
                    <ul id="trackingTimeline" class="list-group small"></ul>

                    <button class="btn btn-success mt-4 w-100" id="btnPesananDiterima">
                        ✅ Pesanan Diterima
                    </button>

                </div>
            </div>
        </div>
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
                                    <button class="btn-modern btn-beli" data-nama="{{ $item->nama }}"
                                        data-harga="{{ $item->harga }}" data-gambar="{{ asset('storage/' . $item->gambar) }}"
                                        data-deskripsi="{{ $item->deskripsi }}" data-ukuran="All Size">
                                        Beli
                                    </button>
                                    <button class="btn-modern btn-keranjang" data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama }}" data-kategori="{{ $item->kategori }}"
                                        data-deskripsi="{{ $item->deskripsi }}"
                                        data-gambar="{{ asset('storage/' . $item->gambar) }}" data-harga="{{ $item->harga }}">
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
                        <p id="previewBeliDeskripsi" class="text-muted small text-justify"></p>
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

    <!-- Modal Request Komplain -->
    <div class="modal fade" id="chatRequestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">🖐️ Kirim Komplain</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="namaPelanggan" class="form-control mb-2" placeholder="Nama Anda">
                    <input type="text" id="kontakPelanggan" class="form-control mb-2" placeholder="Email atau Nomor HP">

                    <h6 class="fw-bold">Pilih Topik Komplain</h6>
                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-outline-secondary" onclick="pilihTopik('Barang rusak', event)">Barang
                            rusak</button>
                        <button class="btn btn-outline-secondary"
                            onclick="pilihTopik('Paket belum sampai', event)">Paket belum sampai</button>
                        <button class="btn btn-outline-secondary"
                            onclick="pilihTopik('Barang yang dikirim salah', event)">Barang yang dikirim salah</button>
                    </div>

                    <button id="btnKirimKomplain" class="btn btn-primary w-100" onclick="kirimKomplain()" disabled>Kirim
                        Komplain</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chat Aktif -->
    <div class="modal fade" id="chatModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-dark">
                <div class="modal-header">
                    <h5 class="modal-title">💬 Chat Komplain</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body d-flex flex-column" id="chatModalBody" style="height: 400px;">
                    <div id="chatContainer" class="flex-grow-1 d-flex flex-column mb-3" style="overflow-y: auto;"></div>
                    <div class="input-group">
                        <input type="text" id="inputPesanChat" class="form-control" placeholder="Tulis pesan...">
                        <button class="btn btn-primary" onclick="kirimPesanChat()">Kirim</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bubble Chat Minimized -->
    <button id="bubbleChat" class="btn btn-primary rounded-circle shadow position-fixed"
        style="bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 9999;">
        💬
        <span id="notifCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="font-size: 0.7rem; display: none;">
            0
        </span>
    </button>

    <!-- Modal Tambah Keranjang -->
    <div class="modal fade" id="modalKeranjang" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark">Pilih Ukuran & Jumlah</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column flex-md-row align-items-start">
                    <img id="previewImage" src="" class="img-fluid me-md-4 mb-3 mb-md-0" style="max-width: 250px;">
                    <div class="flex-grow-1">
                        <h5 id="previewNama" class="text-dark fw-bold"></h5>
                        <p id="previewDeskripsi" class="text-muted small text-justify"></p>
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
        document.addEventListener('DOMContentLoaded', () => {
            // ========== VARIABEL GLOBAL ==========
            let selectedProduct = null;
            let currentProduct = {};
            let komplainId = localStorage.getItem('komplainId') || null;
            let intervalId = null;
            let topikKomplain = null;
            let isMinimized = localStorage.getItem('chatMinimized') === 'true';

            // ========== PRODUK ==========
            document.querySelectorAll('.btn-beli').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedProduct = {
                        nama: btn.dataset.nama,
                        harga: parseInt(btn.dataset.harga),
                        gambar: btn.dataset.gambar,
                        deskripsi: btn.dataset.deskripsi
                    };

                    document.getElementById('previewBeliGambar').src = selectedProduct.gambar;
                    document.getElementById('previewBeliNama').innerText = selectedProduct.nama;
                    document.getElementById('previewBeliDeskripsi').innerText = selectedProduct.deskripsi || '-';

                    new bootstrap.Modal(document.getElementById('modalUkuranJumlah')).show();
                });
            });

            document.getElementById('lanjutIsiData').addEventListener('click', () => {
                selectedProduct.ukuran = document.getElementById('pilihUkuran').value;
                selectedProduct.jumlah = parseInt(document.getElementById('jumlahProduk').value);

                bootstrap.Modal.getInstance(document.getElementById('modalUkuranJumlah')).hide();
                new bootstrap.Modal(document.getElementById('checkoutModal')).show();
            });

            document.getElementById('checkoutForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = {};
                formData.forEach((value, key) => data[key] = value);

                data.items = [{
                    nama_produk: selectedProduct.nama,
                    ukuran: selectedProduct.ukuran,
                    jumlah: selectedProduct.jumlah,
                    harga: selectedProduct.harga
                }];
                data.total = selectedProduct.harga * selectedProduct.jumlah;

                fetch("/transaksi", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                    .then(response => response.json())
                    .then(result => {
                        const alertBox = document.getElementById('resultAlert');
                        alertBox.innerHTML = `
                <h5 class="text-success">Transaksi berhasil!</h5>
                <p>VA: ${result.va_number}</p>
                <p>Expired: ${result.expired_at}</p>
                <button class="btn btn-sm btn-primary mt-2" onclick="window.location.href='/produk'">OK</button>
            `;
                        alertBox.style.display = 'block';
                        fetch("/tes-email");
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan saat transaksi.");
                    });
            });

            // ========== KERANJANG ==========
            const keranjangButtons = document.querySelectorAll('.btn-keranjang');
            const modalKeranjang = new bootstrap.Modal(document.getElementById('modalKeranjang'));

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

                    modalKeranjang.show();
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
                modalKeranjang.hide();
                new bootstrap.Toast(document.getElementById('toastSuccess')).show();
            });

            // ========== CHAT KOMPLAIN ==========

            document.getElementById('bubbleChat').addEventListener('click', () => {
                if (komplainId) {
                    toggleMinimizeChat(); // lanjutkan chat yang sudah ada
                } else {
                    topikKomplain = null;
                    new bootstrap.Modal(document.getElementById('chatRequestModal')).show(); // kirim komplain baru
                }
            });

            window.pilihTopik = function (topik, event) {
                topikKomplain = topik;
                document.querySelectorAll('.btn-outline-secondary').forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
                document.getElementById('btnKirimKomplain').disabled = false;
            };

            window.kirimKomplain = function () {
                const nama = document.getElementById('namaPelanggan').value.trim();
                const kontak = document.getElementById('kontakPelanggan').value.trim();

                console.log('Klik Kirim Komplain');
                console.log('Nama:', nama);
                console.log('Kontak:', kontak);
                console.log('Topik:', topikKomplain);

                if (!nama || !kontak || !topikKomplain) {
                    alert("Mohon isi semua kolom dan pilih topik.");
                    return;
                }

                fetch('/komplain', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        nama,
                        kontak,
                        topik: topikKomplain
                    })
                })
                    .then(res => res.json())
                    .then(res => {
                        console.log('Respon dari server:', res);

                        if (res.komplain && res.komplain.id) {
                            komplainId = res.komplain.id;
                            localStorage.setItem('komplainId', res.komplain.id);
                            localStorage.setItem('chatMinimized', 'false');

                            bootstrap.Modal.getInstance(document.getElementById('chatRequestModal')).hide();
                            new bootstrap.Modal(document.getElementById('chatModal')).show();

                            document.getElementById('bubbleChat').style.display = 'none';
                            isMinimized = false;

                            loadChat();
                            mulaiPollingChat();
                        } else {
                            alert('Gagal memulai chat komplain.');
                        }
                    })
                    .catch(err => {
                        console.error('Gagal kirim komplain:', err);
                        alert('Terjadi kesalahan saat mengirim komplain.');
                    });
            };

            window.kirimPesanChat = function () {
                const teks = document.getElementById('inputPesanChat').value.trim();
                if (!teks || !komplainId) return;

                fetch(`/komplain/${komplainId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ pesan: teks, pengirim: 'pelanggan' })
                }).then(() => {
                    document.getElementById('inputPesanChat').value = '';
                    loadChat();
                });
            };

            function loadChat() {
                if (!komplainId) return;
                fetch(`/komplain/${komplainId}/messages`)
                    .then(res => res.json())
                    .then(data => {
                        const container = document.getElementById('chatContainer');
                        container.innerHTML = '';

                        let unreadCount = 0;

                        data.forEach(msg => {
                            const div = document.createElement('div');
                            div.className = `chat-bubble ${msg.pengirim === 'pelanggan' ? 'chat-left' : 'chat-right'}`;
                            div.textContent = msg.pesan;
                            container.appendChild(div);

                            // hitung pesan dari admin saat chat tidak dibuka
                            if (msg.pengirim === 'admin' && isMinimized) {
                                unreadCount++;
                            }
                        });

                        // tampilkan badge notifikasi
                        const notif = document.getElementById('notifCount');
                        if (unreadCount > 0 && isMinimized) {
                            notif.textContent = unreadCount;
                            notif.style.display = 'inline-block';
                        } else {
                            notif.style.display = 'none';
                        }

                        container.scrollTop = container.scrollHeight;
                    });
            }

            function mulaiPollingChat() {
                loadChat();
                if (intervalId) clearInterval(intervalId);
                intervalId = setInterval(loadChat, 3000);
            }

            const chatModalElement = document.getElementById('chatModal');
            const chatModalInstance = bootstrap.Modal.getOrCreateInstance(chatModalElement);

            function toggleMinimizeChat() {
                const bubbleChat = document.getElementById('bubbleChat');
                const notif = document.getElementById('notifCount');

                if (!isMinimized) {
                    chatModalInstance.hide();
                    bubbleChat.style.display = 'block';
                    isMinimized = true;
                    localStorage.setItem('chatMinimized', 'true');
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                } else {
                    chatModalInstance.show();
                    bubbleChat.style.display = 'none';
                    isMinimized = false;
                    localStorage.setItem('chatMinimized', 'false');
                    notif.style.display = 'none';
                }
            }

            function restoreChatState() {
                if (!komplainId) return;

                fetch(`/komplain/${komplainId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'closed') {
                            // Jika komplain sudah ditutup di DB
                            localStorage.removeItem('komplainId');
                            localStorage.removeItem('chatMinimized');
                            komplainId = null;
                            isMinimized = false;
                            document.getElementById('bubbleChat').style.display = 'none';
                        } else {
                            // Komplain masih open, lanjutkan chat
                            if (isMinimized) {
                                document.getElementById('bubbleChat').style.display = 'block';
                            } else {
                                new bootstrap.Modal(document.getElementById('chatModal')).show();
                            }
                            loadChat();
                            mulaiPollingChat();
                        }
                    });
            }

            restoreChatState();

            // ❌ Tombol close — hanya bersihkan localStorage dan hentikan polling
            document.querySelector('#chatModal .btn-close')?.addEventListener('click', () => {
                const bsModal = bootstrap.Modal.getInstance(document.getElementById('chatModal'));
                bsModal.hide();

                isMinimized = true;
                localStorage.setItem('chatMinimized', 'true');
                document.getElementById('bubbleChat').style.display = 'block';

                // Hapus backdrop agar tidak menutupi layar
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';
            });


            // 💬 Tombol minimize dan bubble
            document.getElementById('btnMinimizeChat').addEventListener('click', toggleMinimizeChat);
            document.getElementById('bubbleChat').addEventListener('click', () => {
                if (komplainId) {
                    toggleMinimizeChat(); // buka/minimize chat
                } else {
                    new bootstrap.Modal(document.getElementById('chatRequestModal')).show(); // buka form kirim komplain
                }
            });

            // Klik backdrop modal untuk minimize
            document.getElementById('chatModal').addEventListener('mousedown', function (e) {
                if (e.target === this && !isMinimized) {
                    toggleMinimizeChat();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnLacak = document.getElementById('btnLacak');
            if (!btnLacak) return;

            btnLacak.addEventListener('click', function () {
                const resi = document.getElementById('inputResi').value.trim();
                if (!resi) return alert("Nomor resi tidak boleh kosong");

                fetch("{{ route('tracking.ajax') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
                    },
                    body: JSON.stringify({ resi: resi })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status !== 200) {
                            alert(data.message || "Resi tidak ditemukan.");
                            return;
                        }

                        const result = data.data;
                        document.getElementById('trackingStatus').innerText = result.summary.status;
                        document.getElementById('trackingResi').innerText = resi;

                        const timeline = document.getElementById('trackingTimeline');
                        timeline.innerHTML = '';
                        result.history.forEach((item, index) => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';
                            const dot = index === 0 ? '🟢' : '⚪';
                            li.innerHTML = `<strong>${dot} ${item.date}</strong><br>${item.desc}`;
                            timeline.appendChild(li);
                        });

                        const modalInput = bootstrap.Modal.getInstance(document.getElementById('modalTracking'));
                        modalInput.hide();
                        new bootstrap.Modal(document.getElementById('modalTrackingDetail')).show();
                    })
                    .catch(() => {
                        alert("Terjadi kesalahan saat memproses permintaan.");
                    });
            });

            // tombol "Pesanan Diterima"
            const btnDiterima = document.getElementById('btnPesananDiterima');
            if (btnDiterima) {
                btnDiterima.addEventListener('click', function () {
                    const resi = document.getElementById('trackingResi').innerText;
                    fetch("{{ route('pesanan.diterima') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').content
                        },
                        body: JSON.stringify({ no_resi: resi })
                    })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.success ? "Pesanan dikonfirmasi." : "Gagal: " + data.message);
                            bootstrap.Modal.getInstance(document.getElementById('modalTrackingDetail')).hide();
                        })
                        .catch(() => {
                            alert("Gagal mengirim konfirmasi.");
                        });
                });
            }
        });
    </script>

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    Produk berhasil ditambahkan ke keranjang!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
</body>

</html>