
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID wali kelas tidak valid.");
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_wali_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data wali kelas tidak ditemukan.");
}

// Ambil data kelas
$query_kelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama ASC");

// Ambil data guru
$query_guru = mysqli_query($koneksi, "SELECT id, nama FROM t_guru ORDER BY nama ASC");

// Ambil data tahun ajaran
$query_tahun = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wali Kelas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container-edit {
            max-width: 850px;
            margin: 45px auto;
            padding: 0 15px;
        }

        .header-edit {
            background: linear-gradient(135deg, #5965d8, #9365df);
            color: white;
            padding: 28px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .header-edit h3 {
            font-weight: bold;
            margin-bottom: 7px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }

        .card-body {
            padding: 30px;
        }

        .form-control {
            height: 43px;
            border-radius: 7px;
            border: 1px solid #dce0eb;
        }

        .form-control:focus {
            border-color: #7568dc;
            box-shadow: 0 0 0 3px rgba(117,104,220,0.12);
        }

        label {
            font-weight: 600;
            color: #424b60;
        }

        .btn {
            border-radius: 7px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary {
            background: #625fd5;
            border-color: #625fd5;
        }

        .btn-primary:hover {
            background: #514dc0;
            border-color: #514dc0;
        }
    </style>
</head>

<body>

<div class="container-edit">

    <div class="header-edit">
        <h3><i class="fas fa-user-edit mr-2"></i>Edit Data Wali Kelas</h3>
        <p class="mb-0">Silakan ubah informasi data wali kelas di bawah ini.</p>
    </div>

    <div class="card">
        <div class="card-body">

            <h5 class="font-weight-bold mb-4">
                <i class="fas fa-user-tie text-primary mr-2"></i>
                Form Edit Wali Kelas
            </h5>

            <form action="proses_edit_wali_kelas.php" method="POST">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($data['id']) ?>">

                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-control" required>
                        <option value="">-- Pilih Tahun Ajaran --</option>

                        <?php while ($tahun = mysqli_fetch_assoc($query_tahun)) : ?>
                            <option value="<?= $tahun['id']; ?>"
                                <?= $data['tahun_ajaran_id'] == $tahun['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tahun['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>

                        <?php while ($kelas = mysqli_fetch_assoc($query_kelas)) : ?>
                            <option value="<?= $kelas['id']; ?>"
                                <?= $data['kelas_id'] == $kelas['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kelas['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Guru Wali Kelas</label>
                    <select name="guru_id" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>

                        <?php while ($guru = mysqli_fetch_assoc($query_guru)) : ?>
                            <option value="<?= $guru['id']; ?>"
                                <?= $data['guru_id'] == $guru['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($guru['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai"
                           class="form-control"
                           value="<?= htmlspecialchars($data['tanggal_mulai'] ?? '') ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai"
                           class="form-control"
                           value="<?= htmlspecialchars($data['tanggal_selesai'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Status Aktif</label>
                    <select name="status_aktif" class="form-control" required>
                        <option value="1" <?= $data['status_aktif'] == 1 ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="0" <?= $data['status_aktif'] == 0 ? 'selected' : '' ?>>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="kelola_wali_kelas.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Wali Kelas
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
