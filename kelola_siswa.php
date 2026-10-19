
<?php
// kelola_siswa.php
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

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .sidebar .nav-link {
            color: white;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        main {
            padding: 25px !important;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        #tabelSiswa {
            width: 100% !important;
        }

        #tabelSiswa th,
        #tabelSiswa td {
            vertical-align: middle;
        }

        .aksi {
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 sidebar p-3">
            <h4 class="text-white mb-4">My Website</h4>

            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link active">
                        Kelola Siswa
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link">
                        Kelola Guru
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link">
                        Kelola Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran.php" class="nav-link">
                        Kelola Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="about_me.php" class="nav-link">
                        About Me
                    </a>
                </li>

                <hr class="bg-secondary">

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10">

            <div class="d-flex justify-content-between
                        align-items-center flex-wrap mb-4">
                <div>
                    <h2>Kelola Siswa</h2>
                    <p class="text-muted">
                        Daftar data siswa yang terdaftar di sistem.
                    </p>
                </div>

                <a href="tambah_siswa.php"
                   class="btn btn-primary mb-3">
                    + Tambah Siswa
                </a>
            </div>

            <p>
                <a href="dashboard.php">Kembali ke Dashboard</a>
            </p>

            <!-- TABEL SISWA -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="tabelSiswa"
                               class="table table-bordered
                                      table-striped table-hover">

                            <thead class="thead-dark">
                                <tr>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Status Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($hasil &&
                                    mysqli_num_rows($hasil) > 0) {

                                    while ($row =
                                        mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nis'] ?? ''
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nisn'] ?? ''
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['nama'] ?? ''
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['jenis_kelamin'] ?? ''
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['tanggal_lahir'] ?? ''
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row['alamat'] ?? ''
                                            ) ?>
                                        </td>

                                        <td>
                                            <?php
                                            if ($row['status_aktif'] == 1) {
                                            ?>
                                                <span class="badge badge-success">
                                                    Aktif
                                                </span>
                                            <?php
                                            } else {
                                            ?>
                                                <span class="badge badge-danger">
                                                    Tidak Aktif
                                                </span>
                                            <?php
                                            }
                                            ?>
                                        </td>

                                        <td class="aksi">
                                            <a href="edit_siswa.php?id=<?= (int)$row['id'] ?>"
                                               class="btn btn-info btn-sm">
                                                Edit
                                            </a>

                                            <a href="hapus_siswa.php?id=<?= (int)$row['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus siswa ini?');">
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

            <p class="text-muted mt-3">
                Sistem Pelanggaran Siswa &copy; <?= date('Y') ?>
            </p>

        </main>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- KONFIGURASI DATATABLES -->
<script>
$(document).ready(function () {

    $('#tabelSiswa').DataTable({
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

        // Urutkan berdasarkan nama
        order: [[2, 'asc']],

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
