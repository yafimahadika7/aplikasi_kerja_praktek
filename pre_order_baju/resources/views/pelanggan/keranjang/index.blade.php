<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
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

        .produk-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
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
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
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
            background-color: rgba(0,0,0,0.5);
            z-index: 1050;
        }

        .custom-alert {
            background-color: white;
            padding: 20px 30px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1rem;
            color: black;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
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
            display: none;
        }
    </style>
</head>
<body>
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

<div id="resultAlert" class="result-alert"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const keranjangList = document.getElementById('keranjangList');
    const totalHarga = document.getElementById('totalHarga');
    const customAlert = document.getElementById('customAlert');
    const btnBeli = document.getElementById('btnBeli');

    function showAlert() {
        customAlert.classList.remove('d-none');
        setTimeout(() => {
            customAlert.classList.add('d-none');
        }, 3000);
    }

    function renderKeranjang() {
        const items = JSON.parse(localStorage.getItem('keranjang')) || [];
        keranjangList.innerHTML = '';

        if (items.length === 0) {
            keranjangList.innerHTML = '<p class="text-muted">Keranjang Anda kosong.</p>';
            totalHarga.textContent = 'Rp0';
            return;
        }

        let total = 0;

        items.forEach((item, index) => {
            const subtotal = item.harga * item.jumlah;
            const el = document.createElement('div');
            el.className = 'produk-card text-dark';
            el.innerHTML = `
                <input type="checkbox" class="form-check-input me-2 item-checkbox" checked data-index="${index}">
                <img src="${item.gambar}" alt="${item.nama}" class="produk-img">
                <div class="produk-info">
                    <strong>${item.nama}</strong><br>
                    Ukuran: ${item.ukuran}<br>
                    Harga: Rp${item.harga.toLocaleString('id-ID')}
                </div>
                <div class="input-group input-group-sm mx-3">
                    <button type="button" class="btn btn-outline-secondary btn-minus" data-index="${index}">-</button>
                    <input type="text" class="form-control text-center qty-input" value="${item.jumlah}" readonly>
                    <button type="button" class="btn btn-outline-secondary btn-plus" data-index="${index}">+</button>
                </div>
                <button class="btn btn-sm btn-danger btn-delete" data-index="${index}">Hapus</button>
            `;

            keranjangList.appendChild(el);
        });

        updateTotal();

        // Re-attach event handlers
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', e => {
                const index = parseInt(e.target.getAttribute('data-index'));
                items.splice(index, 1);
                localStorage.setItem('keranjang', JSON.stringify(items));
                renderKeranjang();
            });
        });

        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', e => {
                const index = parseInt(e.target.getAttribute('data-index'));
                items[index].jumlah++;
                localStorage.setItem('keranjang', JSON.stringify(items));
                renderKeranjang();
            });
        });

        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', e => {
                const index = parseInt(e.target.getAttribute('data-index'));
                if (items[index].jumlah > 1) {
                    items[index].jumlah--;
                    localStorage.setItem('keranjang', JSON.stringify(items));
                    renderKeranjang();
                }
            });
        });

        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                updateTotal();
            });
        });
    }

    function updateTotal() {
        const items = JSON.parse(localStorage.getItem('keranjang')) || [];
        let total = 0;
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            if (cb.checked) {
                const index = parseInt(cb.getAttribute('data-index'));
                total += items[index].harga * items[index].jumlah;
            }
        });
        totalHarga.textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', renderKeranjang);

    btnBeli.addEventListener('click', () => {
        const items = JSON.parse(localStorage.getItem('keranjang')) || [];
        if (items.length === 0) {
            showAlert();
        } else {
            const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            modal.show();
        }
    });

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => data[key] = value);

        const items = JSON.parse(localStorage.getItem('keranjang')) || [];
        const total = items.reduce((sum, item) => sum + item.harga * item.jumlah, 0);

        data.items = items;
        data.total = total;

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
            // ✅ Tambahkan request ke tes-email (jika diperlukan)
            fetch("http://localhost:8000/tes-email")
                .then(() => console.log("Tes email dipanggil"));

            const alertBox = document.getElementById('resultAlert');
            alertBox.innerHTML = `
                <h5 class="text-success">Transaksi berhasil!</h5>
                <p>VA: ${result.va_number}</p>
                <p>Expired: ${result.expired_at}</p>
                <button class="btn btn-sm btn-primary mt-2" onclick="window.location.href='/keranjang'">OK</button>
            `;
            alertBox.style.display = 'block';
            localStorage.removeItem('keranjang');
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Terjadi kesalahan saat memproses transaksi.");
        });
    });
</script>
</body>
</html>