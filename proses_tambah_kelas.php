
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tambah_kelas.php");
    exit;
}

$nama = trim($_POST['nama'] ?? '');

if ($nama === '') {
    echo "<script>
        alert('Nama kelas wajib diisi!');
        window.location='tambah_kelas.php';
    </script>";
    exit;
}

// Cek nama kelas
$stmtCek = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_kelas WHERE nama = ? LIMIT 1"
);

mysqli_stmt_bind_param($stmtCek, "s", $nama);
mysqli_stmt_execute($stmtCek);

$hasilCek = mysqli_stmt_get_result($stmtCek);

if (mysqli_num_rows($hasilCek) > 0) {
    echo "<script>
        alert('Nama kelas sudah terdaftar!');
        window.location='tambah_kelas.php';
    </script>";
    mysqli_stmt_close($stmtCek);
    exit;
}

mysqli_stmt_close($stmtCek);

// Simpan kelas
$stmt = mysqli_prepare(
    $koneksi,
    "INSERT INTO t_kelas (nama) VALUES (?)"
);

mysqli_stmt_bind_param($stmt, "s", $nama);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>
        alert('Data kelas berhasil ditambahkan!');
        window.location='kelola_kelas.php';
    </script>";
} else {
    echo "<script>
        alert('Data kelas gagal ditambahkan: " . addslashes(mysqli_error($koneksi)) . "');
        window.location='tambah_kelas.php';
    </script>";
}

mysqli_stmt_close($stmt);
mysqli_close($koneksi);
exit;
?>
