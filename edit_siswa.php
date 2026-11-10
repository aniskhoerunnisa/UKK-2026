
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// ============================
// PROSES TAMBAH SISWA
// ============================
if (isset($_POST['tambah'])) {

    $nis = trim($_POST['nis'] ?? '');
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    if (
        $nis == '' || $nisn == '' || $nama == '' ||
        !in_array($jenis_kelamin, ['L', 'P'], true) ||
        $tanggal_lahir == '' || $alamat == '' ||
        !in_array($status_aktif, ['Aktif', 'Tidak Aktif'], true)
    ) {
        header("Location: kelola_siswa.php?pesan=kosong");
        exit;
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_siswa
        (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $nis, $nisn, $nama, $jenis_kelamin,
        $tanggal_lahir, $alamat, $status_aktif
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: kelola_siswa.php?pesan=tambah");
    } else {
        mysqli_stmt_close($stmt);
        header("Location: kelola_siswa.php?pesan=gagal");
    }
    exit;
}

// ============================
// PROSES EDIT SISWA
// ============================
if (isset($_POST['update'])) {

    $id = (int)($_POST['id'] ?? 0);
    $nis = trim($_POST['nis'] ?? '');
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    if (
        $id <= 0 || $nis == '' || $nisn == '' || $nama == '' ||
        !in_array($jenis_kelamin, ['L', 'P'], true) ||
        $tanggal_lahir == '' || $alamat == '' ||
        !in_array($status_aktif, ['Aktif', 'Tidak Aktif'], true)
    ) {
        header("Location: kelola_siswa.php?pesan=kosong");
        exit;
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE t_siswa SET
        nis = ?,
        nisn = ?,
        nama = ?,
        jenis_kelamin = ?,
        tanggal_lahir = ?,
        alamat = ?,
        status_aktif = ?
        WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssssi",
        $nis, $nisn, $nama, $jenis_kelamin,
        $tanggal_lahir, $alamat, $status_aktif, $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: kelola_siswa.php?pesan=edit");
    } else {
        mysqli_stmt_close($stmt);
        header("Location: kelola_siswa.php?pesan=gagal");
    }
    exit;
}

// ============================
// PROSES HAPUS SISWA
// ============================
if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    if ($id > 0) {

        $stmt = mysqli_prepare(
            $koneksi,
            "DELETE FROM t_siswa WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: kelola_siswa.php?pesan=hapus");
        } else {
            mysqli_stmt_close($stmt);
            header("Location: kelola_siswa.php?pesan=gagal");
        }
        exit;
    }
}

// ============================
// AMBIL DATA SISWA
// ============================
$sql = "SELECT * FROM t_siswa ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// ============================
// NOTIFIKASI
// ============================
$pesan = $_GET['pesan'] ?? '';

$notifikasi = [
    'tambah' => ['success', 'Data siswa berhasil ditambahkan.'],
    'edit' => ['success', 'Data siswa berhasil diperbarui.'],
    'hapus' => ['success', 'Data siswa berhasil dihapus.'],
    'gagal' => ['danger', 'Gagal memproses data siswa. Data mungkin masih digunakan oleh data lain.'],
    'kosong' => ['warning', 'Semua kolom wajib diisi dengan benar.']
];
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Kelola Siswa - Sistem Pelanggaran Siswa</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>

body {
    background: #f4f6ff;
    font-family: Arial, sans-serif;
}

.content {
    padding: 25px;
    min-height: 100vh;
}

.page-title {
    color: #202f4d;
    font-size: 23px;
    font-weight: bold;
}

.page-subtitle {
    color: #8995aa;
    font-size: 13px;
}

.hero-card {
    background: linear-gradient(120deg, #5265f5, #8054e9);
    border-radius: 15px;
    padding: 25px 22px;
    color: white;
    margin-bottom: 18px;
    box-shadow: 0 5px 15px rgba(83,91,230,.15);
}

.hero-label {
    display: inline-block;
    background: rgba(255,255,255,.2);
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    margin-bottom: 10px;
}

.hero-card h5 {
    font-size: 17px;
    font-weight: bold;
}

.hero-card p {
    font-size: 12px;
    margin-bottom: 0;
}

.section-title {
    color: #202f4d;
    font-size: 17px;
    font-weight: bold;
}

.section-subtitle {
    color: #8995aa;
    font-size: 12px;
}

.card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(30,50,90,.05);
}

.card-header {
    background: white;
    border: none;
    padding: 18px 20px 5px;
    color: #253653;
    font-weight: bold;
}

