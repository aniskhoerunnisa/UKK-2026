
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data kelas
$data_kelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama ASC");

// Ambil data guru
$data_guru = mysqli_query($koneksi, "SELECT id, nama FROM t_guru WHERE status_aktif = 1 ORDER BY nama ASC");

// Ambil data tahun ajaran
$data_tahun = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY nama DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas - Siswa App</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f3f5ff;
            color: #253451;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 243px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #202b46;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            color: white;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 35px;
            text-align: center;
        }

        .menu-title {
            color: #929db5;
            font-size: 11px;
            margin: 22px 10px 10px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: block;
            color: #c5ccda;
            text-decoration: none;
            padding: 11px 13px;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #5368ed;
            color: white;
        }

        .sidebar a i { width: 23px; }

        .main-content {
            margin-left: 243px;
            padding: 30px;
        }

        .banner {
            background: linear-gradient(110deg, #5368ed, #8860e8);
            color: white;
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 25px;
        }

        .banner h3 {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }

        .content-card h5 {
            font-weight: bold;
            margin-bottom: 22px;
        }

        .form-control {
            border-radius: 8px;
            height: 43px;
            border: 1px solid #dce1ee;
        }

        label {
            font-weight: 600;
            font-size: 14px;
        }

        .btn-primary {
            background: #5368ed;
            border-color: #5368ed;
            border-radius: 8px;
            padding: 10px 20px;
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 10px 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
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
        <i class="fas fa-graduation-cap"></i> SISWA APP
    </div>

    <div class="menu-title">Menu Utama</div>
    <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>

    <div class="menu-title">Master Data</div>
    <a href="kelola_siswa.php"><i class="fas fa-user-graduate"></i> Kelola Siswa</a>
    <a href="kelola_guru.php"><i class="fas fa-chalkboard-teacher"></i> Kelola Guru</a>
    <a href="kelola_wali_kelas.php" class="active"><i class="fas fa-user-tie"></i> Kelola Wali Kelas</a>
    <a href="kelola_kelas.php"><i class="fas fa-school"></i> Kelola Kelas</a>
    <a href="kelola_tahun_ajaran.php"><i class="fas fa-calendar-alt"></i> Tahun Ajaran</a>
    <a href="penempatan_siswa.php"><i class="fas fa-users"></i> Penempatan Siswa</a>

    <div class="menu-title">Pelanggaran</div>
    <a href="kelola_pelanggaran_kategori.php"><i class="fas fa-list"></i> Kategori Pelanggaran</a>
    <a href="kelola_jenis_pelanggaran.php"><i class="fas fa-exclamation-triangle"></i> Jenis Pelanggaran</a>
    <a href="laporan.php"><i class="fas fa-file-alt"></i> Laporan</a>

    <div class="menu-title">Lainnya</div>
    <a href="about_me.php"><i class="fas fa-info-circle"></i> About Me</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">

    <div class="banner">
        <h3>Tambah Wali Kelas</h3>
        <p class="mb-0">Tambahkan data wali kelas baru ke dalam sistem.</p>
    </div>

    <div class="content-card">
        <h5><i class="fas fa-user-plus mr-2"></i> Form Tambah Wali Kelas</h5>

        <form action="proses_tambah_wali_kelas.php" method="POST">

            <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-control" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    <?php while ($tahun = mysqli_fetch_assoc($data_tahun)) : ?>
                        <option value="<?= htmlspecialchars($tahun['id']); ?>">
                            <?= htmlspecialchars($tahun['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Kelas</label>
                <select name="kelas_id" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php while ($kelas = mysqli_fetch_assoc($data_kelas)) : ?>
                        <option value="<?= htmlspecialchars($kelas['id']); ?>">
                            <?= htmlspecialchars($kelas['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Guru</label>
                <select name="guru_id" class="form-control" required>
                    <option value="">-- Pilih Guru --</option>
                    <?php while ($guru = mysqli_fetch_assoc($data_guru)) : ?>
                        <option value="<?= htmlspecialchars($guru['id']); ?>">
                            <?= htmlspecialchars($guru['nama']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status_aktif" class="form-control" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>

                <a href="kelola_wali_kelas.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

        </form>
    </div>

</div>

</body>
</html>
