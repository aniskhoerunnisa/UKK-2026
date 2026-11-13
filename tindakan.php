
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = mysqli_query($koneksi, "
    SELECT *
    FROM t_pelanggaran_siswa
    WHERE status = 'belum'
    ORDER BY tanggal DESC, id DESC
");

if (!$sql) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$total = mysqli_num_rows($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tindakan Pelanggaran - MyWebsite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
        }

        .content-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
        }

        .page-title {
            color: #202b46;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .table thead th {
            background: #e9ecef;
            color: #202b46;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
            padding: 14px 12px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px;
        }

        .table {
            margin-bottom: 0;
        }

        .form-control {
            min-width: 150px;
        }

        .empty-data {
            text-align: center;
            padding: 30px !important;
            color: #777;
        }

        .total-data {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <?php include "layout/sidebar.php"; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="content-card">

                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h4 class="page-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Tindakan Pelanggaran
                    </h4>

                    <span class="total-data">
                        Total data: <?= $total; ?>
                    </span>
                </div>

                <hr>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Guru Pelapor</th>
                                <th width="80">Poin</th>
                                <th width="230">Tindakan</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if ($total > 0): ?>
                                <?php $no = 1; ?>

                                <?php while ($d = mysqli_fetch_assoc($sql)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>

                                        <td>
                                            <?= htmlspecialchars($d['tanggal'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($d['nama_siswa'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($d['nama_pelanggaran'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($d['nama_guru'] ?? '-'); ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-danger">
                                                <?= (int)($d['poin'] ?? 0); ?>
                                            </span>
                                        </td>

                                        <td>
                                            <form action="proses_tindakan.php"
                                                  method="POST"
                                                  onsubmit="return confirm('Simpan tindakan dan pindahkan ke riwayat?')">

                                                <input type="hidden"
                                                       name="id"
                                                       value="<?= (int)$d['id']; ?>">

                                                <input type="text"
                                                       name="tindakan"
                                                       class="form-control form-control-sm mb-2"
                                                       placeholder="Masukkan tindakan"
                                                       required>

                                                <button type="submit"
                                                        class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-check me-1"></i>
                                                    Selesai
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="empty-data">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Belum ada pelanggaran yang perlu ditindak.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
