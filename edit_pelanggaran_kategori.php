
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// ============================
// PROSES TAMBAH KATEGORI
// ============================
if (isset($_POST['tambah'])) {

    $nama = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '') {
        header("Location: kelola_pelanggaran_kategori.php?pesan=kosong");
        exit;
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO t_pelanggaran_kategori (nama_kategori, deskripsi)
         VALUES (?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "ss", $nama, $deskripsi);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: kelola_pelanggaran_kategori.php?pesan=tambah");
    } else {
        header("Location: kelola_pelanggaran_kategori.php?pesan=gagal");
    }
    exit;
}

// ============================
// PROSES EDIT KATEGORI
// ============================
if (isset($_POST['edit'])) {

    $id = (int)($_POST['id'] ?? 0);
    $nama = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($id <= 0 || $nama === '') {
        header("Location: kelola_pelanggaran_kategori.php?pesan=kosong");
        exit;
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE t_pelanggaran_kategori
         SET nama_kategori = ?, deskripsi = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "ssi", $nama, $deskripsi, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: kelola_pelanggaran_kategori.php?pesan=edit");
    } else {
        header("Location: kelola_pelanggaran_kategori.php?pesan=gagal");
    }
    exit;
}

// ============================
// PROSES HAPUS KATEGORI
// ============================
if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    if ($id > 0) {

        $stmt = mysqli_prepare(
            $koneksi,
            "DELETE FROM t_pelanggaran_kategori WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_pelanggaran_kategori.php?pesan=hapus");
        } else {
            header("Location: kelola_pelanggaran_kategori.php?pesan=gagal");
        }
        exit;
    }
}

// ============================
// AMBIL DATA KATEGORI
// ============================
$sql = "SELECT * FROM t_pelanggaran_kategori ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// ============================
// NOTIFIKASI
// ============================
$pesan = $_GET['pesan'] ?? '';

$notifikasi = [
    'tambah' => ['success', 'Kategori pelanggaran berhasil ditambahkan.'],
    'edit'   => ['success', 'Kategori pelanggaran berhasil diperbarui.'],
    'hapus'  => ['success', 'Kategori pelanggaran berhasil dihapus.'],
    'gagal'  => ['danger', 'Terjadi kesalahan saat memproses data.'],
    'kosong' => ['warning', 'Nama kategori wajib diisi.']
];
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Kelola Kategori Pelanggaran - Sistem Pelanggaran Siswa</title>

<!-- BOOTSTRAP -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

<!-- FONT AWESOME -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>

body {
    background: #f4f6ff;
    font-family: Arial, sans-serif;
}

/* CONTENT */
.content {
    padding: 25px;
    min-height: 100vh;
}

/* JUDUL */
.page-title {
    color: #202f4d;
    font-size: 23px;
    font-weight: bold;
    margin-bottom: 5px;
}

.page-subtitle {
    color: #8995aa;
    font-size: 13px;
}

/* BANNER */
.hero-card {
    background: linear-gradient(120deg, #5265f5, #8054e9);
    border-radius: 15px;
    padding: 25px 22px;
    color: white;
    margin-bottom: 18px;
    box-shadow: 0 5px 15px rgba(83, 91, 230, .15);
}

.hero-label {
    display: inline-block;
    background: rgba(255,255,255,.2);
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: bold;
    margin-bottom: 10px;
}

.hero-card h5 {
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 5px;
}

.hero-card p {
    font-size: 12px;
    margin-bottom: 0;
}

/* DAFTAR */
.section-title {
    color: #202f4d;
    font-size: 17px;
    font-weight: bold;
    margin-bottom: 5px;
}

.section-subtitle {
    color: #8995aa;
    font-size: 12px;
}

/* BUTTON */
.btn-primary {
    background: #5265ed;
    border-color: #5265ed;
    border-radius: 8px;
}

.btn-primary:hover {
    background: #4053d5;
    border-color: #4053d5;
}

.btn-sm {
    border-radius: 5px;
}

/* CARD */
.card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(30, 50, 90, .05);
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

/* TABLE */
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

.table-bordered {
    border-color: #dce1eb;
}

/* DATATABLES */
.dataTables_wrapper {
    font-size: 11px;
    color: #344767;
}

.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #dce1eb;
    border-radius: 5px;
    padding: 5px;
    outline: none;
}

.dataTables_wrapper .page-link {
    color: #5265ed;
}

.dataTables_wrapper .page-item.active .page-link {
    background: #5265ed;
    border-color: #5265ed;
    color: white;
}

/* MODAL */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,.1);
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

@media (max-width: 768px) {
    .content {
        padding: 15px;
    }

    .hero-card {
        padding: 20px;
    }
}

</style>

</head>

<body>

<div class="container-fluid">

