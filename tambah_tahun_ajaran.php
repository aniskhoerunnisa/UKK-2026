
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// NOTIFIKASI
$pesan = $_GET['pesan'] ?? '';

// PROSES TAMBAH TAHUN AJARAN
if (isset($_POST['simpan'])) {

    $nama = trim($_POST['nama'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = $_POST['status_aktif'] ?? '';

    if (
        $nama === '' ||
        $tanggal_mulai === '' ||
        $tanggal_selesai === '' ||
        !in_array($status_aktif, ['0', '1'], true)
    ) {
        header("Location: tambah_tahun_ajaran.php?pesan=kosong");
        exit;
    }

    if ($tanggal_selesai < $tanggal_mulai) {
        header("Location: tambah_tahun_ajaran.php?pesan=tanggal");
        exit;
    }

    // Cek apakah sudah ada tahun ajaran aktif
    if ($status_aktif === '1') {

        $cek = mysqli_query(
            $koneksi,
            "SELECT id FROM t_tahun_ajaran
             WHERE status_aktif = 1 LIMIT 1"
        );

        if (!$cek) {
            die("Query gagal: " . mysqli_error($koneksi));
        }

        if (mysqli_num_rows($cek) > 0) {
            header("Location: tambah_tahun_ajaran.php?pesan=aktif_ada");
            exit;
        }
    }

    // Simpan data
    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_tahun_ajaran
        (nama, tanggal_mulai, tanggal_selesai, status_aktif)
        VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $nama,
        $tanggal_mulai,
        $tanggal_selesai,
        $status_aktif
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: kelola_tahun_ajaran.php?pesan=berhasil");
        exit;
    } else {
        die("Gagal menyimpan data: " . mysqli_stmt_error($stmt));
    }
}

// PESAN NOTIFIKASI
$notifikasi = [
    'aktif_ada' => [
        'warning',
        'Sudah ada tahun ajaran yang berstatus Aktif.'
    ],
    'berhasil' => [
        'success',
        'Tahun ajaran berhasil ditambahkan.'
    ],
    'gagal' => [
        'danger',
        'Tahun ajaran gagal ditambahkan.'
    ],
    'kosong' => [
        'warning',
        'Semua kolom wajib diisi.'
    ],
    'tanggal' => [
        'warning',
        'Tanggal selesai tidak boleh sebelum tanggal mulai.'
    ]
];
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Tambah Tahun Ajaran</title>

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
    border-radius: 10px;
    box-shadow: 0 3px 12px rgba(0,0,0,.07);
}

.card-header {
    background: white;
    font-weight: bold;
    padding: 16px 20px;
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
                <i class="fas fa-calendar-alt mr-2"></i>
                Tambah Tahun Ajaran
            </h3>

            <p class="page-subtitle">
                Tambahkan data tahun ajaran baru ke dalam sistem sekolah.
            </p>

        </div>

        <!-- NOTIFIKASI -->
        <?php if (isset($notifikasi[$pesan])) { ?>

            <div class="alert alert-<?=
                $notifikasi[$pesan][0];
            ?> alert-dismissible fade show">

                <?= htmlspecialchars($notifikasi[$pesan][1]); ?>

                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>

            </div>

        <?php } ?>

        <!-- FORM -->
        <div class="card">

            <div class="card-header">

                <i class="fas fa-calendar-plus mr-2"></i>
                Form Data Tahun Ajaran

            </div>

            <div class="card-body">

                <form method="POST" action="tambah_tahun_ajaran.php">

                    <div class="row">

                        <!-- NAMA -->
                        <div class="col-md-12">

                            <div class="form-group">

                                <label>Nama Tahun Ajaran</label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Contoh: 2026/2027"
                                    maxlength="20"
                                    required>

                            </div>

                        </div>

                        <!-- TANGGAL MULAI -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Tanggal Mulai</label>

                                <input
                                    type="date"
                                    name="tanggal_mulai"
                                    class="form-control"
                                    required>

                            </div>

                        </div>

                        <!-- TANGGAL SELESAI -->
                        <div class="col-md-6">

                            <div class="form-group">

                                <label>Tanggal Selesai</label>

                                <input
                                    type="date"
                                    name="tanggal_selesai"
                                    class="form-control"
                                    required>

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

                    <!-- TOMBOL -->
                    <div class="d-flex justify-content-end">

                        <a href="kelola_tahun_ajaran.php"
                           class="btn btn-secondary mr-2">

                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali

                        </a>

                        <button
                            type="reset"
                            class="btn btn-outline-secondary mr-2">

                            <i class="fas fa-redo mr-1"></i>
                            Reset

                        </button>

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
