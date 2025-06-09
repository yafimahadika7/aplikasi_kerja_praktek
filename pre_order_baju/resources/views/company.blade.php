<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Bellybee - Fashion Elegan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;500&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 0.3rem 1rem;
        }

        .navbar-brand {
            font-family: 'Great Vibes', cursive;
            font-size: 1.8rem;
            margin-left: -50px;
        }

        .navbar-nav .nav-link {
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.4rem 1rem;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #8e44ad;
            transform: translateY(-2px);
        }

        .navbar-nav {
            margin-left: auto;
            gap: 1.5rem;
            display: flex;
            align-items: center;
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            text-align: center;
            z-index: 10;
        }

        .hero-text h1 {
            font-size: 4rem;
            font-family: 'Great Vibes', cursive;
            font-weight: bold;
        }

        .hero-text p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .carousel-item img {
            height: 100vh;
            object-fit: cover;
            filter: brightness(70%);
        }

        #tentang {
            font-family: 'Quicksand', sans-serif;
        }

        .tentang-card {
            background-color: #fff1f5;
            border-radius: 18px;
            padding: 2.5rem 2rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
            border-left: 6px solid #e47ea1;
            text-align: justify;
            color: #5a4a4a;
            font-size: 1.06rem;
            line-height: 1.85;
            transition: all 0.4s ease;
        }

        .tentang-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.07);
        }

        .tentang-card h2 {
            font-family: 'Playfair Display', serif;
            color: #7b2c52;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
        }

        .tentang-card p {
            font-size: 1.05rem;
            line-height: 1.8;
            font-family: 'DM Sans', sans-serif;
            color: #6e2f4e;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-text h1,
        .hero-text p,
        .hero-text a {
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
        }

        .hero-text h1 {
            animation-delay: 0.3s;
        }

        .hero-text p {
            animation-delay: 0.6s;
        }

        .hero-text a {
            animation-delay: 0.9s;
        }

        .foto-owner,
        .card-tentang {
            opacity: 0;
            transition: all 0.8s ease;
        }

        .foto-owner {
            transform: translateX(-50px);
        }

        .card-tentang {
            transform: translateX(50px);
        }

        .show-animate.foto-owner {
            opacity: 1;
            transform: translateX(0);
        }

        .show-animate.card-tentang {
            opacity: 1;
            transform: translateX(0);
        }

        #layanan {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffafc;
        }

        .layanan-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
            height: 100%;
        }

        .layanan-card:hover {
            transform: translateY(-5px);
        }

        .layanan-card i {
            font-size: 2rem;
            color: #c44569;
            margin-bottom: 1rem;
        }

        .layanan-card h5 {
            font-weight: 600;
            color: #b33771;
            font-size: 1.2rem;
        }

        .layanan-card p {
            font-size: 0.95rem;
            color: #555;
            margin-top: 0.5rem;
        }

        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-up.show-animate {
            opacity: 1;
            transform: translateY(0);
        }

        .gallery-item img {
            transition: transform 0.5s ease;
            border-radius: 8px;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 767.98px) {
            .hero-text h1 {
                font-size: 2.5rem;
            }

            .hero-text p {
                font-size: 1rem;
            }

            .tentang-card {
                padding: 1.5rem 1rem;
            }

            .layanan-card {
                padding: 1.2rem;
            }

            .layanan-card h5 {
                font-size: 1rem;
            }

            .layanan-card p {
                font-size: 0.9rem;
            }

            .navbar-brand {
                margin-left: 0 !important;
                padding-left: 0.75rem;
                font-size: 1.6rem;
            }
        }

        @media (max-width: 575.98px) {
            .gallery-item img {
                width: 100%;
                height: auto;
            }

            .ratio iframe {
                height: 250px !important;
            }
        }
    </style>
</head>

