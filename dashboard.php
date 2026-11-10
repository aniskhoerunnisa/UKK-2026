```php
<nav class="menu">

    <a href="dashboard.php" class="active">
        <i class="fas fa-th-large"></i>
        Dashboard
    </a>

    <?php if ($role == 'admin') { ?>

    <a href="kelola_siswa.php">
        <i class="fas fa-user-graduate"></i>
        Kelola Siswa
    </a>

    <a href="kelola_guru.php">
        <i class="fas fa-chalkboard-teacher"></i>
        Kelola Guru
    </a>

    <a href="wali_kelas.php">
        <i class="fas fa-user-tie"></i>
        Wali Kelas
    </a>

    <a href="kelola_kelas.php">
        <i class="fas fa-school"></i>
        Kelola Kelas
    </a>

    <a href="kelola_tahun_ajaran.php">
        <i class="fas fa-calendar-alt"></i>
        Tahun Ajaran
    </a>

    <!-- MENU TAMBAHAN -->

    <a href="penempatan_siswa.php">
        <i class="fas fa-users"></i>
        Penempatan Siswa
    </a>

    <a href="pelanggan_kategori.php">
        <i class="fas fa-tags"></i>
        Kategori Pelanggan
    </a>

    <a href="kategori_pelanggaran.php">
        <i class="fas fa-exclamation-triangle"></i>
        Kategori Jenis Pelanggaran
    </a>

    <a href="laporan.php">
        <i class="fas fa-file-alt"></i>
        Laporan
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
```