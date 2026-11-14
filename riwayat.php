
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$cari = trim($_GET['cari'] ?? '');
$status = trim($_GET['status'] ?? '');

$sql = "SELECT * FROM t_pelanggaran_siswa WHERE 1=1";

if ($cari !== '') {
    $cari_aman = mysqli_real_escape_string($koneksi, $cari);

    $sql .= " AND (
        nama_siswa LIKE '%$cari_aman%'
        OR nama_pelanggaran LIKE '%$cari_aman%'
        OR nama_kelas LIKE '%$cari_aman%'
        OR nama_guru LIKE '%$cari_aman%'
    )";
}

if ($status !== '') {
    $status_aman = mysqli_real_escape_string($koneksi, $status);
    $sql .= " AND status = '$status_aman'";
}

$sql .= " ORDER BY tanggal DESC, id DESC";

$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}

$jumlah = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Riwayat Pelanggaran - MyWebsite</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    body {
        background: #f4f6ff;
        font-family: Arial, sans-serif;
        overflow-x: hidden;
    }

    /*
       Sidebar menggunakan posisi fixed.
       Sisakan ruang untuk sidebar di sebelah kiri.
    */
    main.content-area {
        margin-left: 220px;
        width: calc(100% - 220px);
        max-width: calc(100% - 220px);
        flex: 0 0 calc(100% - 220px);
        min-width: 0;
        padding: 25px !important;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        width: 100%;
        min-width: 0;
    }

    .page-title {
        color: #202b46;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #777;
        font-size: 14px;
    }

    .summary-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    #tabelRiwayat {
        width: 100% !important;
        min-width: 1100px;
    }

    #tabelRiwayat thead th {
        background: #202b46;
        color: white;
        white-space: nowrap;
        font-size: 13px;
        padding: 13px;
    }

    #tabelRiwayat tbody td {
        font-size: 13px;
        padding: 12px;
        vertical-align: middle;
    }

    .filter-card,
    .table-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
    }

    .card-header-custom {
        background: #202b46;
        color: white;
        padding: 14px 18px;
        border-radius: 10px 10px 0 0;
    }

    @media (max-width: 767px) {
        main.content-area {
            margin-left: 0;
            width: 100%;
            max-width: 100%;
            flex-basis: 100%;
            padding: 15px !important;
        }

        .content-card {
            padding: 15px;
        }
    }
</style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <main class="content-area">

            <div class="content-card">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h4 class="page-title">
                            <i class="fas fa-history me-2"></i>
                            Riwayat Pelanggaran Siswa
                        </h4>
                        <p class="page-subtitle mb-0">
                            Daftar catatan pelanggaran siswa yang telah dicatat.
                        </p>
                    </div>

                    <a href="dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>

                <hr>

                <div class="card summary-card mb-4">
                    <div class="card-body">
                        <div class="text-muted">Jumlah Riwayat Ditampilkan</div>
                        <h3 class="fw-bold text-primary mb-0">
                            <?= $jumlah; ?> data
                        </h3>
                    </div>
                </div>

                <div class="card filter-card mb-4">
                    <div class="card-header-custom">
                        <h6 class="mb-0">
                            <i class="fas fa-search me-2"></i>
                            Pencarian dan Filter Riwayat
                        </h6>
                    </div>

                    <div class="card-body">
                        <form method="GET" action="riwayat.php">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Cari Riwayat</label>
                                    <input type="text"
                                           name="cari"
                                           class="form-control"
                                           placeholder="Nama siswa, pelanggaran, kelas, guru..."
                                           value="<?= htmlspecialchars($cari); ?>">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Filter Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="belum" <?= $status == 'belum' ? 'selected' : ''; ?>>
                                            Belum Ditindak
                                        </option>
                                        <option value="selesai" <?= $status == 'selesai' ? 'selected' : ''; ?>>
                                            Selesai
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Cari
                                    </button>

                                    <a href="riwayat.php" class="btn btn-secondary">
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="card table-card">
                    <div class="card-header-custom">
                        <h6 class="mb-0">
                            <i class="fas fa-table me-2"></i>
                            Data Riwayat Pelanggaran
                        </h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="tabelRiwayat"
                                   class="table table-striped table-bordered table-hover align-middle">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Jenis Pelanggaran</th>
                                        <th>Poin</th>
                                        <th>Guru Pencatat</th>
                                        <th>Keterangan</th>
                                        <th>Tindakan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)):
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= htmlspecialchars($data['tanggal'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_kelas'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_pelanggaran'] ?? '-'); ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">
                                                <?= htmlspecialchars((string)($data['poin'] ?? 0)); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_guru'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['keterangan'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['tindakan'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?php
                                            $st = strtolower(trim($data['status'] ?? ''));

                                            if ($st == 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } elseif ($st == 'belum' || $st == 'belum ditindak') {
                                                echo '<span class="badge bg-danger">Belum Ditindak</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">'
                                                    . htmlspecialchars($data['status'] ?? '-')
                                                    . '</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelRiwayat').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        searching: true,
        ordering: true,
        paging: true,
        scrollX: true,
        order: [[1, 'desc']],

        columnDefs: [{
            orderable: false,
            searchable: false,
            targets: 0
        }],

        drawCallback: function () {
            let api = this.api();
            let awal = api.page.info().start;

            api.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = awal + i + 1;
            });
        },

        language: {
            search: "Cari di tabel:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data riwayat",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Riwayat pelanggaran tidak ditemukan",
            emptyTable: "Belum ada riwayat pelanggaran",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });
});
</script>

</body>
</html>
