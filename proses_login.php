<?php
// proses_login.php
session_start();
require_once 'config/koneksi.php';

// Pastikan form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Ambil input
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validasi input
if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = 'Email dan password wajib diisi!';
    header('Location: login.php');
    exit;
}

// Cari akun berdasarkan email
$sql = "SELECT * FROM t_users WHERE email = ?";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die('Gagal menyiapkan query login: ' . mysqli_error($koneksi));
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
if (!isset($data['password']) ||
    !password_verify($password, $data['password'])) {

    $_SESSION['pesan_error'] = 'Password salah!';
    header('Location: login.php');
    exit;
}

// Simpan session setelah login berhasil
session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['id'] = $data['id'];
$_SESSION['nama'] = $data['name'];
$_SESSION['nama_lengkap'] = $data['name'];
$_SESSION['role'] = $data['role'] ?? 'guru';

// Catat aktivitas login jika tabel dan kolom log sesuai
// dengan struktur database yang digunakan.
if (isset($data['id_user'])) {
    $id_user = (int) $data['id_user'];
    $aktivitas = 'login';
    $waktu = date('Y-m-d H:i:s');

    $sql_log = "INSERT INTO tbl_log (id_user, aktivitas, waktu)
                VALUES (?, ?, ?)";

    $stmt_log = mysqli_prepare($koneksi, $sql_log);

    if ($stmt_log) {
        mysqli_stmt_bind_param(
            $stmt_log,
            'iss',
            $id_user,
            $aktivitas,
            $waktu
        );

        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);
    }
}

// Login berhasil
header('Location: dashboard.php');
exit;
?>