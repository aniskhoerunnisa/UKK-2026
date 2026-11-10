
<?php
// ==========================================
// KELOLA WALI KELAS
// ==========================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data wali kelas
$sql = "SELECT
            w.*,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
        FROM t_wali_kelas w
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY w.id DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}

// Menampung data
$data_wali = [];
$total_aktif = 0;
$total_nonaktif = 0;

while ($row = mysqli_fetch_assoc($hasil)) {

    $data_wali[] = $row;

    if ((int)($row['status_aktif'] ?? 0) === 1) {
        $total_aktif++;
    } else {
        $total_nonaktif++;
    }
}

$total_wali = count($data_wali);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Data Wali Kelas</title>

<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>

* {
    box-sizing: border-box;
}

body {
    background: #f4f6ff;
    font-family: Arial, sans-serif;
    font-size: 13px;
    color: #202b46;
    margin: 0;
}

/* KONTEN UTAMA */

.main-content {
    margin-left: 282px;
    padding: 25px;
    min-height: 100vh;
}

/* JUDUL */

.page-title {
    font-size: 23px;
    font-weight: bold;
    color: #202b46;
    margin-bottom: 5px;
}

.page-subtitle {
    color: #8995aa;
    font-size: 12px;
    margin-bottom: 22px;
}

/* BANNER */

.banner {
    background: linear-gradient(110deg, #5368f2, #9360df);
    padding: 27px 30px;
    border-radius: 14px;
    color: white;
    margin-bottom: 25px;
    box-shadow: 0 7px 20px rgba(83,104,242,.15);
}

.banner-label {
    display: inline-block;
    background: rgba(255,255,255,.2);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 10px;
    margin-bottom: 10px;
}

.banner h4 {
    font-size: 19px;
    font-weight: bold;
    margin-bottom: 8px;
}

.banner p {
    font-size: 12px;
    margin: 0;
    opacity: .95;
}

/* STATISTIK */

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 18px;
    box-shadow: 0 5px 20px rgba(32,43,70,.05);
    height: 100%;
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 45px;
    height: 45px;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.icon-purple {
    background: #eeeaff;
    color: #7058dc;
}

.icon-green {
    background: #e4f8eb;
    color: #19934a;
}

.icon-red {
    background: #ffe8e9;
    color: #dc3545;
}

.stat-label {
    font-size: 11px;
    color: #8995aa;
    margin-bottom: 5px;
}

.stat-number {
    font-size: 22px;
    font-weight: bold;
    color: #202b46;
    margin: 0;
}

/* DAFTAR WALI KELAS */

.section-title {
    font-size: 17px;
    font-weight: bold;
    color: #202b46;
    margin-bottom: 5px;
}

.section-desc {
    font-size: 12px;
    color: #8995aa;
}

/* TOMBOL TAMBAH */

.btn-tambah {
    background: linear-gradient(110deg, #5368f2, #8057e9);
    color: white !important;
    border: none;
    border-radius: 8px;
    padding: 10px 15px;
    font-size: 12px;
    font-weight: bold;
    transition: .2s;
}

.btn-tambah:hover {
    opacity: .9;
    transform: translateY(-1px);
}

/* KARTU TABEL */

.table-card {
    background: white;
    padding: 22px;
    border-radius: 13px;
    box-shadow: 0 5px 20px rgba(32,43,70,.06);
    margin-top: 15px;
}

.table-heading {
    font-size: 15px;
    font-weight: bold;
    color: #202b46;
    margin-bottom: 5px;
}

.table-description {
    color: #8995aa;
    font-size: 11px;
    margin-bottom: 20px;
}

/* TABEL */

#tabelWaliKelas {
    width: 100% !important;
    font-size: 11px;
    color: #344563;
}

#tabelWaliKelas thead th {
    background: #202b46;
    color: white;
    border-color: #344563;
    white-space: nowrap;
    padding: 12px 10px;
    vertical-align: middle;
}

#tabelWaliKelas tbody td {
    padding: 11px 10px;
    vertical-align: middle;
}

#tabelWaliKelas tbody tr:hover {
    background: #f5f6ff;
}

/* STATUS */

.badge-status {
    display: inline-block;
    background: #e4f8eb;
    color: #19934a;
    border-radius: 20px;
    padding: 5px 11px;
    font-size: 10px;
    font-weight: bold;
    white-space: nowrap;
}

.badge-nonaktif {
    background: #ffe8e9;
    color: #dc3545;
}

/* AKSI */

.aksi {
    display: flex;
    gap: 6px;
    white-space: nowrap;
}

.btn-edit {
    background: #17a2b8;
    color: white !important;
    border-radius: 6px;
    padding: 6px 9px;
    font-size: 10px;
    text-decoration: none !important;
}

.btn-hapus {
    background: #e63946;
    color: white !important;
    border-radius: 6px;
    padding: 6px 9px;
    font-size: 10px;
    text-decoration: none !important;
}

/* DATATABLES */

.dataTables_wrapper {
    font-size: 11px;
    color: #687799;
}

.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #dce2f0;
    border-radius: 6px;
    padding: 5px 8px;
    outline: none;
    font-size: 11px;
}

.dataTables_wrapper .page-item.active .page-link {
    background: #625fd5;
    border-color: #625fd5;
}

