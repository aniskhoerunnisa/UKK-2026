
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// AMBIL ID KELAS
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

if ($id <= 0) {
    header("Location: kelola_kelas.php?pesan=id_invalid");
    exit;
}

// PROSES UPDATE KELAS
if (isset($_POST['update'])) {

    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $tingkat = trim($_POST['tingkat'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama_kelas == '' || $tingkat == '') {
        $pesan = 'kosong';
    } else {

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_kelas
             SET nama_kelas = ?, tingkat = ?, jurusan = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $nama_kelas,
            $tingkat,
            $jurusan,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: kelola_kelas.php?pesan=edit");
            exit;
        } else {
            die("Gagal mengubah data: " . mysqli_stmt_error($stmt));
        }
    }
}

// AMBIL DATA KELAS
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_kelas WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$data) {
    die("Data kelas tidak ditemukan.");
}

$pesan = $pesan ?? ($_GET['pesan'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Edit Kelas - Sistem Pelanggaran Siswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .content {
            padding: 25px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,.07);
        }

        .card-header {
            background: #fff;
            font-weight: bold;
            padding: 15px 20px;
        }

        .btn-primary {
            background: #344767;
            border-color: #344767;
        }

        .btn-primary:hover {
            background: #24334f;
            border-color: #24334f;
        }

        .form-control {
            border-radius: 6px;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 ml-sm-auto content">

            <div class="mb-4">
                <h3>
                    <i class="fas fa-school mr-2"></i>
                    Edit Kelas
                </h3>

                <p class="text-muted">
                    Perbarui data kelas pada sistem sekolah.
                </p>
            </div>

            <?php if ($pesan == 'kosong') { ?>
                <div class="alert alert-warning">
                    Semua kolom wajib diisi!
                </div>
            <?php } ?>

            <div class="card">

                <div class="card-header">
                    <i class="fas fa-edit mr-2"></i>
                    Form Edit Kelas
                </div>

                <div class="card-body">

                    <form method="POST" action="edit_kelas.php?id=<?= (int)$data['id']; ?>">

                        <input type="hidden" name="id"
                               value="<?= (int)$data['id']; ?>">

                        <div class="form-group">
                            <label>Nama Kelas</label>

                            <input
                                type="text"
                                name="nama_kelas"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama_kelas'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Masukkan nama kelas"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Tingkat</label>

                            <select name="tingkat" class="form-control" required>
                                <option value="">-- Pilih Tingkat --</option>

                                <?php foreach (['10', '11', '12'] as $t) { ?>
                                    <option value="<?= $t; ?>"
                                        <?= (($data['tingkat'] ?? '') == $t) ? 'selected' : ''; ?>>
                                        <?= $t; ?>
                                    </option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jurusan</label>

                            <input
                                type="text"
                                name="jurusan"
                                class="form-control"
                                value="<?= htmlspecialchars($data['jurusan'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                placeholder="Masukkan jurusan">
                        </div>

                        <button type="submit" name="update" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Perubahan
                        </button>

                        <a href="kelola_kelas.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