<div class="row">

    <!-- SIDEBAR -->
    <?php include 'layout/sidebar.php'; ?>

    <main class="col-md-9 col-lg-10 ml-sm-auto content">

        <!-- HEADER -->
        <div class="mb-3">

            <h3 class="page-title">
                Kelola Kategori Pelanggaran
            </h3>

            <p class="page-subtitle">
                Kelola dan pantau seluruh data kategori pelanggaran siswa.
            </p>

        </div>

        <!-- BANNER -->
        <div class="hero-card">

            <span class="hero-label">
                <i class="fas fa-heart mr-1"></i>
                DATA KATEGORI
            </span>

            <h5>
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Manajemen Kategori Pelanggaran
            </h5>

            <p>
                Halaman untuk melihat, mencari, menambahkan, mengubah,
                dan menghapus data kategori pelanggaran siswa.
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

                <h5 class="section-title">
                    Daftar Kategori Pelanggaran
                </h5>

                <p class="section-subtitle mb-0">
                    Data kategori pelanggaran yang terdaftar dalam sistem.
                </p>

            </div>

            <button
                type="button"
                class="btn btn-primary btn-sm px-3"
                data-toggle="modal"
                data-target="#modalTambah">

                <i class="fas fa-plus mr-1"></i>
                Tambah Kategori

            </button>

        </div>

        <!-- TABEL DATA -->
        <div class="card">

            <div class="card-header">

                <i class="fas fa-list-ul mr-2" style="color:#8060ed;"></i>
                Tabel Data Kategori Pelanggaran

                <p class="section-subtitle mt-2 mb-0">
                    Gunakan kolom pencarian untuk menemukan data kategori pelanggaran.
                </p>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tabelKategori" class="table table-bordered table-striped">

                        <thead class="text-center">

                            <tr>
                                <th width="60">ID</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th width="130">Aksi</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($data = mysqli_fetch_assoc($hasil)) { ?>

                                <tr>

                                    <td class="text-center">
                                        <?= (int)$data['id']; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['nama_kategori'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($data['deskripsi'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                                    </td>

                                    <td class="text-center">

                                        <!-- EDIT -->
                                        <button
                                            type="button"
                                            class="btn btn-info btn-sm"
                                            data-toggle="modal"
                                            data-target="#modalEdit"
                                            data-id="<?= (int)$data['id']; ?>"
                                            data-nama="<?= htmlspecialchars($data['nama_kategori'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                            data-deskripsi="<?= htmlspecialchars($data['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                                            <i class="fas fa-edit"></i>
                                            Edit

                                        </button>

                                        <!-- HAPUS -->
                                        <a
                                            href="kelola_pelanggaran_kategori.php?hapus=<?= (int)$data['id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')">

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

        <!-- FOOTER -->
        <div class="footer-text">
            Sistem Pelanggaran Siswa © 2026
        </div>

    </main>

</div>
</div>


<!-- ================================= -->
<!-- MODAL TAMBAH KATEGORI -->
<!-- ================================= -->

<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="kelola_pelanggaran_kategori.php">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Kategori Pelanggaran
                    </h5>

                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Nama Kategori</label>

                        <input
                            type="text"
                            name="nama_kategori"
                            class="form-control"
                            placeholder="Masukkan nama kategori"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Deskripsi</label>

                        <textarea
                            name="deskripsi"
                            class="form-control"
                            rows="3"
                            placeholder="Masukkan deskripsi kategori"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" name="tambah" class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ================================= -->
<!-- MODAL EDIT KATEGORI -->
<!-- ================================= -->

<div class="modal fade" id="modalEdit" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST" action="kelola_pelanggaran_kategori.php">

                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Kategori Pelanggaran
                    </h5>

                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>

                </div>

                <div class="modal-body">

                    <div class="form-group">

                        <label>Nama Kategori</label>

                        <input
                            type="text"
                            name="nama_kategori"
                            id="edit_nama"
                            class="form-control"
                            placeholder="Masukkan nama kategori"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Deskripsi</label>

                        <textarea
                            name="deskripsi"
                            id="edit_deskripsi"
                            class="form-control"
                            rows="3"
                            placeholder="Masukkan deskripsi kategori"></textarea>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" name="edit" class="btn btn-primary">

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
    $('#tabelKategori').DataTable({

        pageLength: 10,
        ordering: true,
        autoWidth: false,

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari kategori...",
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

    // ISI FORM MODAL EDIT
    $('#modalEdit').on('show.bs.modal', function(event) {

        var button = $(event.relatedTarget);

        $('#edit_id').val(button.attr('data-id'));
        $('#edit_nama').val(button.attr('data-nama'));
        $('#edit_deskripsi').val(button.attr('data-deskripsi'));

    });

});

</script>

</body>
</html>
