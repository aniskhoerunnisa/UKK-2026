
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_tahun_ajaran.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data tahun ajaran
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_tahun_ajaran WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tahun ajaran tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Tahun Ajaran - MyWebsite</title>

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
            border-radius: 7px;
            height: 43px;
        }

        textarea.form-control {
            height: auto;
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
        <h4><i class="fas fa-calendar-alt mr-2"></i> Edit Tahun Ajaran</h4>
        <small>Perbarui data tahun ajaran</small>
    </div>

    <div class="form-content">

        <form action="proses_edit_tahun_ajaran.php" method="POST">

            <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']); ?>">

            <div class="form-group">
                <label>Nama Tahun Ajaran</label>
                <input type="text"
                       name="nama"
                       class="form-control"
                       value="<?= htmlspecialchars($data['nama']); ?>"
                       placeholder="Contoh: 2026/2027"
                       required>
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date"
                       name="tanggal_mulai"
                       class="form-control"
                       value="<?= htmlspecialchars($data['tanggal_mulai']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date"
                       name="tanggal_selesai"
                       class="form-control"
                       value="<?= htmlspecialchars($data['tanggal_selesai']); ?>"
                       required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status_aktif" class="form-control" required>
                    <option value="1" <?= $data['status_aktif'] == 1 ? 'selected' : ''; ?>>
                        Aktif
                    </option>
                    <option value="0" <?= $data['status_aktif'] == 0 ? 'selected' : ''; ?>>
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="kelola_tahun_ajaran.php" class="btn btn-secondary btn-kembali">
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
