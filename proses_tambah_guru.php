
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// MEMBUAT TABEL JIKA BELUM ADA
$buatTabel = "CREATE TABLE IF NOT EXISTS guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(30) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    status_aktif VARCHAR(20) NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($koneksi, $buatTabel)) {
    die("Gagal membuat tabel: " . mysqli_error($koneksi));
}

// PROSES TAMBAH GURU
if (isset($_POST['tambah'])) {

    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $status_aktif = trim($_POST['status_aktif'] ?? '');

    if ($nip == '' || $nama == '' || $email == '' || $status_aktif == '') {
        header("Location: guru.php?pesan=kosong");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: guru.php?pesan=email");
        exit;
    }

    $sql = "INSERT INTO guru (nip, nama, email, status_aktif)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $sql);

    if (!$stmt) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "ssss",
        $nip, $nama, $email, $status_aktif
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: guru.php?pesan=berhasil");
        exit;
    } else {
        die("Gagal menyimpan data: " . mysqli_stmt_error($stmt));
    }
}

// AMBIL DATA GURU
$sqlguru = "SELECT * FROM guru ORDER BY nip DESC";
$hasil = mysqli_query($koneksi, $sqlguru);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// STATISTIK
$totalGuru = mysqli_num_rows($hasil);

$queryAktif = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM guru WHERE status_aktif='Aktif'"
);

$totalAktif = mysqli_fetch_assoc($queryAktif)['total'] ?? 0;
$totalTidakAktif = $totalGuru - $totalAktif;
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Kelola Guru</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>

body {
    background: #f3f5ff;
    font-family: Arial, sans-serif;
    color: #202f4d;
}

.content {
    padding: 25px;
    min-height: 100vh;
}

.page-title {
    font-size: 24px;
    font-weight: bold;
    color: #202f4d;
}

.page-subtitle {
    color: #8190b0;
    font-size: 13px;
}

.card {
    border: none;
    border-radius: 13px;
    background: #fff;
    box-shadow: 0 5px 20px rgba(40,55,100,.05);
}

.card-header {
    background: #fff;
    border-bottom: 1px solid #f0f1f7;
    padding: 18px 22px;
    font-weight: bold;
    color: #202f4d;
    border-radius: 13px 13px 0 0 !important;
}

.card-body {
    padding: 22px;
}

.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 13px;
    box-shadow: 0 5px 20px rgba(40,55,100,.05);
}

.stat-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 12px;
    font-size: 18px;
}

