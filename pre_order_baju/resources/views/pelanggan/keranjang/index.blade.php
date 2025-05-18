<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            animation: fadeInBody 0.8s ease forwards;
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
            animation: slideInUp 0.5s ease;
        }

        @keyframes slideInUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
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
            animation: fadeInDown 0.7s ease;
        }

        @keyframes fadeInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .back-button:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-50%) scale(1.05);
        }

        .main-title {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin: 0;
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
    <button class="btn btn-success">Beli</button>
</div>

<script>
    const keranjangList = document.getElementById('keranjangList');
    const totalHarga = document.getElementById('totalHarga');

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
            total += subtotal;

            const el = document.createElement('div');
            el.className = 'produk-card text-dark';
            el.innerHTML = `
                <input type="checkbox" class="form-check-input me-2 item-checkbox" checked data-index="${index}" data-subtotal="${subtotal}">
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

        totalHarga.textContent = 'Rp' + total.toLocaleString('id-ID');

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
                let sum = 0;
                document.querySelectorAll('.item-checkbox:checked').forEach(c => {
                    sum += parseInt(c.getAttribute('data-subtotal'));
                });
                totalHarga.textContent = 'Rp' + sum.toLocaleString('id-ID');
            });
        });
    }

    document.addEventListener('DOMContentLoaded', renderKeranjang);
</script>
</body>
</html>