```php
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kategori Pelanggan</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6f9;
        }

        .content {
            padding: 25px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .card-header {
            background: #fff;
            font-weight: bold;
            border-bottom: 1px solid #eee;
        }

        .btn-primary {
            background: #344767;
            border-color: #344767;
        }

        .btn-primary:hover {
            background: #253653;
            border-color: #253653;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 ml-sm-auto content">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3>Tambah Kategori Pelanggan</h3>
                    <p class="text-muted mb-0">
                        Tambahkan data kategori pelanggan baru.
                    </p>
                </div>

                <a href="kelola_kategori_pelanggan.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users mr-2"></i> Form Kategori Pelanggan
                </div>

                <div class="card-body">

                    <form action="proses_tambah_pelanggan_kategori.php" method="POST">

                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input
                                type="text"
                                name="nama_kategori"
                                class="form-control"
                                placeholder="Masukkan nama kategori pelanggan"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea
                                name="deskripsi"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan deskripsi kategori"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Status Aktif</label>
                            <select name="status_aktif" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <hr>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>

                        <a href="kelola_kategori_pelanggan.php" class="btn btn-light">
                            Batal
                        </a>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
```