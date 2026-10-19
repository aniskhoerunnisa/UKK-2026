<?php
// kelola_guru.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_guru ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Guru</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f5f8fc;
            font-family: Arial, sans-serif;
            color: #334155;
        }

        main {
            min-height: 100vh;
        }

        .page-title {
            color: #243b53;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .page-subtitle {
            color: #64748b;
            margin-bottom: 25px;
        }

        .content-card {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
            border: 1px solid #e8eef5;
        }

        .table {
            margin-top: 15px !important;
        }

        .table thead th {
            background-color: #e7f1ff;
            color: #24476b;
            white-space: nowrap;
            vertical-align: middle;
            font-size: 14px;
        }

        .table tbody td {
            vertical-align: middle;
            font-size: 14px;
        }

        .table tbody tr:hover td {
            background-color: #f8fbff;
        }

        .btn {
            border-radius: 7px;
            font-size: 13px;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            white-space: nowrap;
        }

        .status-aktif {
            display: inline-block;
            background-color: #dcfce7;
            color: #166534;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-nonaktif {
            display: inline-block;
            background-color: #e2e8f0;
            color: #475569;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 6px 10px;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .dt-info {
            color: #64748b;
            font-size: 13px;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .content-card {
                padding: 15px;
            }

            main {
                padding: 16px !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <!-- JUDUL DAN TOMBOL -->
            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3 mb-4">

                <div>
                    <h1 class="page-title">Kelola Guru</h1>
                    <p class="page-subtitle mb-0">
                        Kelola data guru yang terdaftar di sistem.
                    </p>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="dashboard.php" class="btn btn-outline-secondary">
                        Kembali ke Dashboard
                    </a>

                    <a href="tambah_guru.php" class="btn btn-primary">
                        + Tambah Guru
                    </a>
                </div>

            </div>

            <!-- CARD TABEL -->
            <div class="content-card">

                <div class="mb-3">
                    <h5 class="fw-bold mb-0" style="color: #243b53;">
                        Data Guru
                    </h5>
                    <small class="text-muted">
                        Daftar guru yang tersimpan dalam sistem.
                    </small>
                </div>

                <div class="table-responsive">

                    <table id="tabelGuru"
                           class="table table-bordered table-hover align-middle w-100">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NIP</th>
                                <th>Nama Guru</th>
                                <th>Email</th>
                                <th>Status Aktif</th>
                                <th>Dibuat</th>
                                <th>Diubah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($hasil && mysqli_num_rows($hasil) > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars((string) $row['id'], ENT_QUOTES, 'UTF-8'); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['nip'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        </td>

                                        <td class="fw-semibold">
                                            <?php echo htmlspecialchars($row['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                        </td>

                                        <td>
                                            <?php if ((int) $row['status_aktif'] === 1) { ?>
                                                <span class="status-aktif">
                                                    Aktif
                                                </span>
                                            <?php } else { ?>
                                                <span class="status-nonaktif">
                                                    Tidak Aktif
                                                </span>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo htmlspecialchars(
                                                $row['created_at'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo htmlspecialchars(
                                                $row['updated_at'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            );
                                            ?>
                                        </td>

                                        <td>
                                            <div class="action-buttons">
                                                <a href="edit_guru.php?id=<?php echo urlencode((string) $row['id']); ?>"
                                                   class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>

                                                <a href="hapus_guru.php?id=<?php echo urlencode((string) $row['id']); ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Yakin ingin menghapus data guru ini?');">
                                                    Hapus
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                <?php } ?>
                            <?php } ?>
                        </tbody>

                    </table>

                </div>
            </div>

        </main>

    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelGuru').DataTable({
        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        order: [[2, 'asc']],

        columnDefs: [
            {
                targets: 7,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari nama, NIP, email...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data guru',
            infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data guru',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data guru tidak ditemukan',
            emptyTable: 'Belum ada data guru',

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