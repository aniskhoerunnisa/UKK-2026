
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_guru ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Guru - Sistem Pelanggaran Siswa</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }

        .sidebar .nav-link {
            color: white;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #007bff;
            color: white;
        }

        main {
            padding: 25px !important;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        #tabelGuru {
            width: 100% !important;
        }

        #tabelGuru th,
        #tabelGuru td {
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
        <div class="col-md-3 col-lg-2 sidebar p-3">
            <h4 class="text-white mb-4">My Website</h4>

            <ul class="nav nav-pills flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_siswa.php" class="nav-link">Kelola Siswa</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_guru.php" class="nav-link active">Kelola Guru</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_kelas.php" class="nav-link">Kelola Kelas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="kelola_tahun_ajaran" class="nav-link">Kelola Tahun Ajaran</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="about_me.php" class="nav-link">About Me</a>
                </li>
                <hr class="bg-secondary">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">Logout</a>
                </li>
            </ul>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10">

            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                <div>
                    <h2>Kelola Guru</h2>
                    <p class="text-muted">Daftar data guru yang terdaftar di sistem.</p>
                </div>

                <a href="tambah_guru.php" class="btn btn-primary mb-3">
                    + Tambah Guru
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="tabelGuru" class="table table-bordered table-striped table-hover">

                            <thead class="thead-dark">
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Status Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if ($hasil && mysqli_num_rows($hasil) > 0) {
                                    while ($row = mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['nip'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                                        <td>
                                            <?php if ($row['status_aktif'] == 1) { ?>
                                                <span class="badge badge-success">Aktif</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            <?php } ?>
                                        </td>
                                        <td class="aksi">
                                            <a href="edit_guru.php?id=<?= (int)$row['id'] ?>" class="btn btn-info btn-sm">Edit</a>

                                            <a href="hapus_guru.php?id=<?= (int)$row['id'] ?>"
                                               class="btn btn-secondary btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus guru ini?');">Hapus</a>
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

<!-- JAVASCRIPT: JQUERY HARUS PALING ATAS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- AKTIFKAN DATATABLES -->
<script>
$(document).ready(function () {
    $('#tabelGuru').DataTable({
        pageLength: 10,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'Semua']
        ],
        ordering: true,
        searching: true,
        paging: true,
        info: true,
        autoWidth: false,
        order: [[1, 'asc']],

        columnDefs: [
            {
                targets: 4,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari data guru...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
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
