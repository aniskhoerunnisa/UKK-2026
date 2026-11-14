
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$pesan = "";

// CEK ID
if ($id <= 0) {
    header("Location: catat_pelanggaran.php");
    exit;
}

// PROSES UPDATE
if (isset($_POST['simpan'])) {

    $siswa_id = (int)($_POST['siswa_id'] ?? 0);
    $pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');

    if ($siswa_id <= 0 || $pelanggaran_id <= 0 || empty($tanggal)) {
        $pesan = "Silakan lengkapi data wajib terlebih dahulu.";
    } else {

        // CEK SISWA
        $cekSiswa = mysqli_prepare($koneksi, "
            SELECT id FROM t_siswa WHERE id = ? LIMIT 1
        ");
        mysqli_stmt_bind_param($cekSiswa, "i", $siswa_id);
        mysqli_stmt_execute($cekSiswa);
        $hasilSiswa = mysqli_stmt_get_result($cekSiswa);
        $dataSiswa = mysqli_fetch_assoc($hasilSiswa);
        mysqli_stmt_close($cekSiswa);

        // CEK JENIS PELANGGARAN
        $cekPelanggaran = mysqli_prepare($koneksi, "
            SELECT id FROM t_pelanggaran WHERE id = ? LIMIT 1
        ");
        mysqli_stmt_bind_param($cekPelanggaran, "i", $pelanggaran_id);
        mysqli_stmt_execute($cekPelanggaran);
        $hasilPelanggaran = mysqli_stmt_get_result($cekPelanggaran);
        $dataPelanggaran = mysqli_fetch_assoc($hasilPelanggaran);
        mysqli_stmt_close($cekPelanggaran);

        if (!$dataSiswa) {
            $pesan = "Data siswa tidak ditemukan.";
        } elseif (!$dataPelanggaran) {
            $pesan = "Jenis pelanggaran tidak ditemukan.";
        } else {

            // UPDATE CATATAN
            $stmt = mysqli_prepare($koneksi, "
                UPDATE t_pelanggaran_siswa
                SET siswa_id = ?,
                    pelanggaran_id = ?,
                    tanggal = ?,
                    keterangan = ?
                WHERE id = ?
            ");

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "iissi",
                    $siswa_id,
                    $pelanggaran_id,
                    $tanggal,
                    $keterangan,
                    $id
                );

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header("Location: catat_pelanggaran.php?pesan=berhasil_edit");
                    exit;
                } else {
                    $pesan = "Gagal mengubah data: " . mysqli_stmt_error($stmt);
                    mysqli_stmt_close($stmt);
                }
            } else {
                $pesan = "Query gagal: " . mysqli_error($koneksi);
            }
        }
    }
}

// AMBIL DATA CATATAN
$stmtData = mysqli_prepare($koneksi, "
    SELECT *
    FROM t_pelanggaran_siswa
    WHERE id = ?
    LIMIT 1
");

if (!$stmtData) {
    die("Query data gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmtData, "i", $id);
mysqli_stmt_execute($stmtData);
$hasilData = mysqli_stmt_get_result($stmtData);
$data = mysqli_fetch_assoc($hasilData);
mysqli_stmt_close($stmtData);

if (!$data) {
    die("Data catatan pelanggaran tidak ditemukan.");
}

// AMBIL DATA SISWA
$siswa = mysqli_query($koneksi, "
    SELECT id, nis, nama
    FROM t_siswa
    ORDER BY nama ASC
");

if (!$siswa) {
    die("Query siswa gagal: " . mysqli_error($koneksi));
}

// AMBIL JENIS PELANGGARAN
$pelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin
    FROM t_pelanggaran
    ORDER BY nama ASC
");

if (!$pelanggaran) {
    die("Query pelanggaran gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Catat Pelanggaran - MyWebsite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet">

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
                        <i class="fas fa-edit me-2"></i>
                        Edit Catat Pelanggaran
                    </h4>

                    <div class="page-subtitle">
                        Perbarui data catat pelanggaran siswa.
                    </div>
                </div>

                <hr>

                <?php if ($pesan != ""): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($pesan); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">

                    <input type="hidden" name="id"
                           value="<?= (int)$data['id']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Siswa</label>

                            <select name="siswa_id" id="siswa"
                                    class="form-control" required>

                                <option value="">-- Cari Nama Siswa --</option>

                                <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                    <option value="<?= (int)$s['id']; ?>"
                                        <?= (int)$data['siswa_id'] === (int)$s['id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']); ?>

                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Pelanggaran</label>

                            <select name="pelanggaran_id" id="pelanggaran"
                                    class="form-control" required>

                                <option value="">-- Cari Jenis Pelanggaran --</option>

                                <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                    <option value="<?= (int)$p['id']; ?>"
                                            data-poin="<?= (int)$p['poin']; ?>"
                                        <?= (int)$data['pelanggaran_id'] === (int)$p['id'] ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($p['nama']); ?>

                                    </option>
                                <?php endwhile; ?>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Poin Pelanggaran</label>

                            <input type="text" id="poin"
                                   class="form-control"
                                   readonly
                                   placeholder="Poin otomatis">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pelanggaran</label>

                            <input type="date" name="tanggal"
                                   class="form-control"
                                   value="<?= htmlspecialchars($data['tanggal'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Keterangan</label>

                            <textarea name="keterangan"
                                      class="form-control"
                                      placeholder="Masukkan keterangan tambahan..."><?= htmlspecialchars($data['keterangan'] ?? ''); ?></textarea>
                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between flex-wrap gap-2 mt-3">

                        <a href="catat_pelanggaran.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali
                        </a>

                        <button type="submit" name="simpan" class="btn btn-simpan">
                            <i class="fas fa-save me-1"></i>
                            Simpan Perubahan
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

    function updatePoin() {
        let poin = $('#pelanggaran option:selected').data('poin');
        $('#poin').val(poin !== undefined ? poin : '');
    }

    $('#pelanggaran').on('change', updatePoin);

    updatePoin();

});
</script>

</body>
</html>
