
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Tahun ajaran aktif
$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    LIMIT 1
");

if (!$tahun) {
    die("Query tahun ajaran gagal: " . mysqli_error($koneksi));
}

$tahun_aktif = mysqli_fetch_assoc($tahun);

// Guru yang sedang login
$user_id = $_SESSION['id'] ?? 0;

$stmtGuru = mysqli_prepare($koneksi, "
    SELECT id, nama
    FROM t_guru
    WHERE user_id = ?
    LIMIT 1
");

if (!$stmtGuru) {
    die("Query guru gagal: " . mysqli_error($koneksi));
}

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

if (!$siswa) {
    die("Query siswa gagal: " . mysqli_error($koneksi));
}

// Jenis pelanggaran
$pelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin
    FROM t_pelanggaran
    ORDER BY nama ASC
");

if (!$pelanggaran) {
    die("Query jenis pelanggaran gagal: " . mysqli_error($koneksi));
}

if (!$tahun_aktif) {
    die("Belum ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.");
}

if (!$guru) {
    die("Akun guru yang login belum terhubung dengan data guru. Periksa hubungan user_id pada tabel t_guru.");
}
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
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
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

        .form-label {
            color: #202b46;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            min-height: 43px;
            border-radius: 7px;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .form-control:focus {
            border-color: #5368ed;
            box-shadow: 0 0 0 .15rem rgba(83,104,237,.15);
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

        .select2-container--default .select2-selection--single
        .select2-selection__rendered {
            line-height: 27px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single
        .select2-selection__arrow {
            height: 42px;
            right: 5px;
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

        .info-box {
            background: #f4f6ff;
            border: 1px solid #e1e5ff;
            border-radius: 8px;
            padding: 12px 15px;
            color: #202b46;
            font-size: 13px;
            margin-bottom: 22px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <?php include "layout/sidebar.php"; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="content-card">

                <div class="mb-3">
                    <h4 class="page-title">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Catat Pelanggaran
                    </h4>

                    <div class="page-subtitle">
                        Form pencatatan pelanggaran siswa
                    </div>
                </div>

                <hr>

                <div class="info-box">
                    <i class="fas fa-info-circle me-2"></i>
                    Tahun ajaran dan guru pelapor diisi secara otomatis berdasarkan data yang aktif dan akun yang login.
                </div>

                <form action="proses_simpan_pelanggaran.php" method="POST">

                    <input type="hidden"
                           name="tahun_ajaran_id"
                           value="<?= (int)$tahun_aktif['id']; ?>">

                    <input type="hidden"
                           name="guru_id"
                           value="<?= (int)$guru['id']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Ajaran</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($tahun_aktif['nama']); ?>"
                                   readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Guru Pelapor</label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= htmlspecialchars($guru['nama']); ?>"
                                   readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Siswa</label>

                            <select name="siswa_id"
                                    id="siswa"
                                    class="form-control"
                                    required>

                                <option value="">-- Cari Nama Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                    <option value="<?= (int)$s['id']; ?>">
                                        <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']); ?>
                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Pelanggaran</label>

                            <select name="pelanggaran_id"
                                    id="pelanggaran"
                                    class="form-control"
                                    required>

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

                            <input type="text"
                                   id="poin"
                                   class="form-control"
                                   placeholder="Otomatis mengikuti jenis pelanggaran"
                                   readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pelanggaran</label>

                            <input type="date"
                                   name="tanggal"
                                   class="form-control"
                                   value="<?= date('Y-m-d'); ?>"
                                   required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Keterangan</label>

                            <textarea name="keterangan"
                                      class="form-control"
                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">

                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-simpan">
                            <i class="fas fa-save me-1"></i>
                            Simpan Pelanggaran
                        </button>

                    </div>

                </form>

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

        $('#poin').val(
            poin !== undefined ? poin : ''
        );
    });

});
</script>

</body>
</html>
