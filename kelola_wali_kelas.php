```php
<?php
// kelola_wali_kelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

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
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas</title>

    <!-- Bootstrap 4.6.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- DataTables Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #202b46;
        }

        .main-content {
            margin-left: 200px;
            padding: 20px;
            min-height: 100vh;
        }

        .page-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #202b46;
        }

        .page-subtitle {
            color: #687799;
            font-size: 12px;
            margin-bottom: 17px;
        }

        .banner {
            background: linear-gradient(110deg, #5368f2, #8057e9);
            padding: 22px;
            border-radius: 14px;
            color: white;
            margin-bottom: 17px;
            box-shadow: 0 7px 18px rgba(83, 104, 242, 0.12);
        }

        .banner small {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 10px;
            margin-bottom: 8px;
        }

        .banner h5 {
            font-weight: bold;
            font-size: 17px;
            margin-bottom: 7px;
        }

        .banner p {
            margin: 0;
            font-size: 11px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .section-desc {
            font-size: 12px;
            color: #687799;
        }

        .btn-tambah {
            background: linear-gradient(110deg, #5368f2, #8057e9);
            color: white !important;
            border: none;
            border-radius: 8px;
            padding: 8px 13px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-tambah:hover {
            opacity: 0.9;
        }

        .table-card {
            background: white;
            padding: 16px;
            border-radius: 13px;
            box-shadow: 0 5px 18px rgba(32, 43, 70, 0.05);
            margin-top: 18px;
        }

        .table-heading {
            font-size: 14px;
            font-weight: bold;
            color: #202b46;
            margin-bottom: 5px;
        }

        .table-description {
            color: #687799;
            font-size: 11px;
            margin-bottom: 15px;
        }

        #tabelWaliKelas {
            width: 100% !important;
            font-size: 10px;
            color: #344563;
        }

        #tabelWaliKelas thead th {
            background: #202b46;
            color: white;
            border-color: #344563;
            white-space: nowrap;
            padding: 10px 8px;
            vertical-align: middle;
        }

        #tabelWaliKelas tbody td {
            padding: 9px 8px;
            vertical-align: middle;
        }

        .badge-status {
            background: #20a64b;
            color: white;
            border-radius: 20px;
            padding: 4px 9px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-nonaktif {
            background: #e63946;
        }

        .btn-edit {
            background: #17a2b8;
            color: white !important;
            font-size: 10px;
            padding: 5px 8px;
            border-radius: 5px;
        }

        .btn-hapus {
            background: #e63946;
            color: white !important;
            font-size: 10px;
            padding: 5px 8px;
            border-radius: 5px;
        }

        .aksi {
            display: flex;
            gap: 5px;
            white-space: nowrap;
        }

        .dataTables_wrapper {
            font-size: 10px;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dce2f0;
            border-radius: 5px;
            padding: 3px 7px;
            font-size: 10px;
        }

        .dataTables_wrapper .page-item.active .page-link {
            background: #5965ed;
            border-color: #5965ed;
        }

        .dataTables_wrapper .page-link {
            color: #344563;
            font-size: 10px;
        }

        .footer-text {
            margin-top: 18px;
            font-size: 10px;
            color: #7182a5;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .banner {
                padding: 17px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- KONTEN UTAMA -->
    <main class="main-content">

        <h1 class="page-title">
            Kelola Wali Kelas <span style="color:#8057e9;">.</span>
        </h1>

        <p class="page-subtitle">
            Kelola dan pantau seluruh data wali kelas di sekolah ✨
        </p>

        <!-- BANNER -->
        <div class="banner">
            <small><i class="fas fa-user-tie"></i> DATA WALI KELAS</small>

            <h5>
                <i class="fas fa-chalkboard-teacher"></i>
                Manajemen Data Wali Kelas
            </h5>

            <p>
                Halaman untuk melihat, mencari, menambahkan, mengubah,
                dan menghapus data wali kelas.
            </p>
        </div>

        <!-- JUDUL DAFTAR -->
        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>
                <h5 class="section-title">Daftar Wali Kelas</h5>
                <p class="section-desc">
                    Data wali kelas yang terdaftar dalam sistem.
                </p>
            </div>

            <a href="tambah_wali_kelas.php" class="btn btn-tambah mb-3">
                <i class="fas fa-plus"></i> Tambah Wali Kelas
            </a>

        </div>

        <!-- TABEL -->
        <div class="table-card">

            <h6 class="table-heading">
                <i class="fas fa-list-ul" style="color:#5965ed;"></i>
                Tabel Data Wali Kelas
            </h6>

            <p class="table-description">
                Gunakan kolom pencarian untuk menemukan data wali kelas.
            </p>

            <div class="table-responsive">

                <table id="tabelWaliKelas"
                    class="table table-striped table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran ID</th>
                            <th>Kelas</th>
                            <th>Guru</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                            <tr>
                                <td></td>

                                <td>
                                    <?= htmlspecialchars((string)($row['tahun_ajaran_id'] ?? '')) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama_guru'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tanggal_mulai'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tanggal_selesai'] ?? '-') ?>
                                </td>

                                <td>
                                    <?php if ((int)($row['status_aktif'] ?? 0) === 1) { ?>
                                        <span class="badge-status">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge-status badge-nonaktif">Tidak Aktif</span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <div class="aksi">

                                        <a href="edit_wali_kelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                            class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="hapus_wali_kelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                            class="btn-hapus"
                                            onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">
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

    <!-- DATATABLES -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#tabelWaliKelas').DataTable({

                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                order: [],

                columnDefs: [{
                    targets: [0, 7],
                    orderable: false,
                    searchable: false
                }],

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