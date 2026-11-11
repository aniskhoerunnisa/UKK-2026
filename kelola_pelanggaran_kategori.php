
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data kategori pelanggaran
$sql = "SELECT * FROM t_kategori_pelanggaran ORDER BY nama_kategori ASC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Statistik
$total = mysqli_num_rows($query);
$aktif = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM t_kategori_pelanggaran WHERE status_aktif = 1"));
$nonaktif = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM t_kategori_pelanggaran WHERE status_aktif = 0"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola pelanggaran kategori</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        * { box-sizing: border-box; }

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
            padding: 25px 28px;
            border-radius: 13px;
            margin-bottom: 25px;
        }

        .welcome-box h5 {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .welcome-box p {
            margin: 0;
            font-size: 14px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 22px;
            box-shadow: 0 3px 15px rgba(30, 45, 80, .04);
        }

        .stat-card h3 {
            font-size: 25px;
            font-weight: bold;
            margin: 8px 0 0;
        }

        .stat-card p {
            color: #8992a5;
            margin: 0;
            font-size: 13px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef0ff;
            color: #5368ed;
            font-size: 18px;
        }

        .content-card {
            background: white;
            border-radius: 13px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(30, 45, 80, .04);
        }

        .content-card h5 {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .content-card .description {
            color: #929bad;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .btn-purple {
            background: #5368ed;
            color: white;
            border-radius: 7px;
            padding: 9px 16px;
            font-size: 13px;
        }

        .btn-purple:hover {
            background: #4055d5;
            color: white;
        }

        table thead th {
            background: #f6f7fc;
            color: #626e85;
            font-size: 12px;
            border-top: none !important;
            white-space: nowrap;
        }

        table tbody td {
            font-size: 13px;
            vertical-align: middle !important;
        }

        .badge-status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
        }

        .status-aktif {
            background: #e2f8eb;
            color: #219653;
        }

        .status-nonaktif {
            background: #ffe8e8;
            color: #dc3545;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 6px;
            margin-right: 3px;
        }

        .edit-btn {
            background: #fff2d9;
            color: #e49a16;
        }

        .delete-btn {
            background: #ffe5e5;
            color: #e34c4c;
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
        <i class="fas fa-graduation-cap mr-2"></i> MyWebsite.
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
    <a href="kelola_pelanggaran_kategori.php" class="active">
        <i class="fas fa-list-alt"></i> Kategori Pelanggaran
    </a>
    <a href="kelola_jenis_pelanggaran.php">
        <i class="fas fa-exclamation-triangle"></i> Jenis Pelanggaran
    </a>
    <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>

    <div class="menu-title">Lainnya</div>
    <a href="about_me.php"><i class="fas fa-user"></i> About Me</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- KONTEN -->
<div class="main-content">

    <h2 class="page-title">Kelola Kategori Pelanggaran</h2>
    <p class="subtitle">Kelola data kategori pelanggaran siswa.</p>

    <div class="welcome-box">
        <h5><i class="fas fa-list-alt mr-2"></i> Data Kategori Pelanggaran</h5>
        <p>Halaman untuk melihat, menambah, mengubah, dan menghapus kategori pelanggaran.</p>
    </div>

    <!-- STATISTIK -->
    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-list"></i></div>
                <p class="mt-3">Total Kategori</p>
                <h3><?= $total; ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <p class="mt-3">Kategori Aktif</p>
                <h3><?= $aktif; ?></h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <p class="mt-3">Kategori Nonaktif</p>
                <h3><?= $nonaktif; ?></h3>
            </div>
        </div>
    </div>

    <!-- TABEL -->
    <div class="content-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h5>Daftar Kategori Pelanggaran</h5>
                <p class="description mb-0">Daftar seluruh kategori pelanggaran siswa.</p>
            </div>

            <a href="tambah_kategori_pelanggaran.php" class="btn btn-purple mt-2">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </a>
        </div>

        <div class="table-responsive">
            <table id="tabelKategori" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($data = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($data['nama_kategori']); ?></td>
                        <td><?= htmlspecialchars($data['deskripsi'] ?? '-'); ?></td>
                        <td>
                            <?php if ($data['status_aktif'] == 1) : ?>
                                <span class="badge-status status-aktif">Aktif</span>
                            <?php else : ?>
                                <span class="badge-status status-nonaktif">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_kategori_pelanggaran.php?id=<?= $data['id']; ?>"
                               class="action-btn edit-btn d-inline-flex align-items-center justify-content-center"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="hapus_kategori_pelanggaran.php?id=<?= $data['id']; ?>"
                               class="action-btn delete-btn d-inline-flex align-items-center justify-content-center"
                               onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                               title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelKategori').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

</body>
</html>
