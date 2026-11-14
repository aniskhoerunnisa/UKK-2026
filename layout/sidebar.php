
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);

// Mengambil role dari session login
$role = strtolower(trim($_SESSION['role'] ?? ''));

// Menentukan apakah yang login adalah guru
$is_guru = ($role === 'guru');
?>

<style>
    .sidebar-custom {
        width: 250px;
        min-height: 100vh;
        background: linear-gradient(180deg, #263b80, #202b46);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9999;
        padding: 25px 15px;
        overflow-y: auto;
    }

    .sidebar-custom .brand {
        font-size: 25px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 35px;
        color: white;
    }

    .sidebar-custom .menu-title {
        font-size: 12px;
        color: #b9c5e8;
        margin: 20px 12px 10px;
        text-transform: uppercase;
    }

    .sidebar-custom a {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 13px 15px;
        margin-bottom: 7px;
        color: #e8ecff;
        text-decoration: none;
        border-radius: 10px;
        transition: 0.2s;
        font-size: 15px;
    }

    .sidebar-custom a:hover,
    .sidebar-custom a.active {
        background: #637fe0;
        color: white;
    }

    .sidebar-custom a i {
        width: 20px;
        text-align: center;
    }

    .sidebar-custom .logout {
        margin-top: 30px;
        background: #dc3545;
        color: white;
    }

    .sidebar-custom .logout:hover {
        background: #bd2130;
    }

    .main-content {
        margin-left: 250px;
        width: calc(100% - 250px);
        padding: 25px;
    }

    @media (max-width: 768px) {
        .sidebar-custom {
            width: 210px;
        }

        .main-content {
            margin-left: 210px;
            width: calc(100% - 210px);
        }
    }
</style>

<div class="sidebar-custom">

    <div class="brand">
        <i class="fas fa-book-open"></i>
        MyWebsite.
    </div>

    <div class="menu-title">Menu Utama</div>

    <a href="dashboard.php"
       class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        <i class="fas fa-home"></i>
        Dashboard
    </a>

    <?php if ($is_guru): ?>

        <!-- MENU KHUSUS GURU -->

        <div class="menu-title">Menu Guru</div>

        <a href="catat_pelanggaran.php"
           class="<?= $current_page == 'catat_pelanggaran.php' ? 'active' : '' ?>">
            <i class="fas fa-edit"></i>
            Catat Pelanggaran
        </a>

        <a href="tindakan.php"
           class="<?= $current_page == 'tindakan.php' ? 'active' : '' ?>">
            <i class="fas fa-clipboard-check"></i>
            Tindakan
        </a>

        <a href="riwayat.php"
           class="<?= $current_page == 'riwayat.php' ? 'active' : '' ?>">
            <i class="fas fa-history"></i>
            Riwayat
        </a>

        <a href="rekap_poin.php"
           class="<?= $current_page == 'rekap_poin.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i>
            Rekap Poin
        </a>

    <?php else: ?>

        <!-- MENU ADMIN -->

        <div class="menu-title">Master Data</div>

        <a href="kelola_siswa.php"
           class="<?= $current_page == 'kelola_siswa.php' ? 'active' : '' ?>">
            <i class="fas fa-user-graduate"></i>
            Kelola Siswa
        </a>

        <a href="kelola_guru.php"
           class="<?= $current_page == 'kelola_guru.php' ? 'active' : '' ?>">
            <i class="fas fa-chalkboard-teacher"></i>
            Kelola Guru
        </a>

        <a href="kelola_wali_kelas.php"
           class="<?= $current_page == 'kelola_wali_kelas.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            Kelola Wali Kelas
        </a>

        <a href="kelola_kelas.php"
           class="<?= $current_page == 'kelola_kelas.php' ? 'active' : '' ?>">
            <i class="fas fa-school"></i>
            Kelola Kelas
        </a>

        <a href="kelola_tahun_ajaran.php"
           class="<?= $current_page == 'kelola_tahun_ajaran.php' ? 'active' : '' ?>">
            <i class="fas fa-calendar-alt"></i>
            Tahun Ajaran
        </a>

        <a href="penempatan_siswa.php"
           class="<?= $current_page == 'penempatan_siswa.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            Penempatan Siswa
        </a>

        <a href="pelanggaran_kategori.php"
           class="<?= $current_page == 'pelanggaran_kategori.php' ? 'active' : '' ?>">
            <i class="fas fa-list"></i>
            Pelanggaran Kategori
        </a>

        <a href="kelola_jenis_pelanggaran.php"
           class="<?= $current_page == 'kelola_jenis_pelanggaran.php' ? 'active' : '' ?>">
            <i class="fas fa-exclamation-triangle"></i>
            Jenis Pelanggaran
        </a>

        <a href="laporan.php"
           class="<?= $current_page == 'laporan.php' ? 'active' : '' ?>">
            <i class="fas fa-file-alt"></i>
            Laporan
        </a>

    <?php endif; ?>

    <a href="logout.php" class="logout">
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </a>

</div>
