<?php

// tambah_siswa.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nis = mysqli_real_escape_string($koneksi, $_POST['nis']);
    $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $status_aktif = mysqli_real_escape_string($koneksi, $_POST['status_aktif']);


    $sqlsiswa = "INSERT INTO t_siswa
            (nis, nisn, nama, jenis_kelamin, tanggal_lahir, alamat, status_aktif)
            VALUES
            ('$nis', '$nisn', '$nama', '$jenis_kelamin', '$tanggal_lahir', '$alamat', '$status_aktif')";

if (mysqli_query($koneksi, $sqlsiswa)) {
    header('Location: kelola_siswa.php');
    exit;
} else {
    $error = "Gagal menambahkan siswa: " . mysqli_error($koneksi);
}
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Siswa - Sistem Pelanggaran Siswa</title>
</head>

<body>

    <h1>Tambah Siswa</h1>

    <a href="kelola_siswa.php">Kembali ke Kelola Siswa</a>

    <br><br>

    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">

        <table>

            <tr>
                <td>NIS</td>
                <td>:</td>
                <td>
                    <input type="text" name="nis" required>
                </td>
            </tr>

            <tr>
                <td>NISN</td>
                <td>:</td>
                <td>
                    <input type="text" name="nisn" required>
                </td>
            </tr>

            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama" required>
                </td>
            </tr>

            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <select name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td>
                    <input type="date" name="tanggal_lahir" required>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <textarea name="alamat" rows="4" cols="30" required></textarea>
                </td>
            </tr>

            <tr>
                <td>Status Aktif</td>
                <td>:</td>
                <td>
                    <select name="status_aktif" required>
                        <option value="">-- Pilih --</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td>
                    <input type="submit" value="Simpan">
                    <input type="reset" value="Reset">
                </td>
            </tr>

        </table>

    </form>

</body>

</html>