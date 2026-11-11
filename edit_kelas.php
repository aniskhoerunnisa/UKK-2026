
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID kelas tidak valid.");
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_kelas WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data kelas tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container-edit {
            max-width: 850px;
            margin: 45px auto;
            padding: 0 15px;
        }

        .header-edit {
            background: linear-gradient(135deg, #5965d8, #9365df);
            color: white;
            padding: 28px 30px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .header-edit h3 {
            font-weight: bold;
            margin-bottom: 7px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        }

        .card-body {
            padding: 30px;
        }

        .form-control {
            height: 43px;
            border-radius: 7px;
            border: 1px solid #dce0eb;
        }

        .form-control:focus {
            border-color: #7568dc;
            box-shadow: 0 0 0 3px rgba(117,104,220,0.12);
        }

        label {
            font-weight: 600;
            color: #424b60;
        }

        .btn {
            border-radius: 7px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary {
            background: #625fd5;
            border-color: #625fd5;
        }

        .btn-primary:hover {
            background: #514dc0;
            border-color: #514dc0;
        }
    </style>
</head>

<body>

<div class="container-edit">

    <div class="header-edit">
        <h3><i class="fas fa-edit mr-2"></i>Edit Data Kelas</h3>
        <p class="mb-0">Silakan ubah informasi data kelas di bawah ini.</p>
    </div>

    <div class="card">
        <div class="card-body">

            <h5 class="font-weight-bold mb-4">
                <i class="fas fa-school text-primary mr-2"></i>
                Form Edit Kelas
            </h5>

            <form action="proses_edit_kelas.php" method="POST">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($data['id']) ?>">

                <div class="form-group">
                    <label>Nama Kelas</label>
                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="<?= htmlspecialchars($data['nama']) ?>"
                        placeholder="Masukkan nama kelas"
                        required>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="kelola_kelas.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update Kelas
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
