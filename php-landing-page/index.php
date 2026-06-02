<?php
// index.php - Landing Page for Lapor Infrastruktur
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lapor Infrastruktur - Smart City Reporting</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0056b3;
            --secondary-blue: #007bff;
            --light-blue: #e7f1ff;
            --white: #ffffff;
            --gray-100: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            scroll-behavior: smooth;
        }

        .bg-primary-blue { background-color: var(--primary-blue); }
        .text-primary-blue { color: var(--primary-blue); }
        
        .navbar {
            transition: all 0.4s ease;
            padding: 15px 0;
            border-bottom: 1px solid transparent;
        }
        
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 10px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .nav-link {
            font-weight: 600;
            position: relative;
            color: #444 !important;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-blue) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background-color: var(--primary-blue);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .btn-lang-toggle {
            background-color: var(--light-blue);
            color: var(--primary-blue);
            border: 1px solid transparent;
            padding: 8px 18px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-lang-toggle:hover {
            background-color: var(--primary-blue);
            color: var(--white);
            transform: scale(1.05);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--white) 0%, var(--light-blue) 100%);
            padding: 100px 0;
        }

        .section-padding {
            padding: 80px 0;
        }

        .card {
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 15px;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
        }

        .btn-outline-primary {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
        }

        .lang-switcher .btn {
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .step-box {
            position: relative;
            padding: 20px;
            background: var(--white);
            border-radius: 15px;
            border-left: 5px solid var(--primary-blue);
            margin-bottom: 20px;
        }

        .qr-code-img {
            max-width: 200px;
            border: 5px solid var(--white);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .tech-badge {
            background-color: var(--light-blue);
            color: var(--primary-blue);
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin: 5px;
            font-weight: 600;
        }

        footer {
            background-color: #f1f4f9;
            padding: 50px 0 20px;
        }

        .team-member {
            background-color: var(--white);
            padding: 15px 15px 35px 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            display: inline-block;
            border-radius: 4px;
            transition: all 0.3s ease;
            transform: rotate(-1.5deg);
            border: 1px solid #f0f0f0;
        }

        .team-member:hover {
            transform: rotate(1deg) scale(1.05);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            z-index: 10;
        }

        .team-member img {
            width: 210px;
            height: 210px;
            object-fit: cover;
            border: 1px solid #eee;
            margin-bottom: 5px;
            border-radius: 2px;
        }

        .team-member h6 {
            margin-top: 15px;
            color: #333;
        }

        .team-social a {
            color: #777;
            transition: color 0.3s ease;
            font-size: 1.1rem;
        }
        .team-social a:hover {
            color: var(--primary-blue);
        }

        /* Carousel Customization */
        .carousel-indicators [data-bs-target] {
            background-color: var(--primary-blue);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1) grayscale(100) brightness(0.5);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary-blue" href="#">
                <i class="bi bi-megaphone-fill me-2"></i>Lapor Infrastruktur
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#beranda" data-i18n="nav_home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur" data-i18n="nav_features">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#alur" data-i18n="nav_flow">Alur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#demo" data-i18n="nav_demo">Demo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#unduh" data-i18n="nav_download">Unduh</a></li>
                    <li class="nav-item ms-lg-3">
                        <button class="btn-lang-toggle" onclick="toggleLang()" id="lang-toggle">
                            <i class="bi bi-translate"></i>
                            <span id="lang-text">English</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4" data-i18n="hero_title">Platform Smart City Reporting untuk Infrastruktur</h1>
                    <p class="lead mb-5" data-i18n="hero_subtitle">Membantu masyarakat melaporkan jalan rusak, lampu mati, dan fasilitas umum lainnya langsung dari smartphone dengan tracking status real-time.</p>
                    <div class="d-flex gap-3">
                        <a href="#unduh" class="btn btn-primary" data-i18n="hero_cta_download">Download App</a>
                        <a href="#fitur" class="btn btn-outline-primary" data-i18n="hero_cta_features">Lihat Fitur</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                    <!-- Ganti URL src di bawah untuk mengubah gambar Hero -->
                    <img src="https://picsum.photos/seed/mobile/600/400" alt="App Mockup" class="img-fluid rounded-4 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="section-padding bg-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" data-i18n="problem_title">Kenapa Aplikasi Ini Dibutuhkan?</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 p-4">
                        <i class="bi bi-chat-dots-fill display-5 text-primary-blue mb-3"></i>
                        <h5 data-i18n="problem_1_title">Laporan Tercecer</h5>
                        <p class="text-muted" data-i18n="problem_1_desc">Laporan lewat WhatsApp sering tertumpuk dan tidak terdokumentasi dengan baik.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 p-4">
                        <i class="bi bi-geo-alt-fill display-5 text-primary-blue mb-3"></i>
                        <h5 data-i18n="problem_2_title">Lokasi Tidak Jelas</h5>
                        <p class="text-muted" data-i18n="problem_2_desc">Petugas sering kesulitan menemukan titik pasti kerusakan karena hanya laporan lisan.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 p-4">
                        <i class="bi bi-clock-history display-5 text-primary-blue mb-3"></i>
                        <h5 data-i18n="problem_3_title">Tanpa Tracking</h5>
                        <p class="text-muted" data-i18n="problem_3_desc">Masyarakat tidak tahu apakah laporan mereka sedang diproses atau diabaikan.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 p-4">
                        <i class="bi bi-files display-5 text-primary-blue mb-3"></i>
                        <h5 data-i18n="problem_4_title">Laporan Dobel</h5>
                        <p class="text-muted" data-i18n="problem_4_desc">Sulit mengelola banyak laporan yang sama untuk satu titik kerusakan.</p>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <blockquote class="blockquote italic">
                    <p class="mb-0" data-i18n="quote">“Warga tidak masalah menunggu perbaikan, asalkan tahu laporannya sedang diproses.”</p>
                </blockquote>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <!-- Ganti URL src di bawah untuk mengubah gambar About -->
                    <img src="https://picsum.photos/seed/smartcity/600/400" alt="Smart City" class="img-fluid rounded-4 shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4" data-i18n="about_title">Apa Itu Lapor Infrastruktur?</h2>
                    <p data-i18n="about_desc">Lapor Infrastruktur adalah platform pelaporan kerusakan fasilitas umum berbasis Smart City yang membantu masyarakat dan instansi pemerintah dalam pemantauan real-time dan pengelolaan laporan berbasis AI.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary-blue me-2"></i> GPS Geotagging</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary-blue me-2"></i> AI Clustering (DBSCAN)</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary-blue me-2"></i> Dashboard Monitoring</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary-blue me-2"></i> Mobile App Flutter</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section-padding bg-white" id="fitur">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" data-i18n="features_title">Fitur Unggulan</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 p-4 text-center">
                        <div class="mb-3 text-primary-blue"><i class="bi bi-pin-map-fill display-4"></i></div>
                        <h5 data-i18n="feature_1_title">Pelaporan Berbasis GPS</h5>
                        <p class="small text-muted" data-i18n="feature_1_desc">Laporan lengkap dengan koordinat lokasi otomatis dan foto kerusakan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 text-center">
                        <div class="mb-3 text-primary-blue"><i class="bi bi-cpu-fill display-4"></i></div>
                        <h5 data-i18n="feature_2_title">AI Clustering</h5>
                        <p class="small text-muted" data-i18n="feature_2_desc">Mengelompokkan laporan otomatis berdasarkan lokasi dan jenis kerusakan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 text-center">
                        <div class="mb-3 text-primary-blue"><i class="bi bi-speedometer2 display-4"></i></div>
                        <h5 data-i18n="feature_3_title">Tracking Real-Time</h5>
                        <p class="small text-muted" data-i18n="feature_3_desc">Pantau progres laporan: Sedang Dicek, Dalam Perbaikan, hingga Selesai.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Flow Section -->
    <section class="section-padding bg-light" id="alur">
        <div class="container">
            <h2 class="fw-bold text-center mb-5" data-i18n="flow_title">Bagaimana Sistem Bekerja?</h2>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="step-box">
                        <h6 class="fw-bold">1. <span data-i18n="flow_1_title">Pengguna Membuat Laporan</span></h6>
                        <p class="mb-0 small" data-i18n="flow_1_desc">Upload foto, tentukan lokasi GPS, dan pilih kategori kerusakan.</p>
                    </div>
                    <div class="step-box">
                        <h6 class="fw-bold">2. <span data-i18n="flow_2_title">Sistem Memproses Data</span></h6>
                        <p class="mb-0 small" data-i18n="flow_2_desc">Laporan disimpan dan dianalisis menggunakan AI clustering untuk efisiensi.</p>
                    </div>
                    <div class="step-box">
                        <h6 class="fw-bold">3. <span data-i18n="flow_3_title">Admin Verifikasi</span></h6>
                        <p class="mb-0 small" data-i18n="flow_3_desc">Pihak berwenang memvalidasi laporan yang masuk di dashboard.</p>
                    </div>
                    <div class="step-box">
                        <h6 class="fw-bold">4. <span data-i18n="flow_4_title">Tindak Lanjut</span></h6>
                        <p class="mb-0 small" data-i18n="flow_4_desc">Petugas lapangan dikirim untuk melakukan perbaikan fasilitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Section -->
    <section class="section-padding bg-white" id="demo">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold" data-i18n="demo_title">Demo Aplikasi</h2>
                <p class="text-muted" data-i18n="demo_subtitle">Gunakan akun berikut untuk mencoba fitur di platform kami secara langsung.</p>
            </div>
            <div class="row g-4">
                <!-- Admin Web -->
                <div class="col-md-6">
                    <div class="card h-100 p-4 border-primary shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-laptop display-6 text-primary-blue me-3"></i>
                            <h5 class="mb-0" data-i18n="demo_admin_title">Website Admin</h5>
                        </div>
                        <p class="small text-muted" data-i18n="demo_admin_desc">Digunakan oleh instansi untuk memantau, memverifikasi, dan mengelola laporan masyarakat.</p>
                        <div class="bg-light p-3 rounded mb-3">
                            <p class="mb-1"><strong>Link:</strong> <a href="https://lapor-min.ars-projects.my.id" target="_blank" class="text-decoration-none">lapor-min.ars-projects.my.id</a></p>
                            <p class="mb-1"><strong>Email:</strong> admin@test.com</p>
                            <p class="mb-0"><strong>Pass:</strong> admin123</p>
                        </div>
                        <div class="mt-auto">
                            <a href="https://lapor-min.ars-projects.my.id" target="_blank" class="btn btn-outline-primary btn-sm w-100" data-i18n="demo_open_web">Buka Dashboard</a>
                        </div>
                    </div>
                </div>
                <!-- Mobile App Roles -->
                <div class="col-md-6">
                    <div class="card h-100 p-4 border-primary shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-phone display-6 text-primary-blue me-3"></i>
                            <h5 class="mb-0" data-i18n="demo_app_title">Aplikasi Mobile</h5>
                        </div>
                        <p class="small text-muted" data-i18n="demo_app_desc">Unduh aplikasi dan masuk dengan salah satu akun di bawah untuk mencoba sebagai pelapor atau petugas.</p>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <div class="bg-light p-3 rounded h-100">
                                    <h6 class="fw-bold mb-2 text-primary-blue" data-i18n="role_reporter">Pelapor</h6>
                                    <p class="small mb-1"><strong>Email:</strong> pelapor@test.com</p>
                                    <p class="small mb-0"><strong>Pass:</strong> pelapor123</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-light p-3 rounded h-100">
                                    <h6 class="fw-bold mb-2 text-primary-blue" data-i18n="role_officer">Petugas</h6>
                                    <p class="small mb-1"><strong>Email:</strong> petugas@test.com</p>
                                    <p class="small mb-0"><strong>Pass:</strong> petugas123</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <a href="#unduh" class="btn btn-primary btn-sm w-100" data-i18n="demo_download_app">Unduh Aplikasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="section-padding bg-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" data-i18n="tech_title">Technology Stack</h2>
            <div class="d-flex flex-wrap justify-content-center">
                <span class="tech-badge">Flutter</span>
                <span class="tech-badge">Laravel</span>
                <span class="tech-badge">FastAPI</span>
                <span class="tech-badge">MySQL</span>
                <span class="tech-badge">GPS Geotagging</span>
                <span class="tech-badge">DBSCAN AI</span>
                <span class="tech-badge">LLM Summary</span>
            </div>
        </div>
    </section>

    <!-- App Download Section -->
    <section class="section-padding bg-primary-blue text-white" id="unduh">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="fw-bold mb-4" data-i18n="download_title">Dapatkan Aplikasi Sekarang</h2>
                    <p class="mb-4" data-i18n="download_desc">Laporkan masalah di sekitarmu dan pantau perkembangannya langsung dari genggaman. Tersedia untuk berbagai arsitektur perangkat Android.</p>
                    <div class="card bg-white text-dark p-4 rounded-4 shadow">
                        <h6 class="fw-bold mb-3" data-i18n="select_arch">Pilih Arsitektur Unduhan:</h6>
                        <div class="list-group">
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                                <div>
                                    <h6 class="mb-0 fw-bold">arm64-v8a (64-bit)</h6>
                                    <small class="text-muted">Size: 18.0 MB</small>
                                </div>
                                <a href="https://drive.google.com/file/d/1Iw2cGH-1gq1Vx7crQvdqlsTEEqg0C_fC/view?usp=drive_link" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3" data-i18n="nav_download">Unduh</a>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                                <div>
                                    <h6 class="mb-0 fw-bold">armeabi-v7a (32-bit)</h6>
                                    <small class="text-muted">Size: 15.7 MB</small>
                                </div>
                                <a href="https://drive.google.com/file/d/1atztrFCdVlEPXzbEzIbh7UGtoSQED7Yw/view?usp=drive_link" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3" data-i18n="nav_download">Unduh</a>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-0 border-bottom">
                                <div>
                                    <h6 class="mb-0 fw-bold">x86_64 (emulator Android)</h6>
                                    <small class="text-muted">Size: 19.3 MB</small>
                                </div>
                                <a href="https://drive.google.com/file/d/1vjKPNASkG4CNxWXlqExjqlpPyhv2xptV/view?usp=drive_link" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3" data-i18n="nav_download">Unduh</a>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-0">
                                <div>
                                    <h6 class="mb-0 fw-bold">Universal APK</h6>
                                    <small class="text-muted">Size: 50.5 MB</small>
                                </div>
                                <a href="https://drive.google.com/file/d/11YD5XVeoped9i5f-duv_SB7end9fk_9a/view?usp=sharing" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3" data-i18n="nav_download">Unduh</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 text-center">
                    <div class="bg-white p-4 d-inline-block rounded-4 shadow">
                        <img src="https://barcodeapi.org/api/qr/https://drive.google.com/drive/folders/1TGwIUud3-QlxeYImLAwQ1Jlh3L1Tih84?usp=sharing" alt="QR Code" class="qr-code-img mb-3">
                        <p class="text-dark fw-bold mb-0" data-i18n="scan_qr">Scan untuk Mengunduh</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section-padding bg-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-5" data-i18n="team_title">Tim Pengembang</h2>
            
            <div id="teamCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#teamCarousel" data-bs-slide-to="3"></button>
                </div>
                <div class="carousel-inner pb-5">
                    <div class="carousel-item active">
                        <div class="team-member">
                            <img src="andika.png" alt="Andika">
                            <h6 class="fw-bold mb-1">Andika Risky Septiawan</h6>
                            <p class="small text-muted mb-2">Project Manager</p>
                            <div class="team-social d-flex justify-content-center gap-3">
                                <a href="https://www.instagram.com/_andika.risky/"><i class="bi bi-instagram"></i></a>
                                <a href="https://www.linkedin.com/in/andikariskys/"><i class="bi bi-linkedin"></i></a>
                                <a href="https://github.com/andikariskys"><i class="bi bi-github"></i></a>
                                <a href="mailto:l200230023@student.ums.ac.id"><i class="bi bi-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="team-member">
                            <img src="haydar.jpeg" alt="Haydar">
                            <h6 class="fw-bold mb-1">Haydar Aulia Rahman</h6>
                            <p class="small text-muted mb-2">System Analyst</p>
                            <div class="team-social d-flex justify-content-center gap-3">
                                <a href="https://www.instagram.com/ndok_dadar_/"><i class="bi bi-instagram"></i></a>
                                <a href="https://www.linkedin.com/in/haydar-aulia-rahman-b6b7812a0/"><i class="bi bi-linkedin"></i></a>
                                <a href="https://github.com/Haydar13D"><i class="bi bi-github"></i></a>
                                <a href="mailto:l200230051@student.ums.ac.id"><i class="bi bi-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="team-member">
                            <img src="irfan.png" alt="Irfan">
                            <h6 class="fw-bold mb-1">Irfan Hanif Saputra</h6>
                            <p class="small text-muted mb-2">Front-end Engineer</p>
                            <div class="team-social d-flex justify-content-center gap-3">
                                <a href="https://www.instagram.com/irhns2/"><i class="bi bi-instagram"></i></a>
                                <a href="https://www.linkedin.com/in/irfan-h-487146319/"><i class="bi bi-linkedin"></i></a>
                                <a href="https://github.com/IrfanHanifs"><i class="bi bi-github"></i></a>
                                <a href="mailto:l200230020@student.ums.ac.id"><i class="bi bi-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="team-member">
                            <img src="nanang.png" alt="Nanang">
                            <h6 class="fw-bold mb-1">Nanang Marvin Kurniawan</h6>
                            <p class="small text-muted mb-2">Back-end Engineer</p>
                            <div class="team-social d-flex justify-content-center gap-3">
                                <a href="https://www.instagram.com/vinnn_mk/"><i class="bi bi-instagram"></i></a>
                                <a href="https://www.linkedin.com/in/nanang-marvin-kurniawan-343a762a9/"><i class="bi bi-linkedin"></i></a>
                                <a href="https://github.com/slashMK303"><i class="bi bi-github"></i></a>
                                <a href="mailto:l200230015@student.ums.ac.id"><i class="bi bi-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#teamCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#teamCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section-padding bg-light">
        <div class="container">
            <h2 class="fw-bold text-center mb-5">FAQ</h2>
            <div class="accordion accordion-flush" id="faqAccordion">
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" data-i18n="faq_1_q">
                            Apakah laporan bisa dipantau?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_1_a">
                            Ya, pengguna dapat melihat status laporan secara real-time melalui menu riwayat laporan di aplikasi.
                        </div>
                    </div>
                </div>
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" data-i18n="faq_2_q">
                            Apakah lokasi otomatis terdeteksi?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_2_a">
                            Benar, aplikasi menggunakan GPS geotagging untuk memastikan akurasi lokasi kerusakan infrastruktur.
                        </div>
                    </div>
                </div>
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" data-i18n="faq_3_q">
                            Siapa yang menangani laporan?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_3_a">
                            Setiap laporan yang valid akan diteruskan secara otomatis ke instansi atau petugas lapangan terkait untuk ditindaklanjuti.
                        </div>
                    </div>
                </div>
                <!-- FAQ Tambahan -->
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" data-i18n="faq_4_q">
                            Apakah aplikasi ini berbayar?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_4_a">
                            Tidak, aplikasi ini sepenuhnya gratis untuk digunakan oleh seluruh lapisan masyarakat.
                        </div>
                    </div>
                </div>
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" data-i18n="faq_5_q">
                            Perangkat apa saja yang didukung?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_5_a">
                            Saat ini aplikasi Lapor Infrastruktur tersedia untuk perangkat dengan sistem operasi Android.
                        </div>
                    </div>
                </div>
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" data-i18n="faq_6_q">
                            Bagaimana jika saya salah mengirim laporan?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_6_a">
                            Pengguna dapat membatalkan atau mengedit laporan selama status laporan tersebut masih dalam tahap "Menunggu Verifikasi".
                        </div>
                    </div>
                </div>
                <div class="accordion-item shadow-sm mb-3 rounded">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" data-i18n="faq_7_q">
                            Apakah identitas pelapor anonim?
                        </button>
                    </h2>
                    <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" data-i18n="faq_7_a">
                            Identitas Anda aman dalam sistem kami dan hanya digunakan oleh instansi terkait untuk proses validasi jika diperlukan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p class="mb-2"><strong>Lapor Infrastruktur</strong> - Smart City Reporting Project</p>
            <p class="small text-muted mb-0">&copy; 2026 Cipcupcapstone – Universitas Muhammadiyah Surakarta</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Translation Logic -->
    <script>
        const translations = {
            id: {
                nav_home: "Beranda",
                nav_features: "Fitur",
                nav_flow: "Alur",
                nav_demo: "Demo",
                nav_download: "Unduh",
                hero_title: "Platform Smart City Reporting untuk Infrastruktur",
                hero_subtitle: "Membantu masyarakat melaporkan jalan rusak, lampu mati, dan fasilitas umum lainnya langsung dari smartphone dengan tracking status real-time.",
                hero_cta_download: "Download App",
                hero_cta_features: "Lihat Fitur",
                problem_title: "Kenapa Aplikasi Ini Dibutuhkan?",
                problem_1_title: "Laporan Tercecer",
                problem_1_desc: "Laporan lewat WhatsApp sering tertumpuk dan tidak terdokumentasi dengan baik.",
                problem_2_title: "Lokasi Tidak Jelas",
                problem_2_desc: "Petugas sering kesulitan menemukan titik pasti kerusakan karena hanya laporan lisan.",
                problem_3_title: "Tanpa Tracking",
                problem_3_desc: "Masyarakat tidak tahu apakah laporan mereka sedang diproses atau diabaikan.",
                problem_4_title: "Laporan Dobel",
                problem_4_desc: "Sulit mengelola banyak laporan yang sama untuk satu titik kerusakan.",
                quote: "“Satu sistem pelaporan publik yang lebih jelas, tepat, dan transparan.”",
                about_title: "Apa Itu Lapor Infrastruktur?",
                about_desc: "Lapor Infrastruktur adalah platform pelaporan kerusakan fasilitas umum berbasis Smart City yang membantu masyarakat dan instansi pemerintah dalam pemantauan real-time dan pengelolaan laporan berbasis AI.",
                features_title: "Fitur Unggulan",
                feature_1_title: "Pelaporan Berbasis GPS",
                feature_1_desc: "Laporan lengkap dengan koordinat lokasi otomatis dan foto kerusakan.",
                feature_2_title: "AI Clustering",
                feature_2_desc: "Mengelompokkan laporan otomatis berdasarkan lokasi dan jenis kerusakan.",
                feature_3_title: "Tracking Real-Time",
                feature_3_desc: "Pantau progres laporan: Sedang Dicek, Dalam Perbaikan, hingga Selesai.",
                flow_title: "Bagaimana Sistem Bekerja?",
                flow_1_title: "Pengguna Membuat Laporan",
                flow_1_desc: "Upload foto, tentukan lokasi GPS, dan pilih kategori kerusakan.",
                flow_2_title: "Sistem Memproses Data",
                flow_2_desc: "Laporan disimpan dan dianalisis menggunakan AI clustering untuk efisiensi.",
                flow_3_title: "Admin Verifikasi",
                flow_3_desc: "Pihak berwenang memvalidasi laporan yang masuk di dashboard.",
                flow_4_title: "Tindak Lanjut",
                flow_4_desc: "Petugas lapangan dikirim untuk melakukan perbaikan fasilitas.",
                demo_title: "Demo Aplikasi",
                demo_subtitle: "Gunakan akun berikut untuk mencoba fitur di platform kami secara langsung.",
                demo_admin_title: "Website Admin",
                demo_admin_desc: "Digunakan oleh instansi untuk memantau, memverifikasi, dan mengelola laporan masyarakat.",
                demo_open_web: "Buka Dashboard",
                demo_app_title: "Aplikasi Mobile",
                demo_app_desc: "Unduh aplikasi dan masuk dengan salah satu akun di bawah untuk mencoba sebagai pelapor atau petugas.",
                role_reporter: "Pelapor",
                role_officer: "Petugas",
                demo_download_app: "Unduh Aplikasi",
                tech_title: "Technology Stack",
                download_title: "Dapatkan Aplikasi Sekarang",
                download_desc: "Laporkan masalah di sekitarmu dan pantau perkembangannya langsung dari genggaman. Tersedia untuk berbagai arsitektur perangkat Android.",
                select_arch: "Pilih Arsitektur Unduhan:",
                btn_download_now: "Unduh File .APK",
                scan_qr: "Scan untuk Mengunduh",
                team_title: "Tim Pengembang",
                faq_1_q: "Apakah laporan bisa dipantau?",
                faq_1_a: "Ya, pengguna dapat melihat status laporan secara real-time melalui menu riwayat laporan di aplikasi.",
                faq_2_q: "Apakah lokasi otomatis terdeteksi?",
                faq_2_a: "Benar, aplikasi menggunakan GPS geotagging untuk memastikan akurasi lokasi kerusakan infrastruktur.",
                faq_3_q: "Siapa yang menangani laporan?",
                faq_3_a: "Setiap laporan yang valid akan diteruskan secara otomatis ke instansi atau petugas lapangan terkait untuk ditindaklanjuti.",
                faq_4_q: "Apakah aplikasi ini berbayar?",
                faq_4_a: "Tidak, aplikasi ini sepenuhnya gratis untuk digunakan oleh seluruh lapisan masyarakat.",
                faq_5_q: "Perangkat apa saja yang didukung?",
                faq_5_a: "Saat ini aplikasi Lapor Infrastruktur tersedia untuk perangkat dengan sistem operasi Android.",
                faq_6_q: "Bagaimana jika saya salah mengirim laporan?",
                faq_6_a: "Pengguna dapat membatalkan atau mengedit laporan selama status laporan tersebut masih dalam tahap \"Menunggu Verifikasi\".",
                faq_7_q: "Apakah identitas pelapor anonim?",
                faq_7_a: "Identitas Anda aman dalam sistem kami dan hanya digunakan oleh instansi terkait untuk proses validasi jika diperlukan."
            },
            en: {
                nav_home: "Home",
                nav_features: "Features",
                nav_flow: "Flow",
                nav_demo: "Demo",
                nav_download: "Download",
                hero_title: "Smart City Reporting Platform for Infrastructure",
                hero_subtitle: "Helping communities report damaged roads, broken lights, and other public facilities directly from their smartphones with real-time status tracking.",
                hero_cta_download: "Download App",
                hero_cta_features: "View Features",
                problem_title: "Why is This App Needed?",
                problem_1_title: "Scattered Reports",
                problem_1_desc: "Reports via WhatsApp often get piled up and are not well documented.",
                problem_2_title: "Unclear Locations",
                problem_2_desc: "Officers often struggle to find the exact point of damage due to oral reports only.",
                problem_3_title: "No Tracking",
                problem_3_desc: "The public doesn't know if their reports are being processed or ignored.",
                problem_4_title: "Double Reports",
                problem_4_desc: "Hard to manage many identical reports for a single damage point.",
                quote: "“A clearer, more accurate, and transparent public reporting system.”",
                about_title: "What is Lapor Infrastruktur?",
                about_desc: "Lapor Infrastruktur is a Smart City-based public facility damage reporting platform that helps the public and government agencies in real-time monitoring and AI-based report management.",
                features_title: "Main Features",
                feature_1_title: "GPS-Based Reporting",
                feature_1_desc: "Complete reports with automatic location coordinates and photos of the damage.",
                feature_2_title: "AI Clustering",
                feature_2_desc: "Automatically groups reports based on location and type of damage.",
                feature_3_title: "Real-Time Tracking",
                feature_3_desc: "Monitor report progress: Checking, In Repair, to Completed.",
                flow_title: "How Does the System Work?",
                flow_1_title: "User Creates a Report",
                flow_1_desc: "Upload photos, determine GPS location, and select the damage category.",
                flow_2_title: "System Processes Data",
                flow_2_desc: "Reports are saved and analyzed using AI clustering for efficiency.",
                flow_3_title: "Admin Verification",
                flow_3_desc: "Authorities validate incoming reports on the dashboard.",
                flow_4_title: "Follow Up",
                flow_4_desc: "Field officers are sent to perform facility repairs.",
                demo_title: "Application Demo",
                demo_subtitle: "Use the following accounts to try our platform features directly.",
                demo_admin_title: "Admin Website",
                demo_admin_desc: "Used by agencies to monitor, verify, and manage public reports.",
                demo_open_web: "Open Dashboard",
                demo_app_title: "Mobile Application",
                demo_app_desc: "Download the app and login with one of the accounts below to try as a reporter or officer.",
                role_reporter: "Reporter",
                role_officer: "Officer",
                demo_download_app: "Download Application",
                tech_title: "Technology Stack",
                download_title: "Get the App Now",
                download_desc: "Report problems around you and monitor their progress directly from your palm. Available for various Android device architectures.",
                select_arch: "Select Download Architecture:",
                btn_download_now: "Download .APK File",
                scan_qr: "Scan to Download",
                team_title: "Development Team",
                faq_1_q: "Can reports be monitored?",
                faq_1_a: "Yes, users can view report status in real-time through the report history menu in the application.",
                faq_2_q: "Is the location automatically detected?",
                faq_2_a: "Correct, the application uses GPS geotagging to ensure the accuracy of infrastructure damage locations.",
                faq_3_q: "Who handles the reports?",
                faq_3_a: "Every valid report will be automatically forwarded to the relevant agency or field officer for follow-up.",
                faq_4_q: "Is this app paid?",
                faq_4_a: "No, this application is completely free to use for all levels of society.",
                faq_5_q: "What devices are supported?",
                faq_5_a: "Currently the Lapor Infrastruktur application is available for devices with the Android operating system.",
                faq_6_q: "What if I send the wrong report?",
                faq_6_a: "Users can cancel or edit reports as long as the report status is still in the \"Waiting for Verification\" stage.",
                faq_7_q: "Is the reporter's identity anonymous?",
                faq_7_a: "Your identity is safe in our system and is only used by relevant agencies for the validation process if needed."
            }
        };

        let currentLang = 'id';
        function toggleLang() {
            currentLang = currentLang === 'id' ? 'en' : 'id';
            switchLang(currentLang);
        }

        function switchLang(lang) {
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (translations[lang][key]) {
                    el.textContent = translations[lang][key];
                }
            });

            const langText = document.getElementById('lang-text');
            if (lang === 'id') {
                langText.textContent = 'English';
                document.documentElement.lang = 'id';
            } else {
                langText.textContent = 'Indonesia';
                document.documentElement.lang = 'en';
            }
            currentLang = lang;
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
