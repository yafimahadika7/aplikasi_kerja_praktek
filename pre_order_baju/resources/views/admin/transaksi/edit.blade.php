<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Serial Number</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            padding-top: 60px;
        }
        .card {
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-header {
            background: #0d6efd;
            color: white;
        }
        .btn-success {
            width: 100px;
        }
        .btn-secondary {
            margin-left: 10px;
            width: 100px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Serial Number</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.transaksi.update', $transaksi->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="{{ $transaksi->nama }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control" value="Rp{{ number_format($transaksi->total, 0, ',', '.') }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Serial Number</label>
                            <input type="text" name="serial_number" class="form-control" value="{{ $transaksi->serial_number }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending" {{ $transaksi->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="proses" {{ $transaksi->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="sukses" {{ $transaksi->status == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                <option value="gagal" {{ $transaksi->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <a href="{{ route('admin.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('form').addEventListener('submit', function () {
        const status = document.querySelector('select[name="status"]').value;

        // if (status === 'proses') {
        //     fetch("http://localhost:8000/tes-resi")
        //         .then(() => console.log("✅ Email resi dipanggil dari tes-email"))
        //         .catch(err => console.error("❌ Gagal fetch email:", err));
        // }
    });
</script>
</body>
</html>