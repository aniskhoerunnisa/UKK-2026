
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
    return $data['total'] ?? 0;
}

$totalSiswa = jumlahData($koneksi, 't_siswa');
$totalGuru  = jumlahData($koneksi, 't_guru');
$totalKelas = jumlahData($koneksi, 't_kelas');

$namaUser = $_SESSION['nama'] ?? $_SESSION['nama_lengkap'] ?? 'Pengguna';
$roleUser = $_SESSION['role'] ?? 'Pengguna';
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

        /* KONTEN DASHBOARD */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
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
            .main-content {
                margin-left: 210px;
                width: calc(100% - 210px);
                padding: 18px;
            }

            .welcome-box h3 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

    <!-- MEMANGGIL SIDEBAR SESUAI ROLE LOGIN -->
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

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
                        Nama:
                        <strong><?= htmlspecialchars($namaUser); ?></strong>
                    </p>

                    <p>
                        Role:
                        <strong><?= htmlspecialchars($roleUser); ?></strong>
                    </p>

                </div>
            </div>

        </div>

    </div>

</body>
</html>