
<?php
// tambah_wali_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tambah Wali Kelas</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 700px;
            margin-top: 40px;
        }

        .card {
            border: none;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Tambah Wali Kelas</h4>
        </div>

        <div class="card-body">

            <form action="proses_tambah_wali_kelas.php" method="POST">

                <div class="form-group">
                    <label for="nama">Nama Wali Kelas</label>
                    <input type="text"
                           class="form-control"
                           id="nama"
                           name="nama"
                           placeholder="Masukkan nama wali kelas"
                           required>
                </div>

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text"
                           class="form-control"
                           id="nip"
                           name="nip"
                           placeholder="Masukkan NIP"
                           required>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select class="form-control"
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            required>
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="text"
                           class="form-control"
                           id="no_hp"
                           name="no_hp"
                           placeholder="Masukkan nomor HP"
                           required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control"
                              id="alamat"
                              name="alamat"
                              rows="3"
                              placeholder="Masukkan alamat"
                              required></textarea>
                </div>

                <div class="form-group">
                    <label for="status_aktif">Status Aktif</label>
                    <select class="form-control"
                            id="status_aktif"
                            name="status_aktif"
                            required>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="kelola_wali_kelas.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>
