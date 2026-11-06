
<?php
// kelola_kategori_pelanggan.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kategori_pelanggan ORDER BY nama_kategori ASC";
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

    <title>Kategori Jenis Pelanggan</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6fa;
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
            padding: 18px;
            font-weight: bold;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
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

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="content-header">

                <h3>
                    <i class="fas fa-users mr-2"></i>
                    Kategori Jenis Pelanggan
                </h3>

                <p class="text-muted">
                    Kelola data kategori jenis pelanggan.
                </p>

                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali
                </a>

                <a href="tambah_kategori_pelanggan.php" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah Kategori
                </a>

            </div>

            <div class="card">

                <div class="card-header">
                    <i class="fas fa-table mr-2"></i>
                    Data Kategori Pelanggan
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelKategori"
                            class="table table-bordered table-striped table-hover">

                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Status Aktif</th>
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
                                        <?php echo htmlspecialchars($row['nama_kategori']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($row['deskripsi'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?php if ($row['status_aktif'] == 1) { ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <a href="edit_kategori_pelanggan.php?id=<?php echo urlencode($row['id']); ?>"
                                            class="btn btn-info btn-sm mb-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <a href="hapus_kategori_pelanggan.php?id=<?php echo urlencode($row['id']); ?>"
                                            class="btn btn-danger btn-sm mb-1"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?');">
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

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelKategori').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        ordering: true,

        searching: true,

        language: {
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Kategori tidak ditemukan",
            emptyTable: "Belum ada data kategori pelanggan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            search: "Cari kategori:",
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
