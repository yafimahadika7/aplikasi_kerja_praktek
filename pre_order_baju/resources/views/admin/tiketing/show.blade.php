<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Tiket Komplain</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-container {
            padding: 1rem;
            background: #f8f9fa;
            min-height: 300px;
            max-height: 500px;
            overflow-y: auto;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .bubble-row {
            display: flex;
            width: 100%;
        }
        .bubble {
            max-width: 65%;
            padding: 10px 16px;
            border-radius: 18px;
            font-size: 15px;
            line-height: 1.4;
            word-break: break-word;
            margin-bottom: 2px;
        }
        .bubble-user {
            background: #e4e6eb;
            color: #222;
            text-align: left;
            border-bottom-left-radius: 4px;
            border-top-left-radius: 4px;
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
            margin-right: auto;
        }
        .bubble-admin {
            background: #007bff;
            color: #fff;
            text-align: right;
            border-bottom-right-radius: 4px;
            border-top-right-radius: 4px;
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
            margin-left: auto;
        }
        .chat-meta {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
        }
        .bubble-username {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 2px;
            display: block;
        }
        @media (max-width: 600px) {
            .chat-container { padding: 0.5rem; }
            .bubble { max-width: 85%; font-size: 14px;}
        }
    </style>
</head>
<body>
<div class="container my-4">
    <a href="{{ route('admin.tiketing.index') }}" class="btn btn-secondary mb-3">← Kembali</a>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Detail Tiket #{{ $komplain->id }}</h5>
            <p><b>Nama:</b> {{ $komplain->nama }}</p>
            <p><b>Kontak:</b> {{ $komplain->kontak }}</p>
            <p><b>Topik:</b> {{ $komplain->topik }}</p>
            <p><b>Status:</b>
                <span class="badge bg-{{ $komplain->status == 'closed' ? 'secondary' : 'success' }}">
                    {{ ucfirst($komplain->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><b>Riwayat Chat</b></div>
        <div class="card-body">
            <div class="chat-container mb-4" id="chatBox">
                @forelse ($messages as $msg)
                    @if ($msg->pengirim == 'admin')
                        <div class="bubble-row justify-content-end">
                            <div class="bubble bubble-admin">
                                <span class="bubble-username">Admin</span>
                                {{ $msg->pesan }}
                                <div class="chat-meta">{{ $msg->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    @else
                        <div class="bubble-row justify-content-start">
                            <div class="bubble bubble-user">
                                <span class="bubble-username">{{ $komplain->nama ?? 'Pelanggan' }}</span>
                                {{ $msg->pesan }}
                                <div class="chat-meta">{{ $msg->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-muted text-center">Belum ada pesan.</p>
                @endforelse
            </div>
        </div>
    </div>

    @if($komplain->status != 'closed')
    <form action="{{ route('admin.tiketing.reply', $komplain->id) }}" method="POST" class="d-flex gap-2">
        @csrf
        <input type="text" name="pesan" class="form-control" placeholder="Tulis balasan..." required>
        <button type="submit" class="btn btn-primary">Balas</button>
    </form>
    <form action="{{ route('admin.tiketing.close', $komplain->id) }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tutup tiket ini?')">Tutup Tiket</button>
    </form>
    @else
    <div class="alert alert-secondary text-center">Tiket sudah ditutup</div>
    @endif
</div>
</body>
</html>