.card-body {
    padding: 15px 16px;
}

.btn-primary {
    background: #5265ed;
    border-color: #5265ed;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #4053d5;
    border-color: #4053d5;
}

.table {
    font-size: 12px;
    color: #344767;
}

.table thead th {
    background: #202e4d;
    color: white;
    border-color: #344767;
    vertical-align: middle;
    white-space: nowrap;
    padding: 10px;
}

.table tbody td {
    vertical-align: middle;
    padding: 10px;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: #f1f1f1;
}

.dataTables_wrapper {
    font-size: 11px;
    color: #344767;
}

.dataTables_wrapper input,
.dataTables_wrapper select {
    border: 1px solid #dce1eb;
    border-radius: 5px;
    padding: 5px;
}

.dataTables_wrapper .page-link {
    color: #5265ed;
}

.dataTables_wrapper .page-item.active .page-link {
    background: #5265ed;
    border-color: #5265ed;
}

.modal-content {
    border: none;
    border-radius: 12px;
}

.modal-header {
    background: #f8f9ff;
}

.modal-title {
    color: #253653;
    font-weight: bold;
    font-size: 17px;
}

.form-control {
    border-radius: 6px;
}

.footer-text {
    color: #8995aa;
    font-size: 11px;
    margin-top: 20px;
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

<?php include 'layout/sidebar.php'; ?>

<main class="col-md-9 col-lg-10 ml-sm-auto content">

    <!-- HEADER -->
    <div class="mb-3">

        <h3 class="page-title">Kelola Siswa</h3>

        <p class="page-subtitle">
            Kelola dan pantau seluruh data siswa di sekolah.
        </p>

    </div>

    <!-- BANNER -->
    <div class="hero-card">

        <span class="hero-label">
            <i class="fas fa-heart mr-1"></i>
            DATA SISWA
        </span>

        <h5>
            <i class="fas fa-user-graduate mr-1"></i>
            Manajemen Data Siswa
        </h5>

        <p>
            Halaman untuk melihat, mencari, menambahkan,
            mengubah, dan menghapus data siswa.
        </p>

    </div>

    <!-- NOTIFIKASI -->
    <?php if (isset($notifikasi[$pesan])) { ?>

        <div class="alert alert-<?= $notifikasi[$pesan][0]; ?> alert-dismissible fade show">

            <?= htmlspecialchars($notifikasi[$pesan][1]); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>

        </div>

    <?php } ?>

    <!-- JUDUL DAFTAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h5 class="section-title mb-1">
                Daftar Siswa
            </h5>

            <p class="section-subtitle mb-0">
                Data siswa yang terdaftar dalam sistem.
            </p>

        </div>

        <button type="button"
                class="btn btn-primary btn-sm px-3"
                data-toggle="modal"
                data-target="#modalTambah">

            <i class="fas fa-plus mr-1"></i>
            Tambah Siswa

        </button>

    </div>

    <!-- TABEL -->
    <div class="card">

        <div class="card-header">

            <i class="fas fa-list-ul mr-2" style="color:#8060ed;"></i>
            Tabel Data Siswa

            <p class="section-subtitle mt-2 mb-0">
                Gunakan kolom pencarian untuk menemukan data siswa.
            </p>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table id="tabelSiswa" class="table table-bordered table-striped">

                    <thead class="text-center">

                        <tr>
                            <th>ID</th>
                            <th>NIS</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Tanggal Lahir</th>
                            <th>Alamat</th>
                            <th>Status Aktif</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                        <tr>

                            <td class="text-center">
                                <?= (int)$row['id']; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nis'] ?? '-'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nisn'] ?? '-'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama'] ?? '-'); ?>
                            </td>

                            <td class="text-center">
                                <?= ($row['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tanggal_lahir'] ?? '-'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['alamat'] ?? '-'); ?>
                            </td>

                            <td class="text-center">

                                <?php if (($row['status_aktif'] ?? '') == 'Aktif') { ?>

                                    <span class="badge badge-success">Aktif</span>

                                <?php } else { ?>

                                    <span class="badge badge-danger">Tidak Aktif</span>

                                <?php } ?>

                            </td>

                            <td class="text-center">

                                <!-- EDIT -->
                                <button
                                    type="button"
                                    class="btn btn-info btn-sm"
                                    data-toggle="modal"
                                    data-target="#modalEdit"
                                    data-id="<?= (int)$row['id']; ?>"
                                    data-nis="<?= htmlspecialchars($row['nis'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nisn="<?= htmlspecialchars($row['nisn'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-nama="<?= htmlspecialchars($row['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-jk="<?= htmlspecialchars($row['jenis_kelamin'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-tanggal="<?= htmlspecialchars($row['tanggal_lahir'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-alamat="<?= htmlspecialchars($row['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                    data-status="<?= htmlspecialchars($row['status_aktif'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                    <i class="fas fa-edit"></i>
                                    Edit

                                </button>

                                <!-- HAPUS -->
                                <a href="kelola_siswa.php?hapus=<?= (int)$row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus data siswa ini?')">

                                    <i class="fas fa-trash"></i>
                                    Hapus

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="footer-text">
        Sistem Pelanggaran Siswa © 2026
    </div>

</main>

</div>
</div>


<!-- ========================= -->
<!-- MODAL TAMBAH SISWA -->
<!-- ========================= -->

<div class="modal fade" id="modalTambah" tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<form method="POST" action="kelola_siswa.php">

    <div class="modal-header">

        <h5 class="modal-title">
            <i class="fas fa-user-plus mr-2"></i>
            Tambah Data Siswa
        </h5>

        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>

    </div>

    <div class="modal-body">

        <div class="form-row">

            <div class="form-group col-md-6">
                <label>NIS</label>
                <input type="text" name="nis" class="form-control"
                       placeholder="Masukkan NIS" required>
            </div>

            <div class="form-group col-md-6">
                <label>NISN</label>
                <input type="text" name="nisn" class="form-control"
                       placeholder="Masukkan NISN" required>
            </div>

        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                   placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Jenis Kelamin</label>

                <select name="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

            </div>

            <div class="form-group col-md-6">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir"
                       class="form-control" required>
            </div>

        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control"
                      rows="3" placeholder="Masukkan alamat" required></textarea>
        </div>

        <div class="form-group">

            <label>Status Aktif</label>

            <select name="status_aktif" class="form-control" required>
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>

        </div>

    </div>

    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Batal
        </button>

        <button type="submit" name="tambah" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i>
            Simpan Siswa
        </button>

    </div>

</form>

</div>
</div>
</div>


<!-- ========================= -->
<!-- MODAL EDIT SISWA -->
<!-- ========================= -->

<div class="modal fade" id="modalEdit" tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<form method="POST" action="kelola_siswa.php">

    <input type="hidden" name="id" id="edit_id">

    <div class="modal-header">

        <h5 class="modal-title">
            <i class="fas fa-user-edit mr-2"></i>
            Edit Data Siswa
        </h5>

        <button type="button" class="close" data-dismiss="modal">
            &times;
        </button>

    </div>

    <div class="modal-body">

        <div class="form-row">

            <div class="form-group col-md-6">
                <label>NIS</label>
                <input type="text" name="nis" id="edit_nis"
                       class="form-control" required>
            </div>

            <div class="form-group col-md-6">
                <label>NISN</label>
                <input type="text" name="nisn" id="edit_nisn"
                       class="form-control" required>
            </div>

        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" id="edit_nama"
                   class="form-control" required>
        </div>

        <div class="form-row">

            <div class="form-group col-md-6">

                <label>Jenis Kelamin</label>

                <select name="jenis_kelamin" id="edit_jk"
                        class="form-control" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>

            </div>

            <div class="form-group col-md-6">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir"
                       id="edit_tanggal" class="form-control" required>
            </div>

        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" id="edit_alamat"
                      class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">

            <label>Status Aktif</label>

            <select name="status_aktif" id="edit_status"
                    class="form-control" required>
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>

        </div>

    </div>

    <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Batal
        </button>

        <button type="submit" name="update" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i>
            Simpan Perubahan
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

    // DATATABLES
    $('#tabelSiswa').DataTable({

        pageLength: 10,
        ordering: true,
        autoWidth: false,

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari data siswa...",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ total data)",

            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }

    });

    // ISI MODAL EDIT
    $('#modalEdit').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);

        $('#edit_id').val(button.attr('data-id'));
        $('#edit_nis').val(button.attr('data-nis'));
        $('#edit_nisn').val(button.attr('data-nisn'));
        $('#edit_nama').val(button.attr('data-nama'));
        $('#edit_jk').val(button.attr('data-jk'));
        $('#edit_tanggal').val(button.attr('data-tanggal'));
        $('#edit_alamat').val(button.attr('data-alamat'));
        $('#edit_status').val(button.attr('data-status'));

    });

});

</script>

</body>
</html>
