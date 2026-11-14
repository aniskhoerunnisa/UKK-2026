
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = strtolower($_SESSION['role'] ?? '');

$is_walikelas = false;

// CEK GURU YANG LOGIN
if ($role == 'guru' && isset($koneksi)) {

    $id_user = (int)($_SESSION['id'] ?? $_SESSION['id_user'] ?? 0);

    if ($id_user > 0) {

        $query_guru = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_guru WHERE user_id = ? LIMIT 1"
        );

        if ($query_guru) {
            mysqli_stmt_bind_param($query_guru, "i", $id_user);
            mysqli_stmt_execute($query_guru);

            $hasil_guru = mysqli_stmt_get_result($query_guru);
            $data_guru = mysqli_fetch_assoc($hasil_guru);

            if ($data_guru) {

                $id_guru = (int)$data_guru['id'];

                $query_wali = mysqli_prepare(
                    $koneksi,
                    "SELECT id
                     FROM t_wali_kelas
                     WHERE guru_id = ?
                       AND status_aktif = 1
                     LIMIT 1"
                );

                if ($query_wali) {
                    mysqli_stmt_bind_param($query_wali, "i", $id_guru);
                    mysqli_stmt_execute($query_wali);

                    $hasil_wali = mysqli_stmt_get_result($query_wali);

                    if (mysqli_num_rows($hasil_wali) > 0) {
                        $is_walikelas = true;
                    }

                    mysqli_stmt_close($query_wali);
                }
            }

            mysqli_stmt_close($query_guru);
        }
    }
}
?>

<style>
.sidebar-menu {
    position: fixed;
    top: 0;
    left: 0;
    width: 222px;
    height: 100vh;
    background: #212529;
    color: white;
    padding: 8px;
    overflow-y: auto;
    z-index: 1000;
    box-sizing: border-box;
}

.sidebar-menu h5 {
    color: white;
    font-size: 20px;
    font-weight: bold;
    margin: 10px 4px 25px;
}

.sidebar-menu .menu-label {
    color: #8492a6;
    font-size: 12px;
    margin: 15px 8px 8px;
}

.sidebar-menu .nav-link {
    color: white;
    padding: 11px 16px;
    border-radius: 7px;
    margin-bottom: 4px;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.sidebar-menu .nav-link:hover {
    background: #343a40;
    color: white;
}

.sidebar-menu .nav-link.active {
    background: #0d6efd;
    color: white;
}

.sidebar-menu hr {
    border-color: #495057;
    margin: 18px 0;
}

.sidebar-menu .logout {
    color: #ff3547;
}

.sidebar-menu .logout:hover {
    color: #ff3547;
    background: #343a40;
}

@media (max-width: 767px) {
    .sidebar-menu {
        width: 190px;
    }
}
</style>

<aside class="sidebar-menu">

    <h5>
        Pelanggaran Siswa
    </h5>

    <hr>

    <?php if ($role == 'admin') { ?>

        <div class="menu-label">MENU ADMIN</div>

        <a href="dashboard.php"
           class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            Dashboard
        </a>

        <a href="kelola_user.php"
           class="nav-link <?= $current_page == 'kelola_user.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-cog"></i>
            Kelola User
        </a>

        <a href="kelola_guru.php"
           class="nav-link <?= $current_page == 'kelola_guru.php' ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-teacher"></i>
            Kelola Guru
        </a>

        <a href="kelola_siswa.php"
           class="nav-link <?= $current_page == 'kelola_siswa.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            Kelola Siswa
        </a>

        <a href="kelola_kelas.php"
           class="nav-link <?= $current_page == 'kelola_kelas.php' ? 'active' : ''; ?>">
            <i class="fas fa-school"></i>
            Kelola Kelas
        </a>

        <a href="kelola_catat.php"
           class="nav-link <?= $current_page == 'kelola_catat.php' ? 'active' : ''; ?>">
            <i class="fas fa-clipboard-list"></i>
            Kelola Catatan
        </a>

        <a href="laporan.php"
           class="nav-link <?= $current_page == 'laporan.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-alt"></i>
            Laporan
        </a>

    <?php } elseif ($role == 'guru') { ?>

        <div class="menu-label">MENU GURU</div>

        <a href="dashboard.php"
           class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i>
            Dashboard
        </a>

        <a href="catat_pelanggaran.php"
           class="nav-link <?= $current_page == 'catat_pelanggaran.php' ? 'active' : ''; ?>">
            <i class="fas fa-edit"></i>
            Catat Pelanggaran
        </a>

        <a href="riwayat.php"
           class="nav-link <?= $current_page == 'riwayat.php' ? 'active' : ''; ?>">
            <i class="fas fa-history"></i>
            Riwayat
        </a>

        <a href="rekap_poin.php"
           class="nav-link <?= $current_page == 'rekap_poin.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            Rekap Poin
        </a>

        <?php if ($is_walikelas) { ?>

            <hr>

            <div class="menu-label">MENU WALI KELAS</div>

            <a href="laporan.php"
               class="nav-link <?= $current_page == 'laporan.php' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i>
                Laporan Wali Kelas
            </a>

        <?php } ?>

    <?php } ?>

    <hr>

    <a href="logout.php"
       class="nav-link logout"
       onclick="return confirm('Yakin ingin logout?')">

        <i class="fas fa-sign-out-alt"></i>
        Logout
    </a>

</aside>
