
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: jenis_pelanggaran.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data jenis pelanggaran
$stmt = mysqli_prepare($koneksi, "
    SELECT * FROM t_pelanggaran WHERE id = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data jenis pelanggaran tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Jenis Pelanggaran - MyWebsite</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
        }

        .container-edit {
            max-width: 850px;
            margin: 50px auto;
            background: white;
            border-radius: 13px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(110deg, #5368ed, #8170ed);
            color: white;
            padding: 25px 30px;
        }

        .header h4 {
            margin: 0;
            font-weight: bold;
        }

        .form-content {
            padding: 30px;
        }

        .form-control {
            height: 43px;
            border-radius: 7px;
        }

        .btn-simpan {
            background: #5368ed;
            color: white;
            border-radius: 7px;
            padding: 10px 22px;
        }

        .btn-simpan:hover {
            background: #4055d5;
            color: white;
        }

        .btn-kembali {
            border-radius: 7px;
            padding: 10px 22px;
        }
    </style>
</head>

<body>

<div class="container-edit">

    <div class="header">
        <h4><i class="fas fa-exclamation-triangle mr-2"></i> Edit Jenis Pelanggaran</h4>
        <small>Perbarui data jenis pelanggaran siswa</small>
    </div>

    <div class="form-content">

        <form action="proses_edit_jenis_pelanggaran.php" method="POST">

            <input type="hidden" name="id" value="<?= (int)$data['id']; ?>">

            <div class="form-group">
                <label>Nama Pelanggaran</label>
                <input type="text"
                       name="nama"
                       class="form-control"
                       value="<?= htmlspecialchars($data['nama']); ?>"
                       placeholder="Masukkan nama pelanggaran"
                       required>
            </div>

            <div class="form-group">
                <label>Poin Pelanggaran</label>
                <input type="number"
                       name="poin"
                       class="form-control"
                       value="<?= htmlspecialchars($data['poin']); ?>"
                       min="0"
                       required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="jenis_pelanggaran.php" class="btn btn-secondary btn-kembali">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>

                <button type="submit" class="btn btn-simpan">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
