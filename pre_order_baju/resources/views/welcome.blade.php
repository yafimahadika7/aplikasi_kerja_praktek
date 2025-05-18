<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Bellybee</title>
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
</body>
</html>