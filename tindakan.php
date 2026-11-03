```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$sql = "SELECT * FROM t_pelanggan WHERE 1=1";

if ($tanggal_mulai !== '' && $tanggal_akhir !== '') {
    $mulai = mysqli_real_escape_string($koneksi, $tanggal_mulai);
    $akhir = mysqli_real_escape_string($koneksi, $tanggal_akhir);

    $sql .= " AND DATE(created_at) BETWEEN '$mulai' AND '$akhir'";
}

$sql .= " ORDER BY created_at DESC";

$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pelanggan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .content {
            padding: 25px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);
        }

        .card-header {
            background: #fff;
            font-weight: bold;
        }

        .btn-primary {
            background: #344767;
            border-color: #344767;
        }

        @media print {
            .sidebar,
            .filter-area,
            .no-print {
                display: none !important;
            }

            main {
                width: 100% !important;
                max-width: 100% !important;
            }

            body {
                background: white;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 ml-sm-auto content">

            <div class="mb-4">
                <h3>Laporan Pelanggan</h3>
                <p class="text-muted">Lihat dan cetak laporan data pelanggan.</p>
            </div>

            <div class="card mb-4 filter-area">
                <div class="card-header">
                    <i class="fas fa-filter mr-2"></i> Filter Laporan
                </div>

                <div class="card-body">
                    <form method="GET" class="form-row align-items-end">

                        <div class="form-group col-md-4">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tanggal_mulai); ?>">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tanggal_akhir); ?>">
                        </div>

                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tampilkan
                            </button>

                            <a href="laporan.php" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-alt mr-2"></i> Data Laporan Pelanggan</span>

                    <button onclick="window.print()" class="btn btn-success btn-sm no-print">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table id="tabelLaporan" class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Data Lainnya</th>
                                    <th>Tanggal Dibuat</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($data['nama'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($data['alamat'] ?? '-'); ?></td>
                                        <td>
                                            <?= !empty($data['created_at'])
                                                ? date('d-m-Y', strtotime($data['created_at']))
                                                : '-'; ?>
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabelLaporan').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

</body>
</html>
```