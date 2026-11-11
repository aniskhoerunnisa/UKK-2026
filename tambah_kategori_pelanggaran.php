
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    if ($nama_kategori === '') {
        $error = "Nama kategori wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_kategori_pelanggaran
            (nama_kategori, deskripsi, status_aktif)
            VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $nama_kategori,
            $deskripsi,
            $status_aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: kelola_pelanggaran_kategori.php?pesan=tambah");
            exit;
        } else {
            $error = "Gagal menyimpan kategori: " . mysqli_error($koneksi);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kategori Pelanggaran</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f4f6ff;
            font-family: Arial, sans-serif;
            color: #26324b;
        }

        .sidebar {
            width: 243px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #202b46;
            color: white;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            font-size: 20px;
            font-weight: bold;
            padding: 5px 12px 30px;
        }

        .menu-title {
            font-size: 11px;
            color: #929db5;
            margin: 18px 12px 10px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: block;
            color: #c4ccdc;
            text-decoration: none;
            padding: 12px 13px;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .sidebar a i { width: 23px; }

        .sidebar a:hover,
        .sidebar a.active {
            background: #5368ed;
            color: white;
        }

        .main-content {
            margin-left: 243px;
            padding: 35px;
        }

        .content-card {
            background: white;
            border-radius: 13px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(30,45,80,.04);
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
        }

        .subtitle {
            color: #8790a5;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .btn-purple {
            background: #5368ed;
            color: white;
            border-radius: 7px;
        }

        .btn-purple:hover {
            background: #4055d5;
            color: white;
        }

        .form-control {
            border-radius: 7px;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="brand">
        <i class="fas fa-graduation-cap mr-2"></i> MyWebsite.
    </div>

    <div class="menu-title">Menu Utama</div>
    <a href="dashboard.php">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <div class="menu-title">Master Data</div>
    <a href="kelola_siswa.php"><i class="fas fa-user-graduate"></i> Kelola Siswa</a>
    <a href="kelola_guru.php"><i class="fas fa-chalkboard-teacher"></i> Kelola Guru</a>
    <a href="kelola_wali_kelas.php"><i class="fas fa-user-tie"></i> Wali Kelas</a>
    <a href="kelola_kelas.php"><i class="fas fa-school"></i> Kelola Kelas</a>
    <a href="kelola_tahun_ajaran.php"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</a>
    <a href="penempatan_siswa.php"><i class="fas fa-users"></i> Penempatan Siswa</a>

    <div class="menu-title">Pelanggaran</div>
    <a href="kelola_pelanggaran_kategori.php" class="active">
        <i class="fas fa-list-alt"></i> Kategori Pelanggaran
    </a>
    <a href="kelola_jenis_pelanggaran.php">
        <i class="fas fa-exclamation-triangle"></i> Jenis Pelanggaran
    </a>
    <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>

    <div class="menu-title">Lainnya</div>
    <a href="about_me.php"><i class="fas fa-user"></i> About Me</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">

    <h2 class="page-title">Tambah Kategori Pelanggaran</h2>
    <p class="subtitle">Tambahkan kategori pelanggaran siswa yang baru.</p>

    <div class="content-card">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="font-weight-bold mb-0">
                <i class="fas fa-list-alt mr-2"></i> Form Kategori Pelanggaran
            </h5>

            <a href="kelola_pelanggaran_kategori.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-group">
                <label>Nama Kategori</label>
                <input
                    type="text"
                    name="nama_kategori"
                    class="form-control"
                    placeholder="Masukkan nama kategori pelanggaran"
                    value="<?= htmlspecialchars($_POST['nama_kategori'] ?? ''); ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea
                    name="deskripsi"
                    class="form-control"
                    rows="4"
                    placeholder="Masukkan deskripsi kategori"><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
                <label>Status Aktif</label>
                <select name="status_aktif" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <hr>

            <button type="submit" class="btn btn-purple">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>

            <a href="kelola_pelanggaran_kategori.php" class="btn btn-light">
                Batal
            </a>

        </form>
    </div>

</div>

</body>
</html>
