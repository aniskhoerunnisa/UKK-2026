
<?php
// tambah_wali_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data guru
$sql_guru = "SELECT id, nama FROM t_guru
             WHERE status_aktif = 1
             ORDER BY nama ASC";

$hasil_guru = mysqli_query($koneksi, $sql_guru);

if (!$hasil_guru) {
    die("Query guru gagal: " . mysqli_error($koneksi));
}

// Ambil data kelas
$sql_kelas = "SELECT id, nama FROM t_kelas
              ORDER BY nama ASC";

$hasil_kelas = mysqli_query($koneksi, $sql_kelas);

if (!$hasil_kelas) {
    die("Query kelas gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Wali Kelas - Sistem Pelanggaran Siswa</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6fa;
            font-family: Arial, sans-serif;
        }

        main {
            min-height: 100vh;
        }

        .content-header {
            background: white;
            padding: 22px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: white;
            padding: 18px;
            font-weight: bold;
        }

        .card-body {
            padding: 25px;
        }

        .form-control {
            border-radius: 6px;
        }

        .btn {
            border-radius: 5px;
            padding: 9px 18px;
        }

        @media(max-width: 768px) {
            main {
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <!-- HEADER -->
            <div class="content-header">

                <h3 class="mb-2">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Tambah Wali Kelas
                </h3>

                <p class="text-muted mb-0">
                    Tambahkan penempatan guru sebagai wali kelas.
                </p>

            </div>

            <!-- FORM TAMBAH WALI KELAS -->
            <div class="card">

                <div class="card-header">
                    <i class="fas fa-user-plus mr-2"></i>
                    Form Tambah Wali Kelas
                </div>

                <div class="card-body">

                    <form action="proses_tambah_wali_kelas.php" method="POST">

                        <div class="form-group">
                            <label>Nama Guru</label>

                            <select name="guru_id" class="form-control" required>

                                <option value="">-- Pilih Guru --</option>

                                <?php while ($guru = mysqli_fetch_assoc($hasil_guru)) { ?>

                                    <option value="<?php echo htmlspecialchars($guru['id']); ?>">
                                        <?php echo htmlspecialchars($guru['nama']); ?>
                                    </option>

                                <?php } ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kelas</label>

                            <select name="kelas_id" class="form-control" required>

                                <option value="">-- Pilih Kelas --</option>

                                <?php while ($kelas = mysqli_fetch_assoc($hasil_kelas)) { ?>

                                    <option value="<?php echo htmlspecialchars($kelas['id']); ?>">
                                        <?php echo htmlspecialchars($kelas['nama']); ?>
                                    </option>

                                <?php } ?>

                            </select>
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
                            <i class="fas fa-save mr-1"></i>
                            Simpan
                        </button>

                        <a href="data_wali_kelas.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>

                    </form>

                </div>
            </div>

        </main>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
