
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Siswa - Sistem Pelanggaran Siswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6fa;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #202b46;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 15px;
            color: white;
        }

        .sidebar h4 {
            font-size: 19px;
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: #dce3f2;
            padding: 12px 15px;
            text-decoration: none;
            border-radius: 7px;
            margin-bottom: 7px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #34466d;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .card-header {
            background: white;
            padding: 20px;
            font-weight: bold;
        }

        .card-body {
            padding: 25px;
        }

        .form-control {
            border-radius: 7px;
        }

        .btn {
            border-radius: 7px;
            padding: 9px 18px;
        }

        @media(max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h4><i class="fas fa-school"></i> Sistem Pelanggaran</h4>

    <a href="dashboard.php">
        <i class="fas fa-home mr-2"></i> Dashboard
    </a>

    <a href="kelola_siswa.php" class="active">
        <i class="fas fa-user-graduate mr-2"></i> Kelola Siswa
    </a>

    <a href="kelola_guru.php">
        <i class="fas fa-chalkboard-teacher mr-2"></i> Kelola Guru
    </a>

    <a href="kelola_kelas.php">
        <i class="fas fa-door-open mr-2"></i> Kelola Kelas
    </a>

    <a href="kelola_tahun_ajaran.php">
        <i class="fas fa-calendar-alt mr-2"></i> Tahun Ajaran
    </a>

    <a href="logout.php">
        <i class="fas fa-sign-out-alt mr-2"></i> Logout
    </a>
</div>

<!-- KONTEN UTAMA -->
<div class="main-content">

    <div class="topbar">
        <h4 class="mb-1">Tambah Siswa</h4>
        <small class="text-muted">Dashboard / Kelola Siswa / Tambah Siswa</small>
    </div>

    <div class="card">

        <div class="card-header">
            <i class="fas fa-user-plus mr-2"></i> Form Tambah Siswa
        </div>

        <div class="card-body">

            <form action="proses_tambah_siswa.php" method="POST">

                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS" required>
                </div>

                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control" placeholder="Masukkan NISN" required>
                </div>

                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama siswa" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat" required></textarea>
                </div>

                <div class="form-group">
                    <label>Status Aktif</label>
                    <select name="status_aktif" class="form-control" required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                <hr>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>

                <a href="kelola_siswa.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
