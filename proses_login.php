<?php
// proses_login.php
session_start();
include 'config/koneksi.php';
$email = mysqli_real_escape_string($koneksi, $_POST['email']);
$password = $_POST['password'];

$sql = "SELECT * FROM t_users WHERE  email='$email'";
$query = mysqli_query($koneksi,$sql);

if (mysqli_num_rows($query) == 1) {
    $data = mysqli_fetch_assoc($query);

    if (password_verify($password,$data['password'])) {
        // password cocok, buat session
        $_SESSION['login']        =true;
        $_SESSION['id']           =$data['id'];
        $_SESSION['nama']         =$data['nama'];
        $_SESSION['role']         =$data['role'];

        // catat aktivitas ke tbl_log
        $id_user =$data['id_user'];
        $waktu =date('Y-m-d H:i:s');
        $log ="INSERT INTO tbl_log (id_user,aktivitas,waktu)";
        $log ="VALUES ('$id_user', 'login', '$waktu')";
        mysqli_query($koneksi,$log);

        header('location: dashboard.php');
        exit;
    }else{
        $_SESSION['pesan_error'] = 'password salah!';
        header('location:login.php');
        exit;
    }
}else{ 
    $_SESSION['pesan_error'] ='username tidak ditemukan!';
    header('location: login.php');
    exit;
}
?>