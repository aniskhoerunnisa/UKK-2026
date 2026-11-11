
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// AMBIL DATA JENIS PELANGGARAN
$sql = "SELECT * FROM t_pelanggaran ORDER BY nama ASC";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}

// STATISTIK
$total = mysqli_num_rows($query);

$qAktif = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_pelanggaran WHERE status_aktif = 1"
);

$qNonaktif = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM t_pelanggaran WHERE status_aktif = 0"
);

$qPoin = mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(poin), 0) AS total FROM t_pelanggaran WHERE status_aktif = 1"
);

$aktif = mysqli_fetch_assoc($qAktif)['total'];
$nonaktif = mysqli_fetch_assoc($qNonaktif)['total'];
$totalPoin = mysqli_fetch_assoc($qPoin)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Jenis Pelanggaran</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f3f5ff;
            font-family: Arial, sans-serif;
            color: #253451;
        }

        .sidebar {
            width: 243px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #202b46;
            padding: 25px 16px;
            overflow-y: auto;
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

        .sidebar a i { width: 23px; }

        .sidebar a:hover,
        .sidebar a.active {
            background: #5368ed;
            color: white;
        }

        .main-content {
            margin-left: 243px;
            padding: 30px;
        }

        .page-title {
            font-size: 27px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .subtitle {
            color: #8790a5;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .welcome-box {
            background: linear-gradient(110deg, #5368ed, #8860e8);
            color: white;
            padding: 25px 28px;
            border-radius: 15px;
            margin-bottom: 22px;
        }

        .welcome-box h5 {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .welcome-box p {
            font-size: 13px;
            margin: 0;
            line-height: 1.7;
        }

        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            min-height: 145px;
            box-shadow: 0 5px 20px rgba(30,45,80,.035);
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-bottom: 13px;
            font-size: 18px;
        }

        .purple { background: linear-gradient(135deg,#7164fa,#9873f5); }
        .blue { background: linear-gradient(135deg,#3294f5,#69b8f5); }
        .orange { background: linear-gradient(135deg,#ff994b,#ffbf68); }

        .stat-card h3 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px;
        }

        .stat-card p {
            color: #7d8ca9;
            font-size: 13px;
            margin: 0;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 24px;
            box-shadow: 0 5px 20px rgba(30,45,80,.035);
        }

        .content-card h5 {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .description {
            color: #929bad;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .btn-purple {
            background: #5368ed;
            color: white;
            border-radius: 7px;
            padding: 9px 15px;
            font-size: 13px;
        }

        .btn-purple:hover {
            background: #4055d5;
            color: white;
        }

        .table thead th {
            background: #f6f7fc;
            color: #626e85;
            font-size: 12px;
            border-top: none;
            white-space: nowrap;
        }

        .table tbody td {
            font-size: 13px;
            vertical-align: middle;
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

        .btn-action {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            margin: 1px;
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
    <a href="dashboard.php">
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
        <i class="fas fa-user-tie"></i> Wali Kelas
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
    <a href="kelola_jenis_pelanggaran.php" class="active">
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

<!-- KONTEN -->
<div class="main-content">

    <h2 class="page-title">Kelola Jenis Pelanggaran</h2>
    <p class="subtitle">Kelola data jenis pelanggaran siswa.</p>

    <div class="welcome-box">
        <h5>
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Data Jenis Pelanggaran
        </h5>
        <p>
            Halaman ini digunakan untuk mengelola jenis pelanggaran,
            kode pelanggaran, deskripsi, dan poin pelanggaran siswa.
        </p>
    </div>

    <!-- STATISTIK -->
    <div class="row">

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-list"></i>
                </div>
                <h3><?= $total; ?></h3>
                <p>Total Jenis Pelanggaran</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3><?= $aktif; ?></h3>
                <p>Jenis Pelanggaran Aktif</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-star"></i>
                </div>
                <h3><?= $totalPoin; ?></h3>
                <p>Total Poin Pelanggaran Aktif</p>
            </div>
        </div>

    </div>

    <!-- TABEL -->
    <div class="content-card">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <div>
                <h5>Daftar Jenis Pelanggaran</h5>
                <p class="description mb-0">
                    Berikut merupakan daftar jenis pelanggaran yang tersedia.
                </p>
            </div>

            <a href="tambah_jenis_pelanggaran.php" class="btn btn-purple mt-2">
                <i class="fas fa-plus mr-1"></i> Tambah Jenis Pelanggaran
            </a>
        </div>

        <div class="table-responsive">
            <table id="tabelPelanggaran" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Pelanggaran</th>
                        <th>ID Kategori</th>
                        <th>Poin</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) :
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <?= htmlspecialchars($data['kode'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['nama'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['pelanggaran_kategori_id'] ?? '-'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['poin'] ?? '0'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['deskripsi'] ?? '-'); ?>
                        </td>

                        <td>
                            <?php if ($data['status_aktif'] == 1) : ?>
                                <span class="badge-status status-aktif">Aktif</span>
                            <?php else : ?>
                                <span class="badge-status status-nonaktif">Nonaktif</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="edit_jenis_pelanggaran.php?id=<?= (int)$data['id']; ?>"
                               class="btn btn-warning btn-action"
                               title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="hapus_jenis_pelanggaran.php?id=<?= (int)$data['id']; ?>"
                               class="btn btn-danger btn-action"
                               title="Hapus"
                               onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
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
$(document).ready(function() {
    $('#tabelPelanggaran').DataTable({
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
