<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
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

        .produk-card {
            background-color: rgba(255, 255, 255, 0.95);
            color: #000;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease both;
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

        .produk-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 1rem;
        }

        .produk-info {
            text-align: left;
            flex-grow: 1;
        }

        .input-group-sm {
            max-width: 120px;
        }

        .total-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #ccc;
            z-index: 100;
        }

        .header-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .back-button {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .main-title {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin: 0;
        }

        .modal-content {
            color: black;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        .alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }

        .custom-alert {
            background-color: white;
            padding: 20px 30px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1rem;
            color: black;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
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
            display: none;
        }
    </style>
</head>

<body>

    <!-- Letakkan langsung di bawah <body> -->
    <div id="resultAlert" class="result-alert">
        <div class="bg-white p-4 rounded shadow text-dark" style="max-width: 400px; margin: auto;">
            <h5 class="text-success">🎉 Transaksi Berhasil!</h5>
            <p class="mb-1">Nomor VA:</p>
            <div class="fw-bold text-primary fs-5" id="vaNumberText"></div>
            <p class="mt-2 mb-1">Berlaku hingga:</p>
            <div class="text-danger" id="vaExpiredText"></div>
            <button class="btn btn-success mt-4 w-100" onclick="tutupResultAlert()">OK</button>
        </div>
    </div>

    <div class="container">
        <div class="header-container">
            <a href="{{ url('/produk') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <h1 class="main-title">Keranjang Belanja</h1>
        </div>
        <div id="keranjangList"></div>
    </div>

    <div class="total-bar text-dark">
        <div>Total: <span id="totalHarga">Rp0</span></div>
        <button class="btn btn-success" id="btnBeli">Beli</button>
    </div>

    <!-- Modal Checkout -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Isi Data Diri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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

    <div id="customAlert" class="alert-overlay d-none">
        <div class="custom-alert">Keranjang Anda kosong. Silakan pilih produk terlebih dahulu.</div>
    </div>

    <!-- Bubble Chat Minimized -->
    <button id="bubbleChat" class="btn btn-primary rounded-circle shadow position-fixed"
        style="bottom: 100px; right: 20px; width: 60px; height: 60px; z-index: 9999;">
        💬
        <span id="notifCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="font-size: 0.7rem; display: none;">
            0
        </span>
    </button>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const keranjangList = document.getElementById('keranjangList');
        const totalHarga = document.getElementById('totalHarga');

        function renderKeranjang() {
            const items = JSON.parse(localStorage.getItem('keranjang')) || [];
            keranjangList.innerHTML = '';
            let total = 0;

            items.forEach((item, index) => {
                const card = document.createElement('div');
                card.className = 'produk-card';
                total += item.harga * item.jumlah;
                card.innerHTML = `
                    <input type="checkbox" class="form-check-input me-3 item-checkbox" data-index="${index}" checked>
                    <img src="${item.gambar}" class="produk-img">
                    <div class="produk-info">
                        <strong>${item.nama}</strong><br>
                        Ukuran: ${item.ukuran}<br>
                        Jumlah: ${item.jumlah}<br>
                        Harga: Rp${item.harga.toLocaleString('id-ID')}
                    </div>
                    <div class="d-flex align-items-center ms-auto">
                        <button class="btn btn-sm btn-outline-secondary btn-minus me-1" data-index="${index}">-</button>
                        <span class="mx-1">${item.jumlah}</span>
                        <button class="btn btn-sm btn-outline-secondary btn-plus ms-1" data-index="${index}">+</button>
                    </div>
                `;
                keranjangList.appendChild(card);
            });

            totalHarga.textContent = 'Rp' + total.toLocaleString('id-ID');
        }

        document.getElementById('btnBeli').addEventListener('click', () => {
            const items = JSON.parse(localStorage.getItem('keranjang')) || [];
            if (items.length === 0) return alert('Keranjang kosong');
            new bootstrap.Modal(document.getElementById('checkoutModal')).show();
        });

        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(this).entries());
            const items = JSON.parse(localStorage.getItem('keranjang')) || [];
            const total = items.reduce((sum, item) => sum + item.harga * item.jumlah, 0);

            data.items = items.map(item => ({
                nama_produk: item.nama,
                ukuran: item.ukuran,
                jumlah: item.jumlah,
                harga: item.harga
            }));
            data.total = total;

            fetch('/transaksi', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(async res => {
                    if (!res.ok) {
                        const text = await res.text();
                        throw new Error(`Gagal: ${res.status} - ${text}`);
                    }
                    return res.json();
                })
                .then(res => {

                    // Masukkan data ke alert
                    document.getElementById('vaNumberText').textContent = res.va_number;
                    document.getElementById('vaExpiredText').textContent = res.expired_at;

                    // Tampilkan alert
                    document.getElementById('resultAlert').style.display = 'block';
                    fetch("http://localhost:8000/tes-email")
                        .then(() => console.log("Email dikirim!"))
                        .catch(err => console.error("Gagal kirim email:", err));

                    // Hapus keranjang
                    localStorage.removeItem('keranjang');
                })
                .catch(err => {
                    alert('Gagal memproses transaksi.');
                    console.error(err);
                });
        });



        document.addEventListener('DOMContentLoaded', renderKeranjang);

        function tutupResultAlert() {
            document.getElementById('resultAlert').style.display = 'none';
            location.reload();
        }

        // ========== CHAT KOMPLAIN ==========
        document.addEventListener('DOMContentLoaded', () => {
            let selectedProduct = null;
            let currentProduct = {};
            let komplainId = localStorage.getItem('komplainId') || null;
            let intervalId = null;
            let topikKomplain = null;
            let isMinimized = localStorage.getItem('chatMinimized') === 'true';

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

                            if (msg.pengirim === 'admin' && isMinimized) {
                                unreadCount++;
                            }
                        });

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
                            localStorage.removeItem('komplainId');
                            localStorage.removeItem('chatMinimized');
                            komplainId = null;
                            isMinimized = false;
                            document.getElementById('bubbleChat').style.display = 'none';
                        } else {
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

            document.querySelector('#chatModal .btn-close')?.addEventListener('click', () => {
                const bsModal = bootstrap.Modal.getInstance(document.getElementById('chatModal'));
                bsModal.hide();

                isMinimized = true;
                localStorage.setItem('chatMinimized', 'true');
                document.getElementById('bubbleChat').style.display = 'block';

                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';
            });

            document.getElementById('bubbleChat').addEventListener('click', () => {
                if (komplainId) {
                    toggleMinimizeChat();
                } else {
                    new bootstrap.Modal(document.getElementById('chatRequestModal')).show();
                }
            });

            document.getElementById('chatModal').addEventListener('mousedown', function (e) {
                if (e.target === this && !isMinimized) {
                    toggleMinimizeChat();
                }
            });
        });
    </script>
</body>

</html>