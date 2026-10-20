
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$nama = $_SESSION['nama']
    ?? $_SESSION['nama_lengkap']
    ?? $_SESSION['name']
    ?? 'Pengguna';

$role = $_SESSION['role'] ?? 'guru';

// Menghitung jumlah data
function jumlahData($koneksi, $tabel) {
    $query = mysqli_query(
        $koneksi,
        "SELECT COUNT(*) AS total FROM `$tabel`"
    );

    if ($query) {
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }

    return 0;
}

$siswa = jumlahData($koneksi, 't_siswa');
$guru  = jumlahData($koneksi, 't_guru');
$kelas = jumlahData($koneksi, 't_kelas');
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard - Sistem Pelanggaran Siswa</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6ff;
    color: #202d49;
}

/* SIDEBAR */
.sidebar {
    width: 304px;
    min-height: 100vh;
    background: #202d49;
    padding: 22px 15px;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    overflow-y: auto;
}

.logo {
    color: white;
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 38px;
}

.logo i {
    margin-right: 7px;
}

.menu-label {
    color: #8390aa;
    font-size: 12px;
    margin-bottom: 18px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    margin-bottom: 7px;
    color: #e3e9f7;
    font-size: 14px;
    text-decoration: none;
    border-radius: 12px;
    transition: .2s;
}

.menu a i {
    width: 18px;
    text-align: center;
}

.menu a:hover,
.menu a.active {
    background: #5368ed;
    color: white;
}

.menu-divider {
    border-top: 1px solid #3b4760;
    margin-top: 20px;
    padding-top: 20px;
}

.menu a.logout {
    color: #ff5368;
}

/* KONTEN */
.main {
    margin-left: 304px;
    padding: 25px 30px 40px;
}

.page-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 7px;
}

.page-title span {
    color: #7764ef;
}

.page-subtitle {
    color: #7e8ca9;
    margin-bottom: 26px;
}

/* BANNER */
.welcome {
    position: relative;
    overflow: hidden;
    background: linear-gradient(115deg, #5268ed, #8260e9);
    padding: 38px;
    border-radius: 24px;
    color: white;
    margin-bottom: 25px;
    box-shadow: 0 12px 28px rgba(83, 103, 237, .15);
}

.welcome:after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    right: -15px;
    top: -65px;
    border: 28px solid rgba(255,255,255,.09);
    border-radius: 50%;
}

.welcome-label {
    display: inline-block;
    background: rgba(255,255,255,.20);
    padding: 9px 15px;
    border-radius: 30px;
    font-size: 11px;
    margin-bottom: 18px;
}

.welcome h2 {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 10px;
}

.welcome p {
    line-height: 1.9;
    margin-bottom: 15px;
    max-width: 650px;
}

