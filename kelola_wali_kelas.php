
<?php
// kelola_wali_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_wali_kelas";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Wali Kelas</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body { background: #f8f9fa; }
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
        main { padding: 25px !important; }
        .aksi { white-space: nowrap; }
    </style>
</head>

<body>
<div class="container-fluid">
<div class="row">

    <aside class="col-md-3 col-lg-2 sidebar p-3">
        <h4 class="text-white mb-4">My Website</h4>
        <ul class="nav nav-pills flex-column">
            <li class="nav-item mb-2">
                <a href="dashboard.php" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_siswa.php" class="nav-link">Kelola Siswa</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_guru.php" class="nav-link">Kelola Guru</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_wali_kelas.php" class="nav-link active">
                    Kelola Wali Kelas
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_kelas.php" class="nav-link">Kelola Kelas</a>
            </li>
            <li class="nav-item mb-2">
                <a href="kelola_tahun_ajaran.php" class="nav-link">
                    Kelola Tahun Ajaran
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="about_me.php" class="nav-link">About Me</a>
            </li>
            <hr class="bg-secondary">
            <li class="nav-item">
                <a href="logout.php" class="nav-link text-danger">Logout</a>
            </li>
        </ul>
    </aside>

    <main class="col-md-9 col-lg-10">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div>
                <h2>Kelola Wali Kelas</h2>
                <p class="text-muted">Daftar data wali kelas.</p>
            </div>
            <a href="tambah_wali_kelas.php" class="btn btn-primary mb-3">
                + Tambah Wali Kelas
            </a>
        </div>

        <a href="dashboard.php">Kembali ke Dashboard</a>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tabelWaliKelas"
                           class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nama Wali Kelas</th>
                                <th>NIP</th>
                                <th>Jenis Kelamin</th>
                                <th>Nomor HP</th>
                                <th>Alamat</th>
                                <th>Status Aktif</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['nama'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['nip'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['jenis_kelamin'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['no_hp'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['alamat'] ?? '') ?></td>
                                <td>
                                    <?php if (($row['status_aktif'] ?? 0) == 1): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="aksi">
                                    <a href="edit_wali_kelas.php?id=<?= (int)($row['id'] ?? 0) ?>"
                                       class="btn btn-info btn-sm">Edit</a>
                                    <a href="hapus_wali_kelas.php?id=<?= (int)($row['id'] ?? 0) ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelWaliKelas').DataTable({
        pageLength: 10,
        order: [],
        columnDefs: [{
            targets: 7,
            orderable: false,
            searchable: false
        }],
        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari data wali kelas...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data wali kelas tidak ditemukan',
            emptyTable: 'Belum ada data wali kelas',
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
