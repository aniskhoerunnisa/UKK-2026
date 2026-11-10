<?php
// kelola_pelanggaran_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Mengambil data kategori pelanggaran
$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Kategori Pelanggaran</title>

    <!-- Bootstrap 4.6.2 -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- DataTables Bootstrap 4 -->
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #202b46;
        }

        .main-content {
            margin-left: 200px;
            padding: 25px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 23px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #71809f;
            margin-bottom: 22px;
        }

        .banner {
            background: linear-gradient(110deg, #5368ed, #8260e9);
            padding: 25px;
            border-radius: 15px;
            color: white;
            margin-bottom: 22px;
            box-shadow: 0 8px 20px rgba(83,104,237,.15);
        }

        .banner-label {
            display: inline-block;
            background: rgba(255,255,255,.2);
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 10px;
            margin-bottom: 12px;
        }

        .banner h5 {
            font-weight: bold;
            font-size: 19px;
        }

        .banner p {
            margin-bottom: 0;
            font-size: 12px;
            line-height: 1.7;
        }

        .section-title {
            font-size: 17px;
            font-weight: bold;
        }

        .section-desc {
            color: #71809f;
            font-size: 12px;
        }

        .btn-tambah {
            background: linear-gradient(110deg, #5368ed, #8260e9);
            color: white !important;
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 12px;
            font-weight: bold;
        }

        .table-card {
            background: white;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(32,43,70,.05);
        }

        .table-heading {
            font-size: 15px;
            font-weight: bold;
        }

        #tabelKategori {
            width: 100% !important;
            font-size: 11px;
        }

        #tabelKategori thead th {
            background: #202b46;
            color: white;
            white-space: nowrap;
            padding: 11px 8px;
        }

        #tabelKategori tbody td {
            vertical-align: middle;
            padding: 10px 8px;
        }

        .badge-status {
            background: #20a64b;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge-nonaktif {
            background: #dc3545;
        }

        .aksi {
            display: flex;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-edit {
            background: #ffc107;
            color: #202020 !important;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 10px;
        }

        .btn-hapus {
            background: #dc3545;
            color: white !important;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 10px;
        }

        .footer-text {
            margin-top: 20px;
            color: #71809f;
            font-size: 11px;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN -->
    <main class="main-content">

        <h1 class="page-title">
            Kelola Kategori Pelanggaran
            <span style="color:#8260e9;">.</span>
        </h1>

        <p class="page-subtitle">
            Kelola dan pantau kategori jenis pelanggaran siswa.
        </p>

        <!-- BANNER -->
        <div class="banner">

            <div class="banner-label">
                <i class="fas fa-exclamation-triangle"></i>
                KATEGORI PELANGGARAN
            </div>

            <h5>
                <i class="fas fa-list-alt"></i>
                Manajemen Kategori Pelanggaran
            </h5>

            <p>
                Kelola data kategori pelanggaran siswa dengan mudah,
                praktis, dan terstruktur melalui halaman ini.
            </p>

        </div>

        <!-- JUDUL DAN TOMBOL -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

            <div>
                <h5 class="section-title">Daftar Kategori Pelanggaran</h5>
                <p class="section-desc">
                    Seluruh data kategori pelanggaran yang tersimpan.
                </p>
            </div>

            <a href="tambah_pelanggaran_kategori.php"
                class="btn btn-tambah mb-3">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>

        </div>

        <!-- NOTIFIKASI -->
        <?php if (isset($_SESSION['pesan_sukses'])) { ?>

            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['pesan_sukses']); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>

            <?php unset($_SESSION['pesan_sukses']); ?>

        <?php } ?>

        <?php if (isset($_SESSION['pesan_error'])) { ?>

            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['pesan_error']); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>

            <?php unset($_SESSION['pesan_error']); ?>

        <?php } ?>

        <!-- TABEL -->
        <div class="table-card">

            <h6 class="table-heading">
                <i class="fas fa-list-ul" style="color:#5965ed;"></i>
                Tabel Data Kategori Pelanggaran
            </h6>

            <p class="section-desc">
                Gunakan pencarian untuk menemukan data kategori.
            </p>

            <div class="table-responsive">

                <table id="tabelKategori"
                    class="table table-striped table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Status Aktif</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                            <tr>

                                <td></td>

                                <td>
                                    <?= htmlspecialchars($row['nama'] ?? ''); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['deskripsi'] ?? ''); ?>
                                </td>

                                <td>
                                    <?php if ((int)($row['status_aktif'] ?? 0) === 1) { ?>

                                        <span class="badge-status">Aktif</span>

                                    <?php } else { ?>

                                        <span class="badge-status badge-nonaktif">
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

                                        <a href="edit_pelanggaran_kategori.php?id=<?= (int)$row['id']; ?>"
                                            class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="hapus_pelanggaran_kategori.php?id=<?= (int)$row['id']; ?>"
                                            class="btn-hapus"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

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

            $('#tabelKategori').DataTable({

                pageLength: 10,

                lengthMenu: [5, 10, 25, 50, 100],

                order: [[1, 'desc']],

                columnDefs: [
                    {
                        targets: [0, 6],
                        orderable: false,
                        searchable: false
                    }
                ],

                language: {
                    search: "Cari:",
                    searchPlaceholder: "Cari kategori...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data kategori",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Data tidak ditemukan",
                    emptyTable: "Belum ada data kategori pelanggaran",

                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                },

                drawCallback: function() {

                    var api = this.api();
                    var info = api.page.info();

                    api.column(0, {
                        page: 'current'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = info.start + i + 1;
                    });

                }

            });

        });
    </script>

</body>
</html>
```