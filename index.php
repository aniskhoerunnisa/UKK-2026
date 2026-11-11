
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelanggaran Siswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fb;
            color: #202b46;
        }

        /* NAVBAR */
        .navbar {
            padding: 18px 8%;
            background: #fff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: 700;
            color: #202b46 !important;
        }

        .navbar-brand i {
            margin-right: 10px;
            color: #52678e;
        }

        .btn-nav {
            background: #202b46;
            color: white;
            padding: 10px 24px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-nav:hover {
            background: #344668;
            color: white;
        }

        /* HERO */
        .hero {
            min-height: 590px;
            padding: 80px 8%;
            display: flex;
            align-items: center;
            background: #f5f7fb;
        }

        .hero-content {
            width: 55%;
        }

        .hero-label {
            display: inline-block;
            background: #e7ebf3;
            color: #52678e;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-size: 46px;
            font-weight: 750;
            line-height: 1.25;
            margin-bottom: 20px;
            color: #202b46;
        }

        .hero h1 span {
            color: #52678e;
        }

        .hero p {
            color: #7b8497;
            font-size: 15px;
            line-height: 1.9;
            max-width: 480px;
            margin-bottom: 30px;
        }

        .btn-main {
            display: inline-block;
            background: #202b46;
            color: white;
            padding: 14px 27px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-main:hover {
            background: #344668;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .btn-main i {
            margin-left: 10px;
        }

        .hero-image {
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .illustration {
            width: 330px;
            height: 330px;
            background: #e5eaf3;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .illustration i {
            font-size: 125px;
            color: #52678e;
        }

        .circle-icon {
            position: absolute;
            width: 65px;
            height: 65px;
            background: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(32,43,70,0.1);
        }

        .circle-icon i {
            font-size: 25px;
        }

        .icon-one {
            top: 20px;
            right: 0;
        }

        .icon-two {
            bottom: 25px;
            left: 0;
        }

        /* FEATURES */
        .features {
            background: white;
            padding: 75px 8%;
            text-align: center;
        }

        .section-title {
            font-size: 29px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .section-desc {
            color: #8992a5;
            font-size: 14px;
            margin-bottom: 45px;
        }

        .feature-card {
            padding: 30px 22px;
            border: 1px solid #edf0f5;
            border-radius: 12px;
            height: 100%;
            transition: 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(32,43,70,0.08);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: #e9edf5;
            color: #52678e;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 14px;
            font-size: 23px;
        }

        .feature-card h5 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 13px;
            color: #8992a5;
            line-height: 1.8;
            margin: 0;
        }

        /* FOOTER */
        footer {
            background: #202b46;
            color: #c5ccda;
            padding: 23px 8%;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 65px 7%;
            }

            .hero-content,
            .hero-image {
                width: 100%;
            }

            .hero h1 {
                font-size: 34px;
            }

            .hero p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-image {
                margin-top: 55px;
            }

            .illustration {
                width: 250px;
                height: 250px;
            }

            .illustration > i {
                font-size: 95px;
            }

            .navbar {
                padding: 15px 5%;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="index.php">
        <i class="fas fa-graduation-cap"></i>
        Sistem Pelanggaran Siswa
    </a>

    <div class="ml-auto">
        <a href="login.php" class="btn btn-nav">
            Masuk <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">

    <div class="hero-content">

        <span class="hero-label">
            <i class="fas fa-shield-alt mr-2"></i>
            SISTEM INFORMASI SEKOLAH
        </span>

        <h1>
            Membangun Kedisiplinan
            <span>Siswa</span> Lebih Baik
        </h1>

        <p>
            Sistem Pelanggaran Siswa merupakan platform informasi
            untuk membantu sekolah dalam mengelola data pelanggaran,
            mencatat poin, serta memantau kedisiplinan siswa secara
            terstruktur dan efisien.
        </p>

        <a href="login.php" class="btn-main">
            Masuk ke Sistem
            <i class="fas fa-arrow-right"></i>
        </a>

    </div>

    <div class="hero-image">
        <div class="illustration">
            <i class="fas fa-user-graduate"></i>

            <div class="circle-icon icon-one">
                <i class="fas fa-book-open"></i>
            </div>

            <div class="circle-icon icon-two">
                <i class="fas fa-clipboard-check"></i>
            </div>
        </div>
    </div>

</section>

<!-- FEATURES -->
<section class="features">

    <h2 class="section-title">Fitur Sistem</h2>

    <p class="section-desc">
        Mendukung pengelolaan kedisiplinan siswa secara lebih mudah.
    </p>

    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h5>Data Siswa</h5>
                <p>
                    Mengelola informasi dan identitas siswa
                    secara terorganisir.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h5>Data Pelanggaran</h5>
                <p>
                    Mencatat dan mengelola pelanggaran
                    yang dilakukan oleh siswa.
                </p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h5>Laporan Pelanggaran</h5>
                <p>
                    Menampilkan laporan dan rekapitulasi
                    poin pelanggaran siswa.
                </p>
            </div>
        </div>

    </div>

</section>

<!-- FOOTER -->
<footer>
    © 2026 Sistem Pelanggaran Siswa. All Rights Reserved.
</footer>

</body>
</html>