/* KARTU STATISTIK */
.stat-card {
    background: white;
    border-radius: 18px;
    padding: 25px;
    height: 100%;
    box-shadow: 0 8px 25px rgba(30,45,80,.04);
    transition: transform .2s;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-icon {
    width: 58px;
    height: 58px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 22px;
    border-radius: 17px;
    margin-bottom: 20px;
}

.purple {
    background: linear-gradient(135deg, #626cf4, #9b7af6);
}

.blue {
    background: linear-gradient(135deg, #4095f4, #67b7f8);
}

.orange {
    background: linear-gradient(135deg, #ff9d52, #ffc16b);
}

.stat-card h3 {
    font-size: 30px;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-card p {
    color: #7e8ca9;
    margin-bottom: 0;
}

/* INFORMASI */
.info-card {
    background: white;
    border-radius: 18px;
    padding: 26px;
    margin-top: 25px;
    min-height: 190px;
    box-shadow: 0 8px 25px rgba(30,45,80,.04);
}

.info-title {
    font-weight: bold;
    font-size: 19px;
    margin-bottom: 18px;
}

.info-title i {
    color: #7165ef;
    margin-right: 10px;
}

.info-card p {
    color: #7e8ca9;
    line-height: 1.9;
}

.account-icon {
    display: inline-flex;
    width: 42px;
    height: 42px;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #eeedff;
    color: #7165ef;
    margin-right: 10px;
}

@media (max-width: 767px) {
    .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
    }

    .logo {
        margin-bottom: 25px;
    }

    .main {
        margin-left: 0;
        padding: 22px 16px;
    }

    .welcome {
        padding: 26px 22px;
    }

    .welcome h2 {
        font-size: 25px;
    }

    .page-title {
        font-size: 28px;
    }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="logo">
        <i class="fas fa-layer-group"></i> MyWebsite.
    </div>

    <div class="menu-label">MENU UTAMA</div>

    <nav class="menu">

        <a href="dashboard.php" class="active">
            <i class="fas fa-th-large"></i>
            Dashboard
        </a>

        <?php if ($role == 'admin') { ?>

        <a href="kelola_siswa.php">
            <i class="fas fa-user-graduate"></i>
            Data Siswa
        </a>

        <a href="kelola_guru.php">
            <i class="fas fa-chalkboard-teacher"></i>
            Data Guru
        </a>

        <a href="wali_kelas.php">
            <i class="fas fa-user-tie"></i>
             Data Wali Kelas
        </a>

        <a href="kelola_kelas.php">
            <i class="fas fa-school"></i>
            Data Kelas
        </a>

        <a href="kelola_tahun_ajaran.php">
            <i class="fas fa-calendar-alt"></i>
             Data Tahun Ajaran
        </a>

        <?php } ?>

        <a href="about_me.php">
            <i class="fas fa-user-circle"></i>
            About Me
        </a>

        <div class="menu-divider">
            <a href="logout.php" class="logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>

    </nav>
</aside>

<!-- KONTEN UTAMA -->
<main class="main">

    <h1 class="page-title">
        Dashboard <span>.</span>
    </h1>

    <p class="page-subtitle">
        Selamat datang di Sistem Pelanggaran Siswa ✨
    </p>

    <!-- BANNER SELAMAT DATANG -->
    <section class="welcome">

        <div class="welcome-label">
            <i class="fas fa-heart"></i>
            WELCOME TO MY PAGE
        </div>

        <h2>
            Hai anis khoerunnisa, 👋
        </h2>

        <p>
            Selamat datang di halaman dashboard Sistem Pelanggaran
            Siswa. Kelola informasi siswa, guru, dan kelas sekolah
            dengan mudah melalui satu halaman.
        </p>

        <strong>Keep learning, keep growing! 🚀</strong>

    </section>

    <!-- KARTU STATISTIK -->
    <div class="row">

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card">

                <div class="stat-icon purple">
                    <i class="fas fa-user-graduate"></i>
                </div>

                <h3><?= $siswa ?></h3>
                <p>Total Data Siswa</p>

            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card">

                <div class="stat-icon blue">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>

                <h3><?= $guru ?></h3>
                <p>Total Data Guru</p>

            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card">

                <div class="stat-icon orange">
                    <i class="fas fa-school"></i>
                </div>

                <h3><?= $kelas ?></h3>
                <p>Total Data Kelas</p>

            </div>
        </div>

    </div>

    <!-- KARTU INFORMASI -->
    <div class="row">

        <div class="col-lg-7">
            <div class="info-card">

                <div class="info-title">
                    <i class="fas fa-lightbulb"></i>
                    Tentang Sistem
                </div>

                <p>
                    Sistem Pelanggaran Siswa merupakan website untuk
                    membantu pengelolaan data sekolah. Informasi siswa,
                    guru, dan kelas dapat dikelola dengan lebih rapi,
                    praktis, dan terstruktur.
                </p>

            </div>
        </div>

        <div class="col-lg-5">
            <div class="info-card">

                <div class="info-title">
                    <span class="account-icon">
                        <i class="fas fa-user"></i>
                    </span>
                    Informasi Akun
                </div>

                <p>
                    Nama:anis khoerunnisa
                    <strong></strong>
                </p>

                <p>
                    Role:
                    <strong><?= htmlspecialchars($role) ?></strong>
                </p>

            </div>
        </div>

    </div>

</main>

</body>
</html>
