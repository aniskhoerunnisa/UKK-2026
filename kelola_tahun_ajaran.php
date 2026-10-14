<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_tahun_ajaran ORDER BY nama DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kelola Tahun Ajaran</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Kelola Tahun Ajaran</h3>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <a href="tambah_tahun_ajaran.php" class="btn btn-success">
                    + Tambah Tahun Ajaran
                </a>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover">

                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Tahun Ajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status Aktif</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['id']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['nama']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['tanggal_mulai']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['tanggal_selesai']); ?>
                            </td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>

                                    <span class="badge badge-success">
                                        Aktif
                                    </span>

                                <?php } else { ?>

                                    <span class="badge badge-secondary">
                                        Tidak Aktif
                                    </span>

                                <?php } ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($row['dibuat'] ?? '-');
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($row['diubah'] ?? '-');
                                ?>
                            </td>

                            <td>
                                <a href="edit_tahun_ajaran.php?id=<?php
                                    echo urlencode($row['id']);
                                ?>" class="btn btn-info btn-sm mb-1">
                                    Edit
                                </a>

                                <a href="hapus_tahun_ajaran.php?id=<?php
                                    echo urlencode($row['id']);
                                ?>"
                                   class="btn btn-danger btn-sm mb-1"
                                   onclick="return confirm('Yakin ingin menghapus data tahun ajaran ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer text-muted">
            Sistem Informasi Tahun Ajaran
        </div>
    </div>

</div>

</body>
</html>