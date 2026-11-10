<?php
// laporan.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Export Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel') {
    $sqlExcel = "
        SELECT s.nis, s.nama AS nama_siswa, p.kode,
               p.nama AS nama_pelanggaran, p.poin
        FROM t_pelanggaran_siswa ps
        JOIN t_siswa s ON ps.siswa_id = s.id
        JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
        ORDER BY ps.id DESC
    ";

    $dataExcel = mysqli_query($koneksi, $sqlExcel);
    if (!$dataExcel) {
        die('Gagal mengambil data: ' . htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
    }

    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename=laporan_pelanggaran.xls');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "\xEF\xBB\xBF";
    echo '<table border="1">';
    echo '<tr><th>No</th><th>NIS</th><th>Nama Siswa</th><th>Kode Pelanggaran</th><th>Jenis Pelanggaran</th><th>Poin</th></tr>';

    $no = 1;
    while ($row = mysqli_fetch_assoc($dataExcel)) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . htmlspecialchars((string)$row['nis'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars((string)$row['nama_siswa'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars((string)($row['kode'] ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars((string)$row['nama_pelanggaran'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . (int)$row['poin'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    mysqli_free_result($dataExcel);
    exit;
}

// Data laporan
$sql = "
    SELECT ps.id, s.nis, s.nama AS nama_siswa, p.kode,
           p.nama AS nama_pelanggaran, p.poin
    FROM t_pelanggaran_siswa ps
    JOIN t_siswa s ON ps.siswa_id = s.id
    JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
    ORDER BY ps.id DESC
";
$hasil = mysqli_query($koneksi, $sql);
if (!$hasil) {
    die('Gagal mengambil laporan: ' . htmlspecialchars(mysqli_error($koneksi), ENT_QUOTES, 'UTF-8'));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f4f5fb;
            color: #343a50;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        /* Konten tetap sejajar dengan sidebar dan tidak turun ke baris baru */
        .container-fluid { padding: 0; }
        .page-row {
            display: flex;
            flex-wrap: nowrap;
            align-items: stretch;
            min-height: 100vh;
            margin: 0;
        }
        main.page-content {
            flex: 1 1 auto;
            min-width: 0;
            width: auto;
            padding: 30px !important;
        }

        .page-heading {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 26px 30px;
            margin-bottom: 22px;
            color: #fff;
            border-radius: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 6px 18px rgba(102, 126, 234, .20);
        }
        .page-heading .heading-icon {
            display: grid;
            place-items: center;
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 14px;
            background: rgba(255,255,255,.18);
            font-size: 23px;
        }
        .page-heading h1 {
            margin: 0 0 5px;
            font-size: 25px;
            font-weight: 750;
        }
        .page-heading p {
            margin: 0;
            color: rgba(255,255,255,.9);
            font-size: 13px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            padding: 9px 14px;
            color: #454b65;
            background: #e9eaf3;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .back-link:hover { color: #343a50; background: #dedfee; }

        .report-card {
            overflow: hidden;
            border: 0;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 4px 16px rgba(40,45,80,.06);
        }
        .report-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            padding: 20px 24px;
            background: #fff;
            border-bottom: 1px solid #edf0f6;
        }
        .report-card .card-header h5 {
            margin: 0;
            color: #343a60;
            font-size: 17px;
            font-weight: 700;
        }
        .report-card .card-body { padding: 24px; }

        .btn-purple {
            color: #fff;
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            font-weight: 600;
        }
        .btn-purple:hover { color: #fff; opacity: .92; }
        .btn-export, .btn-print {
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .table-responsive { width: 100%; }
        #tabelLaporan {
            width: 100% !important;
            margin-top: 12px !important;
            border-color: #edf0f6;
        }
        #tabelLaporan thead th {
            padding: 14px 12px;
            color: #fff;
            background: #273451;
            border-color: #273451;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }
        #tabelLaporan tbody td {
            padding: 13px 12px;
            color: #50556b;
            font-size: 13px;
            vertical-align: middle;
        }
        #tabelLaporan tbody tr:hover { background: #f7f5ff; }
        .point-badge {
            display: inline-block;
            min-width: 38px;
            padding: 5px 10px;
            color: #7041a1;
            background: #f0e8fa;
            border-radius: 20px;
            text-align: center;
            font-weight: 700;
        }
        .dataTables_wrapper { font-size: 13px; }
        .dt-search input, .dt-length select {
            border: 1px solid #dfe2ed !important;
            border-radius: 7px !important;
            padding: 6px 9px !important;
            outline: none;
        }
        .dt-search input:focus, .dt-length select:focus {
            border-color: #8b70c5 !important;
            box-shadow: 0 0 0 3px rgba(118,75,162,.10);
        }
        .page-link { color: #764ba2; }
        .active > .page-link, .page-link.active {
            color: #fff !important;
            background: #764ba2 !important;
            border-color: #764ba2 !important;
        }
        .dt-info { color: #74798d; }

        @media (max-width: 768px) {
            main.page-content { padding: 16px !important; }
            .page-heading { padding: 20px; }
            .page-heading h1 { font-size: 21px; }
            .report-card .card-header,
            .report-card .card-body { padding: 16px; }
            .report-card .card-header { align-items: flex-start; }
            .page-row { flex-wrap: wrap; }
            main.page-content { flex-basis: 100%; }
        }

        @media print {
            body { background: #fff !important; font-size: 11px; }
            .sidebar, nav, .no-print,
            .dt-search, .dt-length, .dt-info, .dt-paging { display: none !important; }
            .page-row, .container-fluid { display: block !important; width: 100% !important; margin: 0 !important; }
            main.page-content { width: 100% !important; padding: 0 !important; }
            .page-heading { padding: 0 0 14px !important; color: #000 !important; background: #fff !important; box-shadow: none !important; }
            .page-heading .heading-icon, .page-heading p, .back-link { display: none !important; }
            .page-heading h1 { color: #000 !important; font-size: 18px; }
            .report-card { box-shadow: none !important; }
            .report-card .card-header { padding: 0 0 10px !important; }
            .report-card .card-body { padding: 0 !important; }
            .table-responsive { overflow: visible !important; }
            #tabelLaporan { width: 100% !important; font-size: 9px; border-collapse: collapse !important; }
            #tabelLaporan th, #tabelLaporan td {
                padding: 5px !important;
                color: #000 !important;
                background: #fff !important;
                border: 1px solid #000 !important;
            }
            #tabelLaporan thead th { color: #000 !important; }
            .point-badge { padding: 0; color: #000; background: #fff; }
            @page { size: landscape; margin: 1.5cm; }
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row page-row">
        <?php include 'layout/sidebar.php'; ?>

        <main class="page-content">
            <div class="page-heading">
                <div class="heading-icon"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <h1>Laporan Pelanggaran Siswa</h1>
                    <p>Kelola dan lihat rekap data pelanggaran siswa.</p>
                </div>
            </div>

            <a href="dashboard.php" class="back-link no-print">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="card report-card">
                <div class="card-header">
                    <h5><i class="fa-solid fa-table-list text-primary me-2"></i>Data Laporan Pelanggaran</h5>
                    <div class="d-flex flex-wrap gap-2 no-print">
                        <button type="button" onclick="cetakPDF()" class="btn btn-danger btn-print">
                            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                        </button>
                        <a href="laporan.php?export=excel" class="btn btn-success btn-export">
                            <i class="fa-solid fa-file-excel me-1"></i> Export Excel
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelLaporan" class="table table-striped table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kode Pelanggaran</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars((string)$row['nis'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars((string)$row['nama_siswa'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars((string)($row['kode'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= htmlspecialchars((string)$row['nama_pelanggaran'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><span class="point-badge"><?= (int)$row['poin']; ?></span></td>
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script>
let panjangHalamanSebelumCetak = 10;

$(document).ready(function () {
    $('#tabelLaporan').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada laporan pelanggaran",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[2, 'asc']],
        columnDefs: [{
            targets: 0,
            orderable: false,
            searchable: false
        }]
    });
});

function cetakPDF() {
    const tabel = $('#tabelLaporan').DataTable();
    panjangHalamanSebelumCetak = tabel.page.len();
    tabel.page.len(-1).draw();

    setTimeout(function () {
        window.print();
    }, 300);
}

window.addEventListener('afterprint', function () {
    const tabel = $('#tabelLaporan').DataTable();
    tabel.page.len(panjangHalamanSebelumCetak).draw();
});
</script>
</body>
</html>
