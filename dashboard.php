
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Menghitung jumlah data
function jumlahData($koneksi, $tabel) {
    $query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM `$tabel`");
    if (!$query) {
        return 0;
    }

    $data = mysqli_fetch_assoc($query);
    return $data['total'];
}

$totalSiswa = jumlahData($koneksi, 't_siswa');
$totalGuru  = jumlahData($koneksi, 't_guru');
$totalKelas = jumlahData($koneksi, 't_kelas');

$namaUser = $_SESSION['nama'] ?? 'Pengguna';
$roleUser = $_SESSION['role'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Sistem Pelanggaran Siswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f5ff;
            font-family: Arial, sans-serif;
            color: #253451;
        }

        /* SIDEBAR */
        .sidebar {
            width: 243px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #202b46;
            padding: 25px 16px;
            overflow-y: auto;
            z-index: 100;
        }

        .brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 5px 12px 30px;
        }

        .menu-title {
            color: #929db5;
            font-size: 11px;
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

        /* KONTEN */
        .main-content {
            margin-left: 243px;
            padding: 30px;
        }

        /* WELCOME */
        .welcome-box {
            position: relative;
            overflow: hidden;
            background: linear-gradient(110deg, #5368ed, #8860e8);
            color: white;
            padding: 28px 30px;
            border-radius: 17px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(83, 104, 237, .12);
        }

        .welcome-box h3 {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .welcome-box p {
            font-size: 13px;
            line-height: 1.8;
            margin-bottom: 8px;
        }

        .welcome-box .keep {
            font-weight: bold;
            margin-top: 12px;
            margin-bottom: 0;
        }

        .welcome-decoration {
            position: absolute;
            right: -15px;
            top: -55px;
            width: 145px;
            height: 145px;
            border: 18px solid rgba(255,255,255,.08);
            border-radius: 50%;
        }

        /* STATISTIK */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            min-height: 153px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(30, 45, 80, .035);
        }

        .stat-icon {
            width: 47px;
            height: 47px;
            border-radius: 13px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            margin-bottom: 16px;
        }

        .icon-siswa {
            background: linear-gradient(135deg, #7164fa, #9873f5);
        }

        .icon-guru {
            background: linear-gradient(135deg, #3294f5, #69b8f5);
        }

        .icon-kelas {
            background: linear-gradient(135deg, #ff994b, #ffbf68);
        }

        .stat-card h3 {
            font-size: 23px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .stat-card p {
            font-size: 13px;
            color: #7d8ca9;
            margin: 0;
        }

        /* INFORMASI */
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 22px;
            min-height: 153px;
            height: 100%;
            box-shadow: 0 5px 20px rgba(30, 45, 80, .035);
        }

        .info-title {
            display: flex;
            align-items: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 17px;
        }

        .info-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #f0efff;
            color: #7464f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .info-card p {
            color: #7d8ca9;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 10px;
        }

        @media(max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                padding: 18px;
            }

            .welcome-box h3 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="brand">
        <i class="fas fa-graduation-cap mr-2"></i> MyWebsite.
    </div>

    <div class="menu-title">Menu Utama</div>

    <a href="dashboard.php" class="active">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <div class="menu-title">Master Data</div>

    <a href="kelola_siswa.php">
        <i class="fas fa-user-graduate"></i> Kelola Siswa
    </a>

    <a href="kelola_guru.php">
        <i class="fas fa-chalkboard-teacher"></i> Kelola Guru
    </a>

    <a href="kelola_wali_kelas.php">
        <i class="fas fa-user-tie"></i> Kelola Wali Kelas
    </a>

    <a href="kelola_kelas.php">
        <i class="fas fa-school"></i> Kelola Kelas
    </a>

    <a href="kelola_tahun_ajaran.php">
        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
    </a>

    <a href="penempatan_siswa.php">
        <i class="fas fa-users"></i> Penempatan Siswa
    </a>

    <div class="menu-title">Pelanggaran</div>

    <a href="kelola_pelanggaran_kategori.php">
        <i class="fas fa-list-alt"></i> Kategori Pelanggaran
    </a>

    <a href="kelola_jenis_pelanggaran.php">
        <i class="fas fa-exclamation-triangle"></i> Jenis Pelanggaran
    </a>

    <a href="laporan.php">
        <i class="fas fa-file-alt"></i> Laporan
    </a>

    <div class="menu-title">Lainnya</div>

    <a href="about_me.php">
        <i class="fas fa-user"></i> About Me
    </a>

    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

</div>

<!-- KONTEN DASHBOARD -->
<div class="main-content">

    <!-- WELCOME -->
    <div class="welcome-box">

        <div class="welcome-decoration"></div>

        <h3>
            Hai <?= htmlspecialchars($namaUser); ?>, 👋
        </h3>

        <p>
            Selamat datang di halaman dashboard Sistem Pelanggaran Siswa.
            Kelola informasi siswa,<br>
            guru, dan kelas sekolah dengan mudah melalui satu halaman.
        </p>

        <p class="keep">
            Keep learning, keep growing! 🚀
        </p>

    </div>

    <!-- STATISTIK -->
    <div class="row">

        <div class="col-lg-4 col-md-6">
            <div class="stat-card">

                <div class="stat-icon icon-siswa">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <h3><?= $totalSiswa; ?></h3>
                <p>Total Data Siswa</p>

            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stat-card">

                <div class="stat-icon icon-guru">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>

                <h3><?= $totalGuru; ?></h3>
                <p>Total Data Guru</p>

            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="stat-card">

                <div class="stat-icon icon-kelas">
                    <i class="fas fa-school"></i>
                </div>

                <h3><?= $totalKelas; ?></h3>
                <p>Total Data Kelas</p>

            </div>
        </div>

    </div>

    <!-- INFORMASI BAWAH -->
    <div class="row">

        <div class="col-lg-7 mb-3">
            <div class="info-card">

                <div class="info-title">
                    <div class="info-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    Tentang Sistem
                </div>

                <p>
                    Sistem Pelanggaran Siswa merupakan website untuk membantu
                    pengelolaan data sekolah. Informasi siswa, guru, dan kelas
                    dapat dikelola dengan lebih rapi, praktis, dan terstruktur.
                </p>

            </div>
        </div>

        <div class="col-lg-5 mb-3">
            <div class="info-card">

                <div class="info-title">
                    <div class="info-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    Informasi Akun
                </div>

                <p>
                    Nama: <strong><?= htmlspecialchars($namaUser); ?></strong>
                </p>

                <p>
                    Role: <strong><?= htmlspecialchars($roleUser); ?></strong>
                </p>

            </div>
        </div>

    </div>

</div>

</body>
</html>
