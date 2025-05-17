<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Bellybee</title>
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
            z-index: 1;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* gelap 50% */
            z-index: -1;
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

        .main-title {
            font-size: 3.5rem;
        }
        .sub-title {
            font-size: 1.3rem;
        }

    </style>
</head>
<body>
    <h1 class="main-title mb-3">Welcome to <span class="fw-bold">Bellybee</span></h1>
    <p class="sub-title mb-4">Please select one of the options below:</p>
    <div>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-light">Our Products</a>
        <a href="{{ route('custom.index') }}" class="btn btn-outline-light">Custom Design</a>
    </div>
</body>
</html>