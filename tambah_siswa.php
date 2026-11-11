
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Siswa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f3f5ff;
            font-family: Arial, sans-serif;
            color: #253451;
        }

        .sidebar {
            width: 243px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #202b46;
            padding: 25px 16px;
            overflow-y: auto;
        }

        .brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 5px 12px 30px;
        }

        .menu-title {
            color: #929db5;
            font-size: 11px;
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
            padding: 30px;
        }

        .page-title {
            font-size: 27px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .subtitle {
            color: #8790a5;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .welcome-box {
            background: linear-gradient(110deg, #5368ed, #8860e8);
            color: white;
            padding: 25px 28px;
            border-radius: 15px;
            margin-bottom: 22px;
        }

        .welcome-box h5 {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .welcome-box p {
            font-size: 13px;
            margin: 0;
            line-height: 1.7;
        }

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(30,45,80,.035);
        }

        .content-card h5 {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .description {
            color: #929bad;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: bold;
            color: #52617c;
        }

        .form-control {
            border: 1px solid #e0e5f0;
            border-radius: 7px;
            font-size: 13px;
            height: 42px;
        }

        textarea.form-control {
            height: 100px;
        }

        .form-control:focus {
            border-color: #5368ed;
            box-shadow: 0 0 0 2px rgba(83,104,237,.12);
        }

        .btn-purple {
            background: #5368ed;
            color: white;
            border-radius: 7px;
            padding: 10px 18px;
            font-size: 13px;
        }

        .btn-purple:hover {
            background: #4055d5;
            color: white;
        }

        .btn-kembali {
            background: #e9ecf5;
            color: #52617c;
            border-radius: 7px;
            padding: 10px 18px;
            font-size: 13px;
        }

        @media(max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="brand">
        <i class="fas fa-graduation-cap mr-2"></i> SISWA APP
    </div>

    <div class="menu-title">Menu Utama</div>

    <a href="dashboard.php">
        <i class="fas fa-home"></i> Dashboard
    </a>

    <div class="menu-title">Master Data</div>

    <a href="kelola_siswa.php" class="active">
        <i class="fas fa-user-graduate"></i> Kelola Siswa
    </a>

    <a href="kelola_guru.php">
        <i class="fas fa-chalkboard-teacher"></i> Kelola Guru
    </a>

    <a href="kelola_wali_kelas.php">
        <i class="fas fa-user-tie"></i> Wali Kelas
    </a>

    <a href="kelola_kelas.php">
        <i class="fas fa-school"></i> Kelola Kelas
    </a>

    <a href="kelola_tahun_ajaran.php">
        <i class="fas fa-calendar-alt"></i> Tahun Ajaran
    </a>

    <a href="penempatan_siswa.php">
        <i class="fas fa-users"></i> Penempatan Siswa
    </a>

    <div class="menu-title">Pelanggaran</div>

    <a href="kelola_pelanggaran_kategori.php">
        <i class="fas fa-list-alt"></i> Kategori Pelanggaran
    </a>

    <a href="kelola_jenis_pelanggaran.php">
        <i class="fas fa-exclamation-triangle"></i> Jenis Pelanggaran
    </a>

    <a href="laporan.php">
        <i class="fas fa-file-alt"></i> Laporan
    </a>

    <div class="menu-title">Lainnya</div>

    <a href="about_me.php">
        <i class="fas fa-user"></i> About Me
    </a>

    <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>

</div>

<!-- KONTEN UTAMA -->
<div class="main-content">

    <h2 class="page-title">Tambah Siswa</h2>
    <p class="subtitle">Tambahkan data siswa baru ke dalam sistem.</p>

    <!-- BANNER -->
    <div class="welcome-box">
        <h5>
            <i class="fas fa-user-plus mr-2"></i>
            Form Tambah Siswa
        </h5>

        <p>
            Silakan lengkapi informasi siswa dengan benar
            untuk menyimpan data siswa ke dalam sistem.
        </p>
    </div>

    <!-- FORM -->
    <div class="content-card">

        <h5>Informasi Siswa</h5>

        <p class="description">
            Isi seluruh data siswa pada formulir berikut.
        </p>

        <form action="proses_tambah_siswa.php" method="POST">

            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>NIS</label>
                        <input type="text" name="nis"
                               class="form-control"
                               placeholder="Masukkan NIS"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>NISN</label>
                        <input type="text" name="nisn"
                               class="form-control"
                               placeholder="Masukkan NISN"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama"
                               class="form-control"
                               placeholder="Masukkan nama lengkap siswa"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                                class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Status Aktif</label>
                        <select name="status_aktif"
                                class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"
                                  placeholder="Masukkan alamat lengkap siswa"
                                  required></textarea>
                    </div>
                </div>

            </div>

            <hr>

            <div class="d-flex justify-content-end flex-wrap">

                <a href="kelola_siswa.php"
                   class="btn btn-kembali mr-2 mb-2">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>

                <button type="submit"
                        class="btn btn-purple mb-2">
                    <i class="fas fa-save mr-1"></i> Simpan Data
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>

