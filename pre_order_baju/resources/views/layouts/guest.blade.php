<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: linear-gradient(to bottom, #87CEFA, #ffffff);
            background-repeat: no-repeat;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .cloud {
            background: white;
            border-radius: 50%;
            position: absolute;
            opacity: 0.4;
            z-index: 0; /* PENTING: awan di belakang */
            animation: float 40s linear infinite;
            box-shadow: 60px 20px white, 100px 40px white, 140px 20px white;
        }

        .cloud1 {
            width: 200px;
            height: 60px;
            top: 50px;
            left: -300px;
            animation-delay: 0s;
        }

        .cloud2 {
            width: 250px;
            height: 70px;
            top: 180px;
            left: -400px;
            animation-delay: 10s;
        }

        @keyframes float {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(150vw);
            }
        }

        .content {
            position: relative;
            z-index: 10; /* PENTING: konten di depan */
        }
    </style>
</head>

<body class="font-sans antialiased">

    <!-- Awan di belakang -->
    <div class="cloud cloud1"></div>
    <div class="cloud cloud2"></div>

    <!-- Konten utama -->
    <div class="content min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/">
                <div class="text-center text-6xl font-bold text-gray-700" style="font-family: 'Great Vibes', cursive;">
                    Bellybee
                </div>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>