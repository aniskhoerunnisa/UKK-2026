
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = "";

// PROSES HAPUS
if (isset($_POST['hapus'])) {
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        $stmt = mysqli_prepare($koneksi, "
            DELETE FROM t_pelanggaran_siswa
            WHERE id = ?
        ");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);

            if (mysqli_stmt_execute($stmt)) {
                $pesan = "Catatan pelanggaran berhasil dihapus.";
            } else {
                $pesan = "Gagal menghapus catatan pelanggaran.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $pesan = "Query hapus gagal: " . mysqli_error($koneksi);
        }
    } else {
        $pesan = "ID catatan tidak valid.";
    }
}

// AMBIL ID DARI URL
$id = (int)($_GET['id'] ?? 0);

// AMBIL DATA CATATAN
$data = null;

if ($id > 0) {
    $stmt = mysqli_prepare($koneksi, "
        SELECT ps.*,
               s.nama AS nama_siswa,
               p.nama AS nama_pelanggaran
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_siswa s ON ps.siswa_id = s.id
        LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
        WHERE ps.id = ?
        LIMIT 1
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($hasil);
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Catat Pelanggaran - MyWebsite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <?php include "layout/sidebar.php"; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-1" style="color:#202b46;">
                        Hapus Catat Pelanggaran
                    </h4>

                    <p class="text-muted mb-4">
                        Hapus data catatan pelanggaran siswa.
                    </p>

                    <?php if ($pesan != ""): ?>
                        <div class="alert alert-info">
                            <?= htmlspecialchars($pesan); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($data): ?>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Apakah kamu yakin ingin menghapus catatan pelanggaran ini?
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="180">Nama Siswa</th>
                                    <td><?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Pelanggaran</th>
                                    <td><?= htmlspecialchars($data['nama_pelanggaran'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td><?= htmlspecialchars($data['tanggal'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td><?= htmlspecialchars($data['keterangan'] ?? '-'); ?></td>
                                </tr>
                            </table>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="id" value="<?= (int)$data['id']; ?>">

                            <button type="submit" name="hapus"
                                class="btn btn-danger"
                                onclick="return confirm('Yakin ingin menghapus catatan ini secara permanen?')">
                                <i class="fas fa-trash me-1"></i> Ya, Hapus
                            </button>

                            <a href="catat_pelanggaran.php" class="btn btn-secondary">
                                Batal
                            </a>
                        </form>

                    <?php elseif ($id > 0): ?>

                        <div class="alert alert-danger">
                            Data catat pelanggaran tidak ditemukan.
                        </div>

                        <a href="catat_pelanggaran.php" class="btn btn-secondary">
                            Kembali
                        </a>

                    <?php else: ?>

                        <div class="alert alert-info">
                            Pilih catat pelanggaran dari halaman data catat untuk dihapus.
                        </div>

                        <a href="catat_pelanggaran.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali ke Catatan
                        </a>

                    <?php endif; ?>

                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
