
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// PROSES TAMBAH WALI KELAS
if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama'] ?? '');
    $nip = trim($_POST['nip'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    if (
        $nama === '' ||
        $nip === '' ||
        $jenis_kelamin === '' ||
        $no_hp === '' ||
        $alamat === '' ||
        !in_array($status_aktif, ['0', '1'], true)
    ) {
        header("Location: tambah_wali_kelas.php?pesan=kosong");
        exit;
    }

    // GANTI nama tabel jika tabel database kamu berbeda
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_wali_kelas
        (nama, nip, jenis_kelamin, no_hp, alamat, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $nama,
        $nip,
        $jenis_kelamin,
        $no_hp,
        $alamat,
        $status_aktif
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: kelola_wali_kelas.php?pesan=berhasil");
        exit;
    } else {
        die("Gagal menyimpan data: " . mysqli_stmt_error($stmt));
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Tambah Wali Kelas</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>

body {
    background: #f4f6f9;
    font-family: Arial, sans-serif;
}

.content {
    padding: 25px;
    min-height: 100vh;
}

.page-title {
    color: #202f4d;
    font-size: 25px;
    font-weight: bold;
}

.page-subtitle {
    color: #8995aa;
    font-size: 13px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,.07);
}

.card-header {
    background: white;
    padding: 18px 22px;
    font-weight: bold;
    border-bottom: 1px solid #eee;
}

.card-body {
    padding: 25px;
}

.form-group label {
    color: #344767;
    font-weight: 600;
    font-size: 13px;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e0e4ef;
    font-size: 13px;
    padding: 10px 12px;
}

.form-control:focus {
    border-color: #5865ed;
    box-shadow: 0 0 0 3px rgba(88,101,237,.1);
}

.btn-primary {
    background: #344767;
    border-color: #344767;
    border-radius: 7px;
}

.btn-secondary {
    border-radius: 7px;
}

@media(max-width:768px) {
    .content {
        padding: 15px;
    }
}

</style>

</head>

<body>

<div class="container-fluid">

<div class="row">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- KONTEN -->
<main class="col-md-9 col-lg-10 ml-sm-auto content">

    <!-- HEADER -->
    <div class="mb-4">

        <h3 class="page-title">
            <i class="fas fa-user-plus mr-2"></i>
            Tambah Wali Kelas
        </h3>

        <p class="page-subtitle">
            Tambahkan data wali kelas ke dalam sistem sekolah.
        </p>

    </div>

    <!-- NOTIFIKASI -->
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'kosong') { ?>

        <div class="alert alert-warning alert-dismissible fade show">

            <i class="fas fa-exclamation-circle mr-2"></i>
            Semua kolom wajib diisi!

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>

    <?php } ?>

    <!-- FORM TAMBAH -->
    <div class="card">

        <div class="card-header">

            <i class="fas fa-user-tie mr-2"></i>
            Form Data Wali Kelas

        </div>

        <div class="card-body">

            <form method="POST" action="tambah_wali_kelas.php">

                <div class="row">

                    <!-- NAMA -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Nama Wali Kelas</label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Masukkan nama wali kelas"
                                required>

                        </div>

                    </div>

                    <!-- NIP -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>NIP</label>

                            <input
                                type="text"
                                name="nip"
                                class="form-control"
                                placeholder="Masukkan NIP"
                                required>

                        </div>

                    </div>

                    <!-- JENIS KELAMIN -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Jenis Kelamin</label>

                            <select
                                name="jenis_kelamin"
                                class="form-control"
                                required>

                                <option value="">-- Pilih Jenis Kelamin --</option>

                                <option value="Laki-laki">Laki-laki</option>

                                <option value="Perempuan">Perempuan</option>

                            </select>

                        </div>

                    </div>

                    <!-- NO HP -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Nomor HP</label>

                            <input
                                type="text"
                                name="no_hp"
                                class="form-control"
                                placeholder="Masukkan nomor HP"
                                required>

                        </div>

                    </div>

                    <!-- ALAMAT -->
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>Alamat</label>

                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                required></textarea>

                        </div>

                    </div>

                    <!-- STATUS -->
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>Status Aktif</label>

                            <select
                                name="status_aktif"
                                class="form-control"
                                required>

                                <option value="1">Aktif</option>

                                <option value="0">Tidak Aktif</option>

                            </select>

                        </div>

                    </div>

                </div>

                <hr>

                <!-- BUTTON -->
                <div class="d-flex justify-content-end">

                    <a href="kelola_wali_kelas.php"
                       class="btn btn-secondary mr-2">

                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali

                    </a>

                    <button
                        type="submit"
                        name="simpan"
                        class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>
                        Simpan Data

                    </button>

                </div>

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
