
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Tahun ajaran aktif
$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    LIMIT 1
");

$tahun_aktif = mysqli_fetch_assoc($tahun);

// Guru yang sedang login
$user_id = $_SESSION['id'];

$stmtGuru = mysqli_prepare($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE user_id = ?
    LIMIT 1
");
mysqli_stmt_bind_param($stmtGuru, "i", $user_id);
mysqli_stmt_execute($stmtGuru);
$guru = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtGuru));

// Siswa aktif
$siswa = mysqli_query($koneksi, "
    SELECT id, nis, nama
    FROM t_siswa
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Jenis pelanggaran
$pelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin
    FROM t_pelanggaran
    ORDER BY nama ASC
");

if (!$tahun_aktif) {
    die("Belum ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.");
}

if (!$guru) {
    die("Akun guru yang login belum terhubung dengan data guru.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Catat Pelanggaran - MyWebsite</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6ff;
    font-family: Arial, sans-serif;
}
.container-form {
    max-width: 850px;
    margin: 40px auto;
    background: white;
    border-radius: 13px;
    box-shadow: 0 4px 18px rgba(0,0,0,.08);
    overflow: hidden;
}
.header {
    background: linear-gradient(110deg,#5368ed,#8170ed);
    color: white;
    padding: 25px 30px;
}
.header h4 {
    font-weight: bold;
    margin: 0;
}
.form-content {
    padding: 30px;
}
.form-control {
    height: 43px;
    border-radius: 7px;
}
.select2-container .select2-selection--single {
    height: 43px;
    border: 1px solid #ced4da;
    border-radius: 7px;
    padding: 7px;
}
.select2-selection__arrow {
    height: 42px !important;
}
.btn-simpan {
    background: #5368ed;
    color: white;
    border-radius: 7px;
    padding: 10px 22px;
}
</style>
</head>

<body>

<div class="container-form">
    <div class="header">
        <h4><i class="fas fa-exclamation-circle mr-2"></i> Catat Pelanggaran</h4>
        <small>Form pencatatan pelanggaran siswa</small>
    </div>

    <div class="form-content">

        <form action="proses_simpan_pelanggaran.php" method="POST">

            <input type="hidden" name="tahun_ajaran_id"
                   value="<?= (int)$tahun_aktif['id']; ?>">

            <input type="hidden" name="guru_id"
                   value="<?= (int)$guru['id']; ?>">

            <div class="form-group">
                <label>Tahun Ajaran</label>
                <input type="text" class="form-control"
                       value="<?= htmlspecialchars($tahun_aktif['nama']); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Guru Pelapor</label>
                <input type="text" class="form-control"
                       value="<?= htmlspecialchars($guru['nama']); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Nama Siswa</label>
                <select name="siswa_id" class="form-control" required>
                    <option value="">-- Cari Nama Siswa --</option>
                    <?php while ($s = mysqli_fetch_assoc($siswa)) : ?>
                        <option value="<?= (int)$s['id']; ?>">
                            <?= htmlspecialchars($s['nis'].' - '.$s['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Pelanggaran</label>
                <select name="pelanggaran_id" id="pelanggaran"
                        class="form-control" required>
                    <option value="">-- Cari Jenis Pelanggaran --</option>
                    <?php while ($p = mysqli_fetch_assoc($pelanggaran)) : ?>
                        <option value="<?= (int)$p['id']; ?>"
                                data-poin="<?= (int)$p['poin']; ?>">
                            <?= htmlspecialchars($p['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Poin Pelanggaran</label>
                <input type="text" id="poin" class="form-control"
                       placeholder="Otomatis mengikuti jenis pelanggaran" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Pelanggaran</label>
                <input type="date" name="tanggal" class="form-control"
                       value="<?= date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"
                          rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                <button type="submit" class="btn btn-simpan">
                    <i class="fas fa-save"></i> Simpan Pelanggaran
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('select[name="siswa_id"]').select2({
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
