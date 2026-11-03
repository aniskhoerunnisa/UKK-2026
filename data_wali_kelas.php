
<?php
// data_wali_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT
            w.*,
            k.nama AS nama_kelas,
            g.nama AS nama_guru
        FROM t_wali_kelas w
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY g.nama ASC";

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

    <title>Data Wali Kelas - Sistem Pelanggaran Siswa</title>

    <!-- Bootstrap 4.6.2 -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- DataTables Bootstrap 4 -->
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background-color: #f4f6fa;
            font-family: Arial, sans-serif;
        }

        main {
            min-height: 100vh;
        }

        .content-header {
            background: white;
            padding: 22px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: white;
            font-weight: bold;
            padding: 18px;
        }

        .card-body {
            padding: 22px;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .btn {
            border-radius: 5px;
        }

        @media(max-width: 768px) {
            main {
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <!-- HEADER -->
            <div class="content-header">
                <h3 class="mb-2">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Data Wali Kelas
                </h3>

                <p class="text-muted mb-3">
                    Kelola data penempatan guru sebagai wali kelas.
                </p>

                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke Dashboard
                </a>

                <a href="tambah_wali_kelas.php" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Wali Kelas
                </a>
            </div>

            <!-- TABEL DATA -->
            <div class="card">

                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Tabel Data Wali Kelas
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelWaliKelas"
                            class="table table-bordered table-striped table-hover">

                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Guru</th>
                                    <th>Kelas</th>
                                    <th>Status Aktif</th>
                                    <th>Dibuat</th>
                                    <th>Diubah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($row['id']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['nama_guru'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php if ($row['status_aktif'] == 1) { ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['created_at'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['updated_at'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <a href="edit_wali_kelas.php?id=<?php echo urlencode($row['id']); ?>"
                                            class="btn btn-info btn-sm mb-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="hapus_wali_kelas.php?id=<?php echo urlencode($row['id']); ?>"
                                            class="btn btn-danger btn-sm mb-1"
                                            onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<!-- Konfigurasi DataTables -->
<script>
$(document).ready(function () {

    $('#tabelWaliKelas').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        ordering: true,

        searching: true,

        language: {
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data wali kelas tidak ditemukan",
            emptyTable: "Belum ada data wali kelas",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Cari wali kelas:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }

    });

});
</script>

</body>
</html>
