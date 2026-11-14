
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = "";

// AMBIL TAHUN AJARAN AKTIF
$queryTahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    LIMIT 1
");

$tahun = mysqli_fetch_assoc($queryTahun);
$tahun_ajaran_id = (int)($tahun['id'] ?? 0);

// AMBIL GURU YANG LOGIN
$user_id = (int)($_SESSION['id'] ?? 0);

$stmtGuru = mysqli_prepare($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE user_id = ?
    LIMIT 1
");

mysqli_stmt_bind_param($stmtGuru, "i", $user_id);
mysqli_stmt_execute($stmtGuru);
$hasilGuru = mysqli_stmt_get_result($stmtGuru);
$guru = mysqli_fetch_assoc($hasilGuru);
mysqli_stmt_close($stmtGuru);

$guru_id = (int)($guru['id'] ?? 0);

// PROSES SIMPAN
if (isset($_POST['simpan'])) {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($siswa_id <= 0 || $pelanggaran_id <= 0 || empty($tanggal)) {
        $pesan = "Silakan lengkapi semua data wajib.";
    } elseif ($tahun_ajaran_id <= 0) {
        $pesan = "Belum ada tahun ajaran yang aktif.";
    } elseif ($guru_id <= 0) {
        $pesan = "Akun guru yang login belum terhubung dengan data guru.";
    } else {

        // AMBIL POIN JENIS PELANGGARAN
        $stmtPoin = mysqli_prepare($koneksi, "
            SELECT poin
            FROM t_pelanggaran
            WHERE id = ?
            LIMIT 1
        ");

        mysqli_stmt_bind_param($stmtPoin, "i", $pelanggaran_id);
        mysqli_stmt_execute($stmtPoin);
        $hasilPoin = mysqli_stmt_get_result($stmtPoin);
        $dataPoin = mysqli_fetch_assoc($hasilPoin);
        mysqli_stmt_close($stmtPoin);

        if (!$dataPoin) {
            $pesan = "Jenis pelanggaran tidak ditemukan.";
        } else {

            $poin = (int)$dataPoin['poin'];

            // SIMPAN CATATAN PELANGGARAN
            $stmtSimpan = mysqli_prepare($koneksi, "
                INSERT INTO t_pelanggaran_siswa
                (
                    siswa_id,
                    pelanggaran_id,
                    guru_id,
                    tahun_ajaran_id,
                    tanggal,
                    keterangan,
                    poin,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, 'belum')
            ");

            if ($stmtSimpan) {

                mysqli_stmt_bind_param(
                    $stmtSimpan,
                    "iiiissi",
                    $siswa_id,
                    $pelanggaran_id,
                    $guru_id,
                    $tahun_ajaran_id,
                    $tanggal,
                    $keterangan,
                    $poin
                );

                if (mysqli_stmt_execute($stmtSimpan)) {
                    mysqli_stmt_close($stmtSimpan);
                    header("Location: catat_pelanggaran.php?pesan=berhasil_tambah");
                    exit;
                } else {
                    $pesan = "Gagal menyimpan data: " . mysqli_stmt_error($stmtSimpan);
                    mysqli_stmt_close($stmtSimpan);
                }

            } else {
                $pesan = "Query gagal: " . mysqli_error($koneksi);
            }
        }
    }
}

// AMBIL DATA SISWA
$siswa = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_siswa
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// AMBIL JENIS PELANGGARAN
$pelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Catat Pelanggaran - MyWebsite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .judul {
            color: #202b46;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <?php include "layout/sidebar.php"; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="card shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h4 class="judul mb-1">
                        <i class="fas fa-plus-circle me-2"></i>
                        Tambah Catat Pelanggaran
                    </h4>

                    <p class="text-muted mb-4">
                        Tambahkan data pelanggaran siswa.
                    </p>

                    <?php if ($pesan != ""): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <!-- TAHUN AJARAN -->
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tahun['nama'] ?? 'Belum ada tahun ajaran aktif'); ?>"
                                   readonly>
                        </div>

                        <!-- GURU PELAPOR -->
                        <div class="mb-3">
                            <label class="form-label">Guru Pelapor</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($guru['nama'] ?? 'Guru belum terhubung'); ?>"
                                   readonly>
                        </div>

                        <!-- SISWA -->
                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>

                            <select name="siswa_id" class="form-select select2" required>
                                <option value="">-- Pilih Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                    <option value="<?= (int)$s['id']; ?>"
                                        <?= ($_POST['siswa_id'] ?? '') == $s['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($s['nama']); ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>

                        <!-- JENIS PELANGGARAN -->
                        <div class="mb-3">
                            <label class="form-label">Jenis Pelanggaran</label>

                            <select name="pelanggaran_id"
                                    id="pelanggaran_id"
                                    class="form-select select2"
                                    required>

                                <option value="">-- Pilih Jenis Pelanggaran --</option>

                                <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                    <option
                                        value="<?= (int)$p['id']; ?>"
                                        data-poin="<?= (int)$p['poin']; ?>"
                                        <?= ($_POST['pelanggaran_id'] ?? '') == $p['id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($p['nama']); ?>
                                        (<?= (int)$p['poin']; ?> poin)

                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>

                        <!-- POIN -->
                        <div class="mb-3">
                            <label class="form-label">Poin Pelanggaran</label>

                            <input type="number"
                                   id="poin"
                                   class="form-control"
                                   value="0"
                                   readonly>

                            <small class="text-muted">
                                Poin otomatis mengikuti jenis pelanggaran.
                            </small>
                        </div>

                        <!-- TANGGAL -->
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pelanggaran</label>

                            <input type="date"
                                   name="tanggal"
                                   class="form-control"
                                   value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')); ?>"
                                   required>
                        </div>

                        <!-- KETERANGAN -->
                        <div class="mb-4">
                            <label class="form-label">Keterangan</label>

                            <textarea name="keterangan"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Masukkan keterangan pelanggaran..."><?= htmlspecialchars($_POST['keterangan'] ?? ''); ?></textarea>
                        </div>

                        <!-- TOMBOL -->
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            Simpan Data
                        </button>

                        <a href="catat_pelanggaran.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                    </form>

                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {

    $('.select2').select2({
        width: '100%',
        placeholder: 'Cari data...',
        allowClear: true
    });

    function updatePoin() {
        let poin = $('#pelanggaran_id option:selected').data('poin') || 0;
        $('#poin').val(poin);
    }

    $('#pelanggaran_id').on('change', updatePoin);

    updatePoin();
});
</script>

</body>
</html>
