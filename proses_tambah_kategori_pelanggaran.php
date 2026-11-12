
<?php
// proses_tambah_pelanggan_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data form
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    // Validasi
    if (
        $nama_kategori == '' ||
        !in_array($status_aktif, ['0', '1'], true)
    ) {
        echo "<script>
            alert('Data belum lengkap!');
            window.location='tambah_kategori_pelanggan.php';
        </script>";
        exit;
    }

    // Cek kategori duplikat
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_kategori_pelanggan WHERE nama_kategori = ?"
    );

    mysqli_stmt_bind_param($cek, "s", $nama_kategori);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        mysqli_stmt_close($cek);

        echo "<script>
            alert('Kategori pelanggan sudah terdaftar!');
            window.location='tambah_kategori_pelanggan.php';
        </script>";
        exit;
    }

    mysqli_stmt_close($cek);

    // Query insert
    $sql = "INSERT INTO t_kategori_pelanggan
            (nama_kategori, deskripsi, status_aktif)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $sql);

    if (!$stmt) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $nama_kategori,
        $deskripsi,
        $status_aktif
    );

    // Eksekusi
    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        echo "<script>
            alert('Data kategori pelanggan berhasil disimpan!');
            window.location='kelola_kategori_pelanggan.php';
        </script>";
        exit;

    } else {

        $error = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);

        die("Gagal menyimpan data: " . htmlspecialchars($error));
    }

} else {

    header("Location: tambah_kategori_pelanggan.php");
    exit;

}
?>
