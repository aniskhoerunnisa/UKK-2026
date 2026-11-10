<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_wali_kelas.php");
    exit;
}

$tahun_ajaran_id = $_POST['tahun_ajaran_id'] ?? '';
$kelas_id        = $_POST['kelas_id'] ?? '';
$guru_id         = $_POST['guru_id'] ?? '';
$tanggal_mulai   = $_POST['tanggal_mulai'] ?? '';
$tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
$status_aktif    = $_POST['status_aktif'] ?? '';

if (
    $tahun_ajaran_id === '' ||
    $kelas_id === '' ||
    $guru_id === '' ||
    $tanggal_mulai === '' ||
    $tanggal_selesai === '' ||
    $status_aktif === ''
) {
    exit("Gagal! Ada data yang belum terkirim dari form tambah wali kelas.");
}

if ($tanggal_selesai < $tanggal_mulai) {
    exit("Tanggal selesai tidak boleh lebih awal dari tanggal mulai.");
}

$sql = "INSERT INTO t_wali_kelas
        (tahun_ajaran_id, kelas_id, guru_id,
         tanggal_mulai, tanggal_selesai, status_aktif)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    exit("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iiissi",
    $tahun_ajaran_id,
    $kelas_id,
    $guru_id,
    $tanggal_mulai,
    $tanggal_selesai,
    $status_aktif
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: data_wali_kelas.php");
    exit;
} else {
    echo "Gagal menambahkan wali kelas: " . mysqli_stmt_error($stmt);
}
?>