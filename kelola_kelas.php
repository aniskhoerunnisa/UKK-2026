
<?php
// kelola_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kelas ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kelola Kelas - Sistem Pelanggaran Siswa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        #tabelKelas {
            width: 100% !important;
        }

        #tabelKelas th,
        #tabelKelas td {
            vertical-align: middle;
        }

        .aksi {
            white-space: nowrap;
        }
    </style>
</head>

<body>

<div class="container-fluid mt-4 px-4">

    <!-- JUDUL -->
    <div class="d-flex justify-content-between
                align-items-center flex-wrap mb-4">
        <div>
            <h2>Kelola Kelas</h2>
            <p class="text-muted">
                Daftar data kelas yang terdaftar di sistem.
            </p>
        </div>

        <a href="tambah_kelas.php" class="btn btn-primary mb-3">
            + Tambah Kelas
        </a>
    </div>

    <p>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </p>

    <!-- TABEL KELAS -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">

                <table id="tabelKelas"
                       class="table table-bordered table-striped table-hover">

                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Status Aktif</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        if ($hasil && mysqli_num_rows($hasil) > 0) {
                            while ($row = mysqli_fetch_assoc($hasil)) {
                        ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($row['id'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['nama'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['tingkat'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['jurusan'] ?? '') ?>
                                </td>

                                <td>
                                    <?php if ($row['status_aktif'] == 1) { ?>
                                        <span class="badge badge-success">
                                            Aktif
                                        </span>
                                    <?php } else { ?>
                                        <span class="badge badge-danger">
                                            Tidak Aktif
                                        </span>
                                    <?php } ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['created_at'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($row['updated_at'] ?? '') ?>
                                </td>

                                <td class="aksi">
                                    <a href="edit_kelas.php?id=<?= (int)$row['id'] ?>"
                                       class="btn btn-info btn-sm">
                                        Edit
                                    </a>

                                    <a href="hapus_kelas.php?id=<?= (int)$row['id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data kelas ini?');">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <p class="text-muted mt-3">
        Sistem Pelanggaran Siswa &copy; <?= date('Y') ?>
    </p>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- KONFIGURASI DATATABLES -->
<script>
$(document).ready(function () {

    $('#tabelKelas').DataTable({
        pageLength: 10,

        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'Semua']
        ],

        searching: true,
        ordering: true,
        paging: true,
        info: true,
        autoWidth: false,

        // Urutkan berdasarkan nama kelas
        order: [[1, 'asc']],

        // Kolom aksi tidak bisa dicari atau diurutkan
        columnDefs: [
            {
                targets: 7,
                orderable: false,
                searchable: false
            }
        ],

        // Bahasa Indonesia
        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari data kelas...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data kelas tidak ditemukan',
            emptyTable: 'Belum ada data kelas',

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
