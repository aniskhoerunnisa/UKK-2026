
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// PROSES TAMBAH PELANGGARAN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
    $tanggal = trim($_POST['tanggal'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');

    $user_id = (int)($_SESSION['id'] ?? 0);

    // Ambil guru berdasarkan akun login
    $stmt = mysqli_prepare($koneksi, "
        SELECT id FROM t_guru
        WHERE user_id = ?
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    // Ambil tahun ajaran aktif
    $tahun = mysqli_query($koneksi, "
        SELECT id FROM t_tahun_ajaran
        WHERE status_aktif = 1
        LIMIT 1
    ");
    $tahun_aktif = mysqli_fetch_assoc($tahun);

    if (!$guru) {
        $_SESSION['pesan_error'] = "Akun guru belum terhubung dengan data guru.";
        header("Location: catat_pelanggaran.php");
        exit;
    }

    if (!$tahun_aktif) {
        $_SESSION['pesan_error'] = "Belum ada tahun ajaran aktif.";
        header("Location: catat_pelanggaran.php");
        exit;
    }

    if ($siswa_id <= 0 || $pelanggaran_id <= 0 || $tanggal == '') {
        $_SESSION['pesan_error'] = "Data pelanggaran belum lengkap.";
        header("Location: catat_pelanggaran.php");
        exit;
    }

    // Ambil poin dari jenis pelanggaran
    $stmt = mysqli_prepare($koneksi, "
        SELECT poin FROM t_pelanggaran
        WHERE id = ?
        LIMIT 1
    ");
    mysqli_stmt_bind_param($stmt, "i", $pelanggaran_id);
    mysqli_stmt_execute($stmt);
    $data_pelanggaran = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$data_pelanggaran) {
        $_SESSION['pesan_error'] = "Jenis pelanggaran tidak ditemukan.";
        header("Location: catat_pelanggaran.php");
        exit;
    }

    $poin = (int)$data_pelanggaran['poin'];

    // Simpan data
    $stmt = mysqli_prepare($koneksi, "
        INSERT INTO t_pelanggaran_siswa
        (siswa_id, pelanggaran_id, guru_id, tahun_ajaran_id,
         tanggal, keterangan, poin, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'belum')
    ");

    if (!$stmt) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "iiiissi",
        $siswa_id,
        $pelanggaran_id,
        $guru['id'],
        $tahun_aktif['id'],
        $tanggal,
        $keterangan,
        $poin
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Pelanggaran berhasil dicatat.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menyimpan: " . mysqli_stmt_error($stmt);
    }

    header("Location: catat_pelanggaran.php");
    exit;
}

// AMBIL TAHUN AJARAN AKTIF
$tahun = mysqli_query($koneksi, "
    SELECT id, nama FROM t_tahun_ajaran
    WHERE status_aktif = 1 LIMIT 1
");
$tahun_aktif = mysqli_fetch_assoc($tahun);

// AMBIL GURU LOGIN
$user_id = (int)($_SESSION['id'] ?? 0);

$stmtGuru = mysqli_prepare($koneksi, "
    SELECT id, nama FROM t_guru
    WHERE user_id = ? LIMIT 1
");
mysqli_stmt_bind_param($stmtGuru, "i", $user_id);
mysqli_stmt_execute($stmtGuru);
$guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtGuru));

// AMBIL SISWA
$siswa = mysqli_query($koneksi, "
    SELECT id, nis, nama FROM t_siswa
    WHERE status_aktif = 1 ORDER BY nama ASC
");

// AMBIL JENIS PELANGGARAN
$pelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin FROM t_pelanggaran
    ORDER BY nama ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Catat Pelanggaran - MyWebsite</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6ff;
    font-family: Arial, sans-serif;
}
.content-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 18px rgba(0,0,0,.06);
}
.page-title {
    color: #202b46;
    font-size: 24px;
    font-weight: bold;
}
.form-label {
    color: #202b46;
    font-weight: 600;
}
.form-control {
    min-height: 43px;
    border-radius: 7px;
}
textarea.form-control {
    min-height: 100px;
}
.select2-container {
    width: 100% !important;
}
.select2-container .select2-selection--single {
    height: 43px;
    border: 1px solid #ced4da;
    border-radius: 7px;
    padding: 7px 10px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 27px;
    padding-left: 0;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px;
}
.btn-simpan {
    background: #5368ed;
    color: white;
    border: none;
    border-radius: 7px;
    padding: 10px 20px;
}
.btn-simpan:hover {
    background: #4055d8;
    color: white;
}
</style>
</head>

<body class="bg-light">

<div class="container-fluid">
<div class="row">

<?php include "layout/sidebar.php"; ?>

<main class="col-md-9 col-lg-10 p-4">

<div class="content-card">

<h4 class="page-title">
    <i class="fas fa-exclamation-circle me-2"></i>
    Catat Pelanggaran
</h4>
<p class="text-muted">Form pencatatan pelanggaran siswa</p>
<hr>

<?php if (!empty($_SESSION['pesan_error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['pesan_error']); ?>
    </div>
    <?php unset($_SESSION['pesan_error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['pesan_sukses'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['pesan_sukses']); ?>
    </div>
    <?php unset($_SESSION['pesan_sukses']); ?>
<?php endif; ?>

<?php if (!$tahun_aktif): ?>
    <div class="alert alert-warning">Belum ada tahun ajaran aktif.</div>

<?php elseif (!$guru): ?>
    <div class="alert alert-danger">
        Akun guru yang login belum terhubung dengan data guru.
        Periksa hubungan user_id pada tabel t_guru.
    </div>

<?php else: ?>

<form method="POST" action="catat_pelanggaran.php">

<input type="hidden" name="tahun_ajaran_id"
       value="<?= (int)$tahun_aktif['id']; ?>">

<input type="hidden" name="guru_id"
       value="<?= (int)$guru['id']; ?>">

<div class="row">

<div class="col-md-6 mb-3">
    <label class="form-label">Tahun Ajaran</label>
    <input type="text" class="form-control"
           value="<?= htmlspecialchars($tahun_aktif['nama']); ?>" readonly>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Guru Pelapor</label>
    <input type="text" class="form-control"
           value="<?= htmlspecialchars($guru['nama']); ?>" readonly>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Nama Siswa</label>
    <select name="siswa_id" id="siswa" class="form-control" required>
        <option value="">-- Cari Nama Siswa --</option>
        <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
            <option value="<?= (int)$s['id']; ?>">
                <?= htmlspecialchars($s['nis'].' - '.$s['nama']); ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Jenis Pelanggaran</label>
    <select name="pelanggaran_id" id="pelanggaran" class="form-control" required>
        <option value="">-- Cari Jenis Pelanggaran --</option>
        <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
            <option value="<?= (int)$p['id']; ?>"
                    data-poin="<?= (int)$p['poin']; ?>">
                <?= htmlspecialchars($p['nama']); ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Poin Pelanggaran</label>
    <input type="text" id="poin" class="form-control"
           placeholder="Otomatis mengikuti jenis pelanggaran" readonly>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Tanggal Pelanggaran</label>
    <input type="date" name="tanggal" class="form-control"
           value="<?= date('Y-m-d'); ?>" required>
</div>

<div class="col-12 mb-3">
    <label class="form-label">Keterangan</label>
    <textarea name="keterangan" class="form-control"
              placeholder="Keterangan tambahan (opsional)"></textarea>
</div>

</div>

<hr>

<div class="d-flex justify-content-between flex-wrap gap-2 mt-3">
    <a href="dashboard.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>

    <button type="submit" class="btn btn-simpan">
        <i class="fas fa-save me-1"></i> Simpan Pelanggaran
    </button>
</div>

</form>

<?php endif; ?>

</div>
</main>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#siswa').select2({
        placeholder: 'Cari nama siswa',
        width: '100%'
    });

    $('#pelanggaran').select2({
        placeholder: 'Cari jenis pelanggaran',
        width: '100%'
    });

    $('#pelanggaran').on('change', function() {
        let poin = $(this).find(':selected').data('poin');
        $('#poin').val(poin !== undefined ? poin : '');
    });
});
</script>

</body>
</html>
