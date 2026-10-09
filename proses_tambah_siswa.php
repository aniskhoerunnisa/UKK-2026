
<?php
// tambah_guru.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $nip = trim($_POST['nip']);
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $alamat = trim($_POST['alamat']);

    // Cek apakah NIP atau email sudah terdaftar
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru WHERE nip = ? OR email = ?"
    );

    mysqli_stmt_bind_param($cek, "ss", $nip, $email);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        $error = "NIP atau email sudah terdaftar!";
    } else {
        mysqli_stmt_close($cek);

        // Password disimpan dalam bentuk hash
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO t_guru
                (nama_lengkap, nip, jenis_kelamin, email, password, alamat)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssssss",
                $nama_lengkap,
                $nip,
                $jenis_kelamin,
                $email,
                $password_hash,
                $alamat
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: kelola_guru.php");
                exit;
            } else {
                $error = "Gagal menyimpan guru: " .
                         mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Query gagal: " . mysqli_error($koneksi);
        }
    }

    if (isset($cek) && $cek instanceof mysqli_stmt) {
        mysqli_stmt_close($cek);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Guru - Sistem Pelanggaran Siswa</title>
</head>
<body>

    <h1>Tambah Guru</h1>

    <a href="kelola_guru.php">Kembali ke Kelola Guru</a>

    <br><br>

    <?php if ($error != "") { ?>
        <p style="color:red;">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <form method="POST">
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>
                    <input type="text" name="nama_lengkap" required>
                </td>
            </tr>

            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>
                    <input type="text" name="nip" required>
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
                <td>Email</td>
                <td>:</td>
                <td>
                    <input type="email" name="email" required>
                </td>
            </tr>

            <tr>
                <td>Password</td>
                <td>:</td>
                <td>
                    <input type="password" name="password"
                           required minlength="8">
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    <textarea name="alamat" rows="4"
                              cols="30" required></textarea>
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