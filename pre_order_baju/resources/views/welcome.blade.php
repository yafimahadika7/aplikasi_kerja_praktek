<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Bellybee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            position: relative;
            background-image: url('/images/BG-01.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .main-title {
            font-size: 3.5rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
        }

        .sub-title {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
            animation-delay: 0.3s;
        }

        .btn-group {
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
            animation-delay: 0.6s;
        }

        .btn-outline-light {
            margin: 0 10px;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            border: 2px solid white;
            color: white;
            background-color: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
            transform: scale(1.05);
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

        @media (max-width: 768px) {
            .main-title {
                font-size: 2.2rem;
            }

            .sub-title {
                font-size: 1rem;
            }

            .btn-outline-light {
                display: block;
                width: 80%;
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
    <h1 class="main-title">Welcome to <span class="fw-bold">Bellybee</span></h1>
    <p class="sub-title">Please select one of the options below:</p>
    <div class="btn-group">
        <a href="{{ route('produk.index') }}" class="btn btn-outline-light">Our Products</a>
        <a href="{{ route('custom.index') }}" class="btn btn-outline-light">Custom Design</a>
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
                <button class="btn btn-outline-secondary" onclick="pilihTopik('Barang rusak', event)">Barang rusak</button>
                <button class="btn btn-outline-secondary" onclick="pilihTopik('Paket belum sampai', event)">Paket belum sampai</button>
                <button class="btn btn-outline-secondary" onclick="pilihTopik('Barang yang dikirim salah', event)">Barang yang dikirim salah</button>
            </div>

            <button id="btnKirimKomplain" class="btn btn-primary w-100" onclick="kirimKomplain()" disabled>Kirim Komplain</button>
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
        document.addEventListener('DOMContentLoaded', () => {
            // ========== VARIABEL GLOBAL ==========
            let selectedProduct = null;
            let currentProduct = {};
            let komplainId = localStorage.getItem('komplainId') || null;
            let intervalId = null;
            let topikKomplain = null;
            let isMinimized = localStorage.getItem('chatMinimized') === 'true';

            // ========== CHAT KOMPLAIN ==========
            document.getElementById('bubbleChat').addEventListener('click', () => {
                if (komplainId) {
                    toggleMinimizeChat(); // lanjutkan chat yang sudah ada
                } else {
                    topikKomplain = null;
                    new bootstrap.Modal(document.getElementById('chatRequestModal')).show(); // kirim komplain baru
                }
            });

            window.pilihTopik = function(topik, event) {
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
</body>

</html>
