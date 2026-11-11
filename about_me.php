
<?php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Me</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6ff;
            font-family: Arial, sans-serif;
            color: #26324b;
        }

        .sidebar {
            width: 243px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #202b46;
            color: white;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            padding: 5px 12px 30px;
        }

        .menu-title {
            font-size: 11px;
            color: #929db5;
            margin: 18px 12px 10px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: block;
            color: #c4ccdc;
            text-decoration: none;
            padding: 12px 13px;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .sidebar a i {
            width: 23px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #5368ed;
            color: white;
        }

        .main-content {
            margin-left: 243px;
            padding: 35px;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .subtitle {
            color: #8790a5;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .welcome-box {
            background: linear-gradient(110deg, #5368ed, #8170ed);
            color: white;
            padding: 27px 30px;
            border-radius: 13px;
            margin-bottom: 25px;
        }

        .welcome-box h4 {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .welcome-box p {
            margin: 0;
            font-size: 14px;
        }

        .profile-card,
        .info-card {
            background: white;
            border-radius: 13px;
            padding: 28px;
            box-shadow: 0 3px 15px rgba(30,45,80,.04);
            margin-bottom: 22px;
        }

        .profile-card {
            text-align: center;
            padding: 40px 25px;
        }

        .profile-icon {
            width: 115px;
            height: 115px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5368ed, #8170ed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 55px;
            margin: 0 auto 20px;
        }

        .profile-card h3 {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .profile-card .role {
            color: #5368ed;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .profile-card .bio {
            color: #8992a5;
            font-size: 14px;
            line-height: 1.8;
            max-width: 500px;
            margin: auto;
        }

        .info-card h5 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #edf0f7;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 9px;
            background: #eef0ff;
            color: #5368ed;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            flex-shrink: 0;
        }

        .info-item small {
            color: #929bad;
            display: block;
            margin-bottom: 4px;
        }

        .info-item strong {
            font-size: 14px;
            color: #26324b;
        }

        .project-card {
            background: white;
            padding: 25px;
            border-radius: 13px;
            box-shadow: 0 3px 15px rgba(30,45,80,.04);
            height: 100%;
        }

        .project-icon {
            width: 48px;
            height: 48px;
            border-radius: 11px;
            background: #eef0ff;
            color: #5368ed;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            margin-bottom: 17px;
        }

        .project-card h5 {
            font-weight: bold;
            font-size: 17px;
        }

        .project-card p {
            color: #8992a5;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .footer {
            text-align: center;
            color: #929bad;
            font-size: 12px;
            padding: 20px 0 5px;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">
        <i class="fas fa-graduation-cap mr-2"></i> SISWA APP
    </div>

    <div class="menu-title">Menu Utama</div>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <div class="menu-title">Master Data</div>
    <a href="kelola_siswa.php"><i class="fas fa-user-graduate"></i> Kelola Siswa</a>
    <a href="kelola_guru.php"><i class="fas fa-chalkboard-teacher"></i> Kelola Guru</a>
    <a href="kelola_wali_kelas.php"><i class="fas fa-user-tie"></i> Wali Kelas</a>
    <a href="kelola_kelas.php"><i class="fas fa-school"></i> Kelola Kelas</a>
    <a href="kelola_tahun_ajaran.php"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</a>
    <a href="penempatan_siswa.php"><i class="fas fa-users"></i> Penempatan Siswa</a>

    <div class="menu-title">Pelanggaran</div>
    <a href="kelola_pelanggaran_kategori.php"><i class="fas fa-list-alt"></i> Kategori Pelanggaran</a>
    <a href="kelola_jenis_pelanggaran.php"><i class="fas fa-exclamation-triangle"></i> Jenis Pelanggaran</a>
    <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>

    <div class="menu-title">Lainnya</div>
    <a href="about_me.php" class="active"><i class="fas fa-user"></i> About Me</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- KONTEN -->
<div class="main-content">

    <h2 class="page-title">About Me</h2>
    <p class="subtitle">Informasi mengenai pengembang sistem.</p>

    <div class="welcome-box">
        <h4><i class="fas fa-user-circle mr-2"></i> Tentang Anis Khoerunnisa N.R</h4>
        <p>Kenali lebih dekat pengembang Sistem Pelanggaran Siswa.</p>
    </div>

    <div class="row">

        <!-- PROFIL -->
        <div class="col-lg-5">
            <div class="profile-card">
                <div class="profile-icon">
                    <i class="fas fa-user"></i>
                </div>

                <h3>Nama Anis Khoerunnisa N.R</h3>
                <div class="role">Web Developer</div>

                <p class="bio">
                    Saya merupakan seorang pelajar Rekayasa Perangkat Lunak
                    yang tertarik pada dunia teknologi, pemrograman,
                    dan pengembangan aplikasi berbasis web.
                </p>
            </div>
        </div>

        <!-- INFORMASI -->
        <div class="col-lg-7">
            <div class="info-card">
                <h5><i class="fas fa-id-card mr-2" style="color:#5368ed;"></i> Informasi Profil</h5>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <small>Nama</small>
                        <strong>Anis Khoerunnisa N.R</strong>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <small>Jurusan</small>
                        <strong>Rekayasa Perangkat Lunak (RPL)</strong>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <div>
                        <small>Sekolah</small>
                        <strong>SMK MUHAMMADIYAH</strong>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div>
                        <small>Keahlian</small>
                        <strong>PHP, MySQL, HTML, CSS, JavaScript</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- INFORMASI PROJECT -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="project-card">
                <div class="project-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <h5>Proyek yang Dikembangkan</h5>
                <p>
                    Sistem Pelanggaran Siswa merupakan aplikasi berbasis web
                    yang dibuat untuk membantu sekolah dalam mengelola data
                    dan pencatatan pelanggaran siswa.
                </p>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="project-card">
                <div class="project-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h5>Teknologi yang Digunakan</h5>
                <p>
                    Aplikasi ini dikembangkan menggunakan PHP Native,
                    MySQL sebagai database, serta HTML, CSS, Bootstrap,
                    dan JavaScript untuk tampilan antarmuka.
                </p>
            </div>
        </div>
    </div>

    <div class="footer">
        © 2026 Sistem Pelanggaran Siswa. All Rights Reserved.
    </div>

</div>

</body>
</html>
