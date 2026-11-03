
<?php
// proses_pelanggan_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Cek apakah form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data dari form
    $nama_kategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    // Validasi input
    if (
        $nama_kategori == '' ||
        !in_array($status_aktif, ['0', '1'], true)
    ) {
        echo "<script>
            alert('Data kategori belum lengkap!');
            window.location='tambah_kategori_pelanggan.php';
        </script>";
        exit;
    }

    // Cek apakah kategori sudah ada
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_kategori_pelanggan WHERE nama_kategori = ?"
    );

    mysqli_stmt_bind_param($cek, "s", $nama_kategori);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        echo "<script>
            alert('Kategori pelanggan sudah tersedia!');
            window.location='tambah_kategori_pelanggan.php';
        </script>";
        exit;
    }

    mysqli_stmt_close($cek);

    // Simpan data kategori
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

    if (mysqli_stmt_execute($stmt)) {

        echo "<script>
            alert('Kategori pelanggan berhasil ditambahkan!');
            window.location='kelola_kategori_pelanggan.php';
        </script>";

    } else {

        echo "<script>
            alert('Gagal menyimpan kategori pelanggan!');
            window.location='tambah_kategori_pelanggan.php';
        </script>";

    }

    mysqli_stmt_close($stmt);

} else {

    header("Location: tambah_kategori_pelanggan.php");
    exit;

}
?>
