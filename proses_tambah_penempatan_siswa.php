
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan data dikirim dari form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_penempatan_siswa.php");
    exit;
}

// Ambil data dari form
$siswa_id = (int) ($_POST['siswa_id'] ?? 0);
$kelas_id = (int) ($_POST['kelas_id'] ?? 0);
$tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

// Validasi input
if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {
    echo "<script>
        alert('Semua data wajib dipilih!');
        window.location='tambah_penempatan_siswa.php';
    </script>";
    exit;
}

// Simpan data penempatan siswa
$sql = "INSERT INTO t_kelas_siswa
        (siswa_id, kelas_id, tahun_ajaran_id)
        VALUES (?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal disiapkan: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iii",
    $siswa_id,
    $kelas_id,
    $tahun_ajaran_id
);

// Jalankan query
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    echo "<script>
        alert('Penempatan siswa berhasil ditambahkan!');
        window.location='penempatan_siswa.php';
    </script>";
    exit;

} else {
    $error = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    echo "Gagal menyimpan data: " . htmlspecialchars($error);
}
?>
