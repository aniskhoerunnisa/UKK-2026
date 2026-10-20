
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_siswa ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Siswa - Sistem Pelanggaran Siswa</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6ff;
    color: #202d49;
}

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

.menu-label {
    color: #8390aa;
    font-size: 12px;
    margin-bottom: 18px;
}

.menu a {
    display: block;
    color: #e3e9f7;
    text-decoration: none;
    padding: 15px;
    margin-bottom: 7px;
    border-radius: 12px;
    font-size: 14px;
    transition: .2s;
}

.menu a i {
    width: 25px;
}

.menu a:hover,
.menu a.active {
    background: #5368ed;
    color: white;
}

.menu-divider {
    border-top: 1px solid #3b4760;
    margin-top: 20px;
    padding-top: 15px;
}

.menu a.logout { color: #ff5368; }

.main {
    margin-left: 304px;
    padding: 28px 30px 40px;
}

.page-title {
    font-size: 32px;
    font-weight: bold;
}

.page-title span { color: #7764ef; }

.subtitle {
    color: #7e8ca9;
    margin-bottom: 25px;
}

.top-banner {
    background: linear-gradient(115deg, #5268ed, #8260e9);
    color: white;
    padding: 28px 32px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 10px 25px rgba(83,103,237,.12);
}

.top-banner h3 {
    font-size: 24px;
    font-weight: bold;
}

.top-banner p {
    margin: 0;
    line-height: 1.8;
}

.btn-add {
    background: linear-gradient(110deg, #5268ed, #8260e9);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 12px 18px;
    font-weight: bold;
}

.btn-add:hover {
    color: white;
    opacity: .9;
}

.table-card {
    background: white;
    padding: 24px;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(30,45,80,.05);
}

.table-heading {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 5px;
}

.table-description {
    color: #7e8ca9;
    margin-bottom: 22px;
}

#tabelSiswa {
    width: 100% !important;
}

#tabelSiswa thead th {
    background: #202d49;
    color: white;
    border-color: #35425f;
    white-space: nowrap;
    vertical-align: middle;
}

#tabelSiswa td {
    vertical-align: middle;
    color: #46536d;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: #f7f8ff;
}

.badge-status {
    padding: 7px 11px;
    border-radius: 20px;
}

.btn-sm {
    border-radius: 7px;
    margin: 2px;
}

.dataTables_wrapper {
    color: #526078;
    font-size: 13px;
}

.dataTables_wrapper .form-control,
.dataTables_wrapper select {
    border: 1px solid #dfe4f1;
    border-radius: 8px;
}

.dataTables_wrapper .page-item.active .page-link {
    background: #5368ed;
    border-color: #5368ed;
}

.dataTables_wrapper .page-link {
    color: #5368ed;
}

.footer {
    color: #8b97ad;
    font-size: 13px;
    margin-top: 25px;
}

@media (max-width: 767px) {
    .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
    }

    .logo { margin-bottom: 25px; }

    .main {
        margin-left: 0;
        padding: 22px 15px;
    }

    .table-card { padding: 15px; }

    .page-title { font-size: 27px; }

    .top-banner { padding: 23px; }
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
        <a href="dashboard.php">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <a href="kelola_siswa.php" class="active">
            <i class="fas fa-user-graduate"></i> Kelola Siswa
        </a>

        <a href="kelola_guru.php">
            <i class="fas fa-chalkboard-teacher"></i> Kelola Guru
        </a>

        <a href="wali_kelas.php">
            <i class="fas fa-user-tie"></i> Wali Kelas
        </a>

        <a href="kelola_kelas.php">
            <i class="fas fa-school"></i> Kelola Kelas
        </a>

        <a href="kelola_tahun_ajaran.php">
            <i class="fas fa-calendar-alt"></i> Tahun Ajaran
        </a>

        <a href="about_me.php">
            <i class="fas fa-user-circle"></i> About Me
        </a>

        <div class="menu-divider">
            <a href="logout.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>
</aside>

<!-- KONTEN -->
<main class="main">

    <h1 class="page-title">
        Kelola Siswa <span>.</span>
    </h1>

    <p class="subtitle">
        Kelola dan pantau seluruh data siswa dengan mudah ✨
    </p>

    <div class="top-banner">
        <h3><i class="fas fa-user-graduate"></i> Data Siswa</h3>
        <p>
            Halaman untuk melihat, mencari, menambahkan,
            mengubah, dan menghapus data siswa.
        </p>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <h4 class="font-weight-bold">Daftar Siswa</h4>
            <p class="text-muted">Data siswa yang terdaftar dalam sistem.</p>
        </div>

        <a href="tambah_siswa.php" class="btn btn-add mb-3">
            <i class="fas fa-plus"></i> Tambah Siswa
        </a>
    </div>

    <div class="table-card">

        <div class="table-heading">
            <i class="fas fa-list" style="color:#7764ef"></i>
            Tabel Data Siswa
        </div>

        <p class="table-description">
            Gunakan kolom pencarian untuk menemukan data siswa.
        </p>

        <div class="table-responsive">
            <table id="tabelSiswa"
                   class="table table-bordered table-striped table-hover">

                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($hasil && mysqli_num_rows($hasil) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nis'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['nisn'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['jenis_kelamin'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['tanggal_lahir'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>

                        <td>
                            <?php if (($row['status_aktif'] ?? 0) == 1) { ?>
                                <span class="badge badge-success badge-status">Aktif</span>
                            <?php } else { ?>
                                <span class="badge badge-danger badge-status">Tidak Aktif</span>
                            <?php } ?>
                        </td>

                        <td style="white-space:nowrap">
                            <a href="edit_siswa.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            <a href="hapus_siswa.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

    <div class="footer">
        Sistem Pelanggaran Siswa &copy; <?= date('Y') ?>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelSiswa').DataTable({
        pageLength: 10,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'Semua']
        ],
        autoWidth: false,
        order: [[2, 'asc']],
        columnDefs: [{
            targets: 7,
            orderable: false,
            searchable: false
        }],
        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari data siswa...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data siswa tidak ditemukan',
            emptyTable: 'Belum ada data siswa',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    });
});
</script>

</body>
</html>