.dataTables_wrapper .page-link {
    color: #344563;
    font-size: 11px;
}

/* FOOTER */

.footer-text {
    margin-top: 25px;
    font-size: 10px;
    color: #8995aa;
}

/* RESPONSIVE */

@media (max-width: 768px) {

    .main-content {
        margin-left: 0;
        padding: 15px;
    }

    .banner {
        padding: 20px;
    }

    .table-card {
        padding: 15px;
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



<!-- KONTEN -->
<main class="main-content">

    <!-- JUDUL -->
    <div class="mb-4">

        <h3 class="page-title">
            Data Wali Kelas
            <span style="color:#8057e9;">.</span>
        </h3>

        <p class="page-subtitle">
            Kelola dan pantau data guru yang bertugas sebagai wali kelas.
        </p>

    </div>


    <!-- BANNER -->
    <div class="banner">

        <span class="banner-label">
            <i class="fas fa-user-tie mr-1"></i>
            DATA WALI KELAS
        </span>

        <h4>
            <i class="fas fa-chalkboard-teacher mr-2"></i>
            Manajemen Data Wali Kelas
        </h4>

        <p>
            Halaman untuk melihat, mencari, menambahkan,
            mengubah, dan menghapus data wali kelas.
        </p>

    </div>


    <!-- STATISTIK -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="stat-card">

                <div class="stat-icon icon-purple">
                    <i class="fas fa-users"></i>
                </div>

                <div>
                    <div class="stat-label">Total Wali Kelas</div>
                    <h4 class="stat-number">
                        <?= $total_wali; ?>
                    </h4>
                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="stat-card">

                <div class="stat-icon icon-green">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div>
                    <div class="stat-label">Wali Kelas Aktif</div>
                    <h4 class="stat-number">
                        <?= $total_aktif; ?>
                    </h4>
                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="stat-card">

                <div class="stat-icon icon-red">
                    <i class="fas fa-times-circle"></i>
                </div>

                <div>
                    <div class="stat-label">Wali Kelas Tidak Aktif</div>
                    <h4 class="stat-number">
                        <?= $total_nonaktif; ?>
                    </h4>
                </div>

            </div>

        </div>

    </div>


    <!-- JUDUL DAFTAR -->
    <div class="d-flex justify-content-between align-items-center flex-wrap">

        <div class="mb-3">

            <h5 class="section-title">
                Daftar Wali Kelas
            </h5>

            <p class="section-desc">
                Berikut merupakan data wali kelas yang terdaftar dalam sistem.
            </p>

        </div>

        <a href="tambah_wali_kelas.php" class="btn btn-tambah mb-3">

            <i class="fas fa-plus mr-1"></i>
            Tambah Wali Kelas

        </a>

    </div>


    <!-- TABEL -->
    <div class="table-card">

        <h6 class="table-heading">
            <i class="fas fa-list-ul mr-2" style="color:#625fd5;"></i>
            Tabel Data Wali Kelas
        </h6>

        <p class="table-description">
            Gunakan fitur pencarian untuk menemukan data wali kelas dengan mudah.
        </p>


        <div class="table-responsive">

            <table id="tabelWaliKelas"
                   class="table table-striped table-bordered table-hover">

                <thead>

                    <tr>
                        <th width="45">No</th>
                        <th>ID Tahun Ajaran</th>
                        <th>Kelas</th>
                        <th>Nama Guru</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th width="120">Aksi</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($data_wali as $index => $row) { ?>

                    <tr>

                        <td><?= $index + 1; ?></td>

                        <td>
                            <?= htmlspecialchars((string)($row['tahun_ajaran_id'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)($row['nama_kelas'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)($row['nama_guru'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)($row['tanggal_mulai'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)($row['tanggal_selesai'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                        </td>

                        <td>

                            <?php if ((int)($row['status_aktif'] ?? 0) === 1) { ?>

                                <span class="badge-status">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aktif
                                </span>

                            <?php } else { ?>

                                <span class="badge-status badge-nonaktif">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Tidak Aktif
                                </span>

                            <?php } ?>

                        </td>

                        <td>

                            <div class="aksi">

                                <a href="edit_wali_kelas.php?id=<?= (int)$row['id']; ?>"
                                   class="btn-edit">

                                    <i class="fas fa-edit"></i>
                                    Edit

                                </a>

                                <a href="hapus_wali_kelas.php?id=<?= (int)$row['id']; ?>"
                                   class="btn-hapus"
                                   onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">

                                    <i class="fas fa-trash"></i>
                                    Hapus

                                </a>

                            </div>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- FOOTER -->
    <div class="footer-text">
        Sistem Pelanggaran Siswa © 2026
    </div>

</main>


<!-- JAVASCRIPT -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>


<script>

$(document).ready(function() {

    $('#tabelWaliKelas').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [],

        columnDefs: [
            {
                targets: [0, 7],
                orderable: false,
                searchable: false
            }
        ],

        language: {

            search: "Cari:",

            searchPlaceholder: "Cari data wali kelas...",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data wali kelas",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data wali kelas tidak ditemukan",

            emptyTable: "Belum ada data wali kelas",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }

        }

    });

});

</script>

</body>
</html>
