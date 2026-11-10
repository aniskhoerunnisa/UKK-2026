
<?php
// kelola_jenis_pelanggaran.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// ======================================
// AMBIL DATA JENIS PELANGGARAN
// ======================================
$sql = "SELECT
            p.id,
            p.pelanggaran_kategori_id,
            pk.nama AS nama_kategori,
            p.kode,
            p.nama,
            p.poin,
            p.deskripsi,
            p.status_aktif,
            p.created_at,
            p.updated_at
        FROM t_pelanggaran p
        LEFT JOIN t_pelanggaran_kategori pk
            ON p.pelanggaran_kategori_id = pk.id
        ORDER BY p.nama ASC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Gagal mengambil data: " . htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Kelola Jenis Pelanggaran</title>

<!-- BOOTSTRAP 4 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<style>

body {
    background: #f4f6ff;
    font-family: Arial, sans-serif;
    color: #333;
}

.content {
    padding: 25px;
    min-height: 100vh;
}

.page-title {
    color: #202f4d;
    font-size: 25px;
    font-weight: bold;
}

.page-subtitle {
    color: #8995aa;
    font-size: 13px;
}

/* HEADER UNGU */
.header-page {
    background: linear-gradient(135deg, #5965d8, #9365df);
    color: white;
    padding: 28px 30px;
    border-radius: 12px;
    margin-bottom: 25px;
}

.header-page h4 {
    font-weight: bold;
}

/* CARD */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    margin-bottom: 20px;
}

.card-header {
    background: white;
    font-weight: bold;
    padding: 18px 22px;
    border-bottom: 1px solid #eee;
    color: #202f4d;
}

.card-body {
    padding: 25px;
}

/* TOMBOL */
.btn {
    border-radius: 7px;
    padding: 9px 17px;
    font-weight: 600;
}

.btn-primary {
    background: #625fd5;
    border-color: #625fd5;
}

.btn-primary:hover {
    background: #514dc0;
    border-color: #514dc0;
}

.btn-warning {
    color: white;
    background: #f0ad4e;
    border-color: #f0ad4e;
}

.btn-warning:hover {
    color: white;
    background: #dc9633;
}

.btn-danger {
    background: #e05260;
    border-color: #e05260;
}

/* TABEL */
.table {
    color: #424b60;
    font-size: 13px;
}

.table thead th {
    background: #f3f4fc;
    color: #424b60;
    border-top: none;
    border-bottom: 2px solid #e4e6f0;
    vertical-align: middle;
    white-space: nowrap;
}

.table tbody td {
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: #f8f8ff;
}

.badge {
    padding: 7px 10px;
    border-radius: 5px;
    font-size: 11px;
}

.badge-aktif {
    background: #d4f4e2;
    color: #16834a;
}

.badge-nonaktif {
    background: #ffe0e0;
    color: #c03939;
}

/* DATATABLES */
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #dce0eb;
    border-radius: 7px;
    padding: 7px 10px;
    outline: none;
}

.dataTables_wrapper .dataTables_length select {
    border: 1px solid #dce0eb;
    border-radius: 6px;
    padding: 5px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 5px !important;
}

.page-item.active .page-link {
    background: #625fd5;
    border-color: #625fd5;
}

.page-link {
    color: #625fd5;
}

.page-link:hover {
    color: #514dc0;
}

.aksi {
    display: flex;
    gap: 5px;
    white-space: nowrap;
}

.deskripsi {
    min-width: 150px;
    max-width: 230px;
}

@media(max-width:767px) {
    .content {
        padding: 15px;
    }

    .card-body {
        padding: 18px;
    }

    .header-page {
        padding: 22px;
    }
}

</style>

</head>

<body>

<div class="container-fluid">
<div class="row">

    <?php include 'layout/sidebar.php'; ?>

    <main class="col-md-9 col-lg-10 ml-sm-auto content">

        <!-- JUDUL HALAMAN -->
        <div class="mb-4">

            <h3 class="page-title">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Kelola Jenis Pelanggaran
            </h3>

            <p class="page-subtitle">
                Kelola data jenis pelanggaran siswa pada sistem sekolah.
            </p>

        </div>

        <!-- HEADER UNGU -->
        <div class="header-page">

            <span class="badge badge-light mb-2" style="color:#625fd5;">
                <i class="fas fa-list-alt"></i> DATA PELANGGARAN
            </span>

            <h4>
                <i class="fas fa-clipboard-list mr-2"></i>
                Data Jenis Pelanggaran
            </h4>

            <p class="mb-0">
                Lihat, tambah, edit, dan kelola jenis pelanggaran siswa.
            </p>

        </div>

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['pesan_sukses'])) { ?>

            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>

                <?= htmlspecialchars($_SESSION['pesan_sukses']); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>

            <?php unset($_SESSION['pesan_sukses']); ?>

        <?php } ?>

        <?php if (isset($_SESSION['pesan_error'])) { ?>

            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle mr-2"></i>

                <?= htmlspecialchars($_SESSION['pesan_error']); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>

            <?php unset($_SESSION['pesan_error']); ?>

        <?php } ?>

        <!-- TOMBOL AKSI -->
        <div class="mb-3 d-flex flex-wrap">

            <a href="dashboard.php" class="btn btn-secondary mr-2 mb-2">
                <i class="fas fa-arrow-left mr-1"></i>
                Kembali
            </a>

            <a href="tambah_jenis_pelanggaran.php"
               class="btn btn-primary mb-2">

                <i class="fas fa-plus mr-1"></i>
                Tambah Jenis Pelanggaran

            </a>

        </div>

        <!-- CARD TABEL -->
        <div class="card">

            <div class="card-header">
                <i class="fas fa-table mr-2"></i>
                Tabel Data Jenis Pelanggaran
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelPelanggaran"
                           class="table table-bordered table-striped table-hover w-100">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Kode</th>
                                <th>Nama Pelanggaran</th>
                                <th>Poin</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        while ($row = mysqli_fetch_assoc($hasil)) {
                        ?>

                            <tr>

                                <td><?= $no++; ?></td>

                                <td>
                                    <?= (int)$row['id']; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['kode'] ?? '-'); ?>
                                </td>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge badge-info">
                                        <?= (int)$row['poin']; ?> Poin
                                    </span>
                                </td>

                                <td class="deskripsi">
                                    <?= htmlspecialchars($row['deskripsi'] ?? '-'); ?>
                                </td>

                                <td>

                                    <?php if ((int)$row['status_aktif'] === 1) { ?>

                                        <span class="badge badge-aktif">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aktif
                                        </span>

                                    <?php } else { ?>

                                        <span class="badge badge-nonaktif">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Tidak Aktif
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>
                                    <?= htmlspecialchars($row['created_at'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['updated_at'] ?? '-'); ?>
                                </td>

                                <td>

                                    <div class="aksi">

                                        <a href="edit_jenis_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-warning btn-sm"
                                           title="Edit">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                        <a href="hapus_jenis_pelanggaran.php?id=<?= (int)$row['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?');">

                                            <i class="fas fa-trash"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="mt-4">
            <small class="text-muted">
                Sistem Pelanggaran Siswa © 2026
            </small>
        </div>

    </main>

</div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelPelanggaran').DataTable({

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada jenis pelanggaran",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [[4, 'asc']],

        columnDefs: [
            {
                targets: 10,
                orderable: false,
                searchable: false
            }
        ]

    });

});
</script>

</body>
</html>
