
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_penempatan_siswa.php");
    exit;
}

// Ambil data dari form
$siswa_id = (int)($_POST['siswa_id'] ?? 0);
$kelas_id = (int)($_POST['kelas_id'] ?? 0);
$tahun_ajaran_id = (int)($_POST['tahun_ajaran_id'] ?? 0);

// Validasi data
if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {
    die("Semua data penempatan siswa wajib dipilih.");
}

// Simpan data penempatan
$sql = "INSERT INTO t_kelas_siswa
        (siswa_id, kelas_id, tahun_ajaran_id)
        VALUES (?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iii",
    $siswa_id,
    $kelas_id,
    $tahun_ajaran_id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: penempatan_siswa.php");
    exit;
} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    die("Gagal menyimpan penempatan siswa: " . htmlspecialchars($error));
}
?>
