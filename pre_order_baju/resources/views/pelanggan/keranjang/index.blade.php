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
        
    </style>
</head>
<body>
<div class="position-fixed top-0 start-0 p-3 z-1">
    <a href="{{ route('produk.index') }}" class="btn btn-light shadow-sm">
        &larr; Kembali
    </a>
</div>
<div class="container">
    <h3 class="mb-4">Keranjang Belanja</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->count())
        <form id="cartForm">
            @foreach($items as $item)
                <div class="produk-card text-dark">
                    <input type="checkbox" class="form-check-input me-2 item-checkbox" data-id="{{ $item->id }}" data-price="{{ $item->produk->harga }}" data-qty="{{ $item->jumlah }}">
                    <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama }}" class="produk-img">
                    <div class="produk-info">
                        <strong>{{ $item->produk->nama }}</strong><br>
                        Ukuran: {{ $item->ukuran }}<br>
                        Harga: Rp{{ number_format($item->produk->harga, 0, ',', '.') }}
                    </div>
                    <div class="input-group input-group-sm mx-3">
                        <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
                        <input type="text" class="form-control text-center qty-input" value="{{ $item->jumlah }}" data-id="{{ $item->id }}" readonly>
                        <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger btn-hapus" data-url="{{ route('keranjang.destroy', $item->id) }}">
                        Hapus
                    </button>
                </div>
            @endforeach
        </form>
    @else
        <p class="text-muted">Keranjang Anda kosong.</p>
    @endif
</div>

<div class="total-bar text-dark">
    <div>Total: <span id="totalHarga">Rp0</span></div>
    <button class="btn btn-success">Beli</button>
</div>

<!-- Form Hapus Global -->
<form id="formHapus" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const totalHarga = document.getElementById('totalHarga');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', hitungTotal);
    });

    function hitungTotal() {
        let total = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const harga = parseInt(cb.dataset.price);
                const qty = parseInt(cb.dataset.qty);
                total += harga * qty;
            }
        });
        totalHarga.textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    // Tombol hapus logic
    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function () {
            if (confirm('Yakin hapus item ini?')) {
                const form = document.getElementById('formHapus');
                form.setAttribute('action', this.getAttribute('data-url'));
                form.submit();
            }
        });
    });
</script>

<script>
    // Sembunyikan alert dalam 3 detik
    const alertEl = document.querySelector('.alert');
    if (alertEl) {
        setTimeout(() => {
            alertEl.style.transition = 'opacity 0.5s ease';
            alertEl.style.opacity = '0';
            setTimeout(() => alertEl.remove(), 500); // hapus dari DOM setelah fade out
        }, 3000);
    }
</script>
</body>
</html>