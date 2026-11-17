
<?php
// proses_login.php
session_start();
require_once 'config/koneksi.php';

// Pastikan form menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Ambil input
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi
if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = 'Email dan password wajib diisi!';
    header('Location: login.php');
    exit;
}

// Cari akun pengguna
$sql = "SELECT * FROM t_users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die('Query login gagal: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

// Periksa akun
if (!$data) {
    $_SESSION['pesan_error'] = 'Email tidak ditemukan!';
    header('Location: login.php');
    exit;
}

// Periksa password
if (!password_verify($password, $data['password'])) {
    $_SESSION['pesan_error'] = 'Password salah!';
    header('Location: login.php');
    exit;
}

// Ambil identitas akun
$id_user = (int)$data['id'];
$nama = $data['name'];
$role = strtolower(trim($data['role'] ?? 'guru'));

// Regenerasi session
session_regenerate_id(true);

// Bersihkan session identitas lama
unset(
    $_SESSION['guru_id'],
    $_SESSION['nama_guru'],
    $_SESSION['id_user']
);

// Simpan session akun
$_SESSION['login'] = true;
$_SESSION['id'] = $id_user;
$_SESSION['id_user'] = $id_user;
$_SESSION['nama'] = $nama;
$_SESSION['nama_lengkap'] = $nama;
$_SESSION['role'] = $role;

// Jika akun guru, cari data guru berdasarkan user_id
if ($role === 'guru') {

    $sql_guru = "
        SELECT id, nama
        FROM t_guru
        WHERE user_id = ?
        LIMIT 1
    ";

    $stmt_guru = mysqli_prepare($koneksi, $sql_guru);

    if (!$stmt_guru) {
        die('Query data guru gagal: ' . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt_guru, 'i', $id_user);
    mysqli_stmt_execute($stmt_guru);

    $hasil_guru = mysqli_stmt_get_result($stmt_guru);
    $guru = mysqli_fetch_assoc($hasil_guru);

    mysqli_stmt_close($stmt_guru);

    if (!$guru) {
        $_SESSION = [];
        session_destroy();

        session_start();
        $_SESSION['pesan_error'] =
            'Akun guru belum terhubung dengan data guru.';

        header('Location: login.php');
        exit;
    }

    // Simpan ID dan nama guru
    $_SESSION['guru_id'] = (int)$guru['id'];
    $_SESSION['nama_guru'] = $guru['nama'];
}

// Login berhasil
header('Location: dashboard.php');
exit;
?>