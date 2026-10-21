<?php
session_start();

$error = '';

if (isset($_SESSION['pesan_error'])) {
    $error = $_SESSION['pesan_error'];
    unset($_SESSION['pesan_error']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - Sistem Pelanggaran Siswa</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f3f5fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            color: #243b65;
        }

        .subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
        }

        .form-control {
            height: 45px;
            border-radius: 8px;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            background: #5368ed;
            color: white;
            font-weight: bold;
            border: none;
        }

        .btn-login:hover {
            background: #4054d6;
            color: white;
        }
    </style>
</head>
<body>

<div class="login-card">

    <h2>Login</h2>
    <p class="subtitle">Sistem Pelanggaran Siswa</p>

    <?php if ($error !== '') { ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php } ?>

    <form action="proses_login.php" method="POST">

        <div class="form-group">
            <label for="email">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                class="form-control"
                placeholder="Masukkan email"
                autocomplete="username"
                required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control"
                placeholder="Masukkan password"
                autocomplete="current-password"
                required>
        </div>

        <button type="submit" class="btn btn-login">
            Login
        </button>

    </form>

</div>

</body>
</html>