.purple {
    background: linear-gradient(135deg,#7774ff,#8b5cf6);
}

.blue {
    background: linear-gradient(135deg,#369cf5,#55b5ff);
}

.orange {
    background: linear-gradient(135deg,#ff9a45,#ffbd70);
}

.stat-number {
    font-size: 25px;
    font-weight: bold;
    color: #202f4d;
}

.stat-label {
    font-size: 12px;
    color: #8190b0;
}

.btn-primary {
    background: #5865ed;
    border-color: #5865ed;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #4654d9;
    border-color: #4654d9;
}

.btn {
    font-size: 13px;
}

.table {
    color: #53627e;
    font-size: 13px;
}

.table thead th {
    background: #f5f6fc;
    color: #344767;
    border-color: #edf0f6;
    padding: 14px;
    white-space: nowrap;
}

.table tbody td {
    padding: 13px;
    vertical-align: middle;
    border-color: #edf0f6;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: #fafbff;
}

.badge-success {
    background: #e4f8ec;
    color: #209653;
    padding: 7px 12px;
    border-radius: 20px;
}

.badge-secondary {
    background: #edf0f5;
    color: #667085;
    padding: 7px 12px;
    border-radius: 20px;
}

.modal-content {
    border: none;
    border-radius: 13px;
}

.modal-header {
    border-bottom: 1px solid #edf0f6;
}

.modal-footer {
    border-top: 1px solid #edf0f6;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e0e4ef;
    font-size: 13px;
    height: 42px;
}

.form-control:focus {
    border-color: #5865ed;
    box-shadow: 0 0 0 3px rgba(88,101,237,.1);
}

label {
    font-size: 13px;
    font-weight: 600;
    color: #344767;
}

.dataTables_wrapper {
    font-size: 13px;
}

.dataTables_filter input,
.dataTables_length select {
    border: 1px solid #e0e4ef;
    border-radius: 7px;
    padding: 5px;
}

.page-item.active .page-link {
    background: #5865ed;
    border-color: #5865ed;
}

.page-link {
    color: #5865ed;
}

@media(max-width:768px) {
    .content {
        padding: 15px;
    }
}

@media print {
    .sidebar, .no-print {
        display: none !important;
    }

    main {
        width: 100% !important;
        max-width: 100% !important;
    }
}

</style>
</head>

<body>

<div class="container-fluid">
<div class="row">

<?php include 'layout/sidebar.php'; ?>

<main class="col-md-9 col-lg-10 ml-sm-auto content">

    <!-- HEADER -->
    <div class="mb-4">

        <h3 class="page-title">
            Data Guru
        </h3>

        <p class="page-subtitle">
            Selamat datang di halaman pengelolaan data guru sekolah.
        </p>

    </div>

    <!-- NOTIFIKASI -->
    <?php if (isset($_GET['pesan'])) { ?>

        <?php if ($_GET['pesan'] == 'berhasil') { ?>

            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i>
                Data guru berhasil ditambahkan!
                <button type="button" class="close" data-dismiss="alert">
                    &times;
                </button>
            </div>

        <?php } elseif ($_GET['pesan'] == 'kosong') { ?>

            <div class="alert alert-warning">
                Semua kolom wajib diisi!
            </div>

        <?php } elseif ($_GET['pesan'] == 'email') { ?>

            <div class="alert alert-warning">
                Format email tidak valid!
            </div>

        <?php } ?>

    <?php } ?>

    <!-- STATISTIK -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">
            <div class="stat-card">

                <div class="stat-icon purple">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>

                <div class="stat-number">
                    <?= $totalGuru; ?>
                </div>

                <div class="stat-label">
                    Total Data Guru
                </div>

            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card">

                <div class="stat-icon blue">
                    <i class="fas fa-user-check"></i>
                </div>

                <div class="stat-number">
                    <?= $totalAktif; ?>
                </div>

                <div class="stat-label">
                    Guru Aktif
                </div>

            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card">

                <div class="stat-icon orange">
                    <i class="fas fa-user-times"></i>
                </div>

                <div class="stat-number">
                    <?= $totalTidakAktif; ?>
                </div>

                <div class="stat-label">
                    Guru Tidak Aktif
                </div>

            </div>
        </div>

    </div>

    <!-- TABEL GURU -->
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <span>
                <i class="fas fa-users mr-2" style="color:#7774ff;"></i>
                Data Guru
            </span>

            <button
                class="btn btn-primary btn-sm no-print"
                data-toggle="modal"
                data-target="#modalTambah">

                <i class="fas fa-plus mr-1"></i>
                Tambah Guru

            </button>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="tabelGuru" class="table table-bordered table-striped">

                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($hasil)) {
                    ?>

                        <tr>

                            <td class="text-center">
                                <?= $no++; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['nip'] ?? '-'); ?>
                            </td>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($data['nama'] ?? '-'); ?>
                                </strong>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['email'] ?? '-'); ?>
                            </td>

                            <td class="text-center">

                                <?php if (($data['status_aktif'] ?? '') == 'Aktif') { ?>

                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>

                                <?php } else { ?>

                                    <span class="badge badge-secondary">
                                        <?= htmlspecialchars($data['status_aktif'] ?? '-'); ?>
                                    </span>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- INFORMASI -->
    <div class="card mt-4">

        <div class="card-body">

            <h6 class="font-weight-bold mb-3">
                <i class="fas fa-lightbulb mr-2" style="color:#7774ff;"></i>
                Informasi Pengelolaan Guru
            </h6>

            <p class="text-muted mb-0" style="font-size:13px;line-height:1.8;">
                Halaman ini digunakan untuk mengelola informasi guru sekolah.
                Pastikan NIP, nama, email, dan status guru sudah benar
                sebelum menyimpan data.
            </p>

        </div>

    </div>

</main>
</div>
</div>

<!-- MODAL TAMBAH GURU -->
<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" action="guru.php">

                <div class="modal-header">

                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-user-plus mr-2" style="color:#5865ed;"></i>
                        Tambah Guru
                    </h5>

                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>NIP</label>
                        <input
                            type="text"
                            name="nip"
                            class="form-control"
                            placeholder="Masukkan NIP"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Nama Guru</label>
                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            placeholder="Masukkan nama guru"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Masukkan email"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Status Aktif</label>

                        <select name="status_aktif" class="form-control" required>

                            <option value="">-- Pilih Status --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>

                        </select>
                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        name="tambah"
                        class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {

    $('#tabelGuru').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50],

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            zeroRecords: "Data tidak ditemukan",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data",

            infoFiltered: "(difilter dari _MAX_ total data)",

            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }

        }

    });

});
</script>

</body>
</html>