<body>

    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"
                style="font-family: 'Great Vibes', cursive; font-size: 1.8rem;">Bellybee</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#hero">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#galeri">Galeri</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Carousel -->
    <section id="hero">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/images/1.jpeg" class="d-block w-100" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="/images/2.jpeg" class="d-block w-100" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="/images/3.jpeg" class="d-block w-100" alt="Slide 3">
                </div>
                <div class="carousel-item">
                    <img src="/images/4.jpeg" class="d-block w-100" alt="Slide 4">
                </div>
            </div>
            <div class="hero-text">
                <h1>Bellybee</h1>
                <p>Elegan & Percaya Diri dalam Balutan Fashion</p>
                <a href="#tentang" class="btn btn-light btn-lg">Jelajahi Lebih Lanjut</a>
            </div>
        </div>
    </section>

    <!-- ✅ Tentang -->
    <section id="tentang" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <!-- Foto Owner -->
                <div class="col-md-5 mb-4 mb-md-0 text-center">
                    <img src="/images/5.jpeg" alt="Owner Bellybee" class="img-fluid rounded shadow foto-owner"
                        style="max-height: 400px; width: auto;">
                </div>
                <!-- Card Deskripsi -->
                <div class="col-md-7 ms-md-n2">
                    <div class="tentang-card card-tentang">
                        <h2>Tentang Bellybee</h2>
                        <p>
                            Bellybee adalah butik fashion yang telah berdiri selama <strong>lebih dari 12
                                tahun</strong>. Kami hadir untuk mendampingi wanita tampil anggun dan percaya diri dalam
                            setiap momen.
                        </p>
                        <p>
                            Dengan pengalaman panjang, kami menghadirkan koleksi yang elegan, nyaman, dan dibuat dengan
                            ketulusan hati.
                        </p>
                        <p>
                            Dipimpin oleh founder yang peduli akan keindahan, Bellybee bukan hanya menjual pakaian—tapi
                            juga menghadirkan rasa bangga saat dikenakan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section id="layanan" class="py-5 text-dark" style="background: url('/images/GB-02.png') center/cover no-repeat;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="color: #000;">Layanan Bellybee</h2>
                <p class="text-muted">Pelayanan terbaik kami hadirkan untuk kenyamanan dan kepuasan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="layanan-card">
                        <i class="bi bi-scissors"></i>
                        <h5>Custom Order Premium</h5>
                        <p>Pesan pakaian sesuai selera dan ukuran Anda, langsung konsultasi dengan desainer kami.</p>
                        <a href="/welcome" class="btn btn-outline-danger btn-sm mt-3">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="layanan-card">
                        <i class="bi bi-person-lines-fill"></i>
                        <h5>Styling Konsultasi</h5>
                        <p>Dapatkan saran fashion profesional untuk setiap acara penting Anda.</p>
                        <a href="/welcome" class="btn btn-outline-danger btn-sm mt-3">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="layanan-card">
                        <i class="bi bi-truck"></i>
                        <h5>Pengiriman Ekspres</h5>
                        <p>Pengiriman cepat dan aman ke seluruh Indonesia dengan packing eksklusif.</p>
                        <a href="/welcome" class="btn btn-outline-danger btn-sm mt-3">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="layanan-card">
                        <i class="bi bi-bag-check"></i>
                        <h5>Try Before You Buy</h5>
                        <p>Coba dulu baru beli untuk pelanggan Jabodetabek, langsung dari rumah Anda.</p>
                        <a href="/welcome" class="btn btn-outline-danger btn-sm mt-3">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="layanan-card">
                        <i class="bi bi-arrow-repeat"></i>
                        <h5>Retur & Tukar Ukuran</h5>
                        <p>Jika kurang pas, Anda bisa tukar ukuran atau kembalikan dalam 7 hari.</p>
                        <a href="/welcome" class="btn btn-outline-danger btn-sm mt-3">Pesan Sekarang</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-up">
                    <div class="layanan-card">
                        <i class="bi bi-tools"></i>
                        <h5>After-Sales Service</h5>
                        <p>Perbaikan ringan gratis seperti resleting atau kancing, tanpa biaya tambahan.</p>
                        <a href="/welcome" class="btn btn-outline-danger btn-sm mt-3">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galeri -->
    <section id="galeri" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 600;">Galeri Bellybee</h2>
                <p class="text-muted">Cuplikan momen spesial bersama Bellybee selama perjalanan kami</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="gallery-item rounded overflow-hidden shadow-sm">
                        <img src="/images/1 (3).jpeg" alt="Event 3" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item rounded overflow-hidden shadow-sm">
                        <img src="/images/1 (4).jpeg" alt="Event 3" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item rounded overflow-hidden shadow-sm">
                        <img src="/images/1 (5).jpeg" alt="Event 3" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item rounded overflow-hidden shadow-sm">
                        <img src="/images/1 (6).jpeg" alt="Event 3" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item rounded overflow-hidden shadow-sm">
                        <img src="/images/1 (7).jpeg" alt="Event 3" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="gallery-item rounded overflow-hidden shadow-sm">
                        <img src="/images/1 (1).jpeg" alt="Event 1" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalGaleri" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <img id="modalGaleriImg" src="" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>

    <!-- Kontak -->
    <section id="kontak" class="py-5 bg-white" style="background: url('/images/GB-02.png') center/cover no-repeat;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 style="font-family: 'Poppins', sans-serif; font-weight: 600;">Kontak Kami</h2>
                <p class="text-muted">Hubungi atau kunjungi kami langsung</p>
            </div>

            <div class="row g-4">
                <!-- Kiri: Informasi dan Sosial Media -->
                <div class="col-md-6">
                    <div class="p-4 rounded shadow-sm h-100" style="background-color: #fdf6f0;">
                        <h5 class="mb-3" style="font-weight: 600;">Alamat & Kontak</h5>
                        <p class="mb-2 text-muted">📍 Jl. Bulak Bar. No.108, RT.4/RW.1, Sawah Lama, Kec. Ciputat, Kota
                            Tangerang Selatan, Banten 15415</p>
                        <p class="mb-2 text-muted">📞 0822-9012-1096</p>
                        <p class="mb-4 text-muted">✉️ bellybee79@yahoo.co.id</p>

                        <h6 class="mb-2" style="font-weight: 600;">Sosial Media</h6>
                        <div class="d-flex gap-3 fs-4">
                            <a href="https://wa.me/6282290121096" target="_blank" class="text-success">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="https://instagram.com/bellybeeofficial" target="_blank" class="text-danger">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@bellybeecatalog?_t=ZS-8wpy7ZapXpX&_r=1" target="_blank"
                                class="text-dark">
                                <i class="bi bi-tiktok"></i>
                            </a>
                            <a href="tel:082290121096" class="text-primary">
                                <i class="bi bi-telephone-fill"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Peta Lokasi -->
                <div class="col-md-6">
                    <div class="ratio ratio-4x3 rounded overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15862.743882117133!2d106.7352424!3d-6.3049264!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69effcd34d8a09%3A0x8d417975924c4016!2sHouse%20Of%20Belly%20Bee!5e0!3m2!1sen!2sid!4v1748766247419!5m2!1sen!2sid"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            if (!location.hash) {
                location.hash = "#hero";
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show-animate');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.3
            });

            document.querySelectorAll('.foto-owner, .card-tentang').forEach(el => {
                observer.observe(el);
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show-animate');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.2
            });

            document.querySelectorAll('.fade-up').forEach(el => {
                observer.observe(el);
            });
        });

        document.querySelectorAll('.gallery-item img').forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function () {
                document.getElementById('modalGaleriImg').src = this.src;
                new bootstrap.Modal(document.getElementById('modalGaleri')).show();
            });
        });
    </script>

</body>

</html>