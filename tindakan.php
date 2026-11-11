
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = mysqli_query($koneksi, "
    SELECT * FROM t_pelanggaran_siswa
    WHERE status = 'belum'
    ORDER BY tanggal DESC, id DESC
");

if (!$sql) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tindakan Pelanggaran - MyWebsite</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
body { background:#f4f6ff; font-family:Arial,sans-serif; }
.container-page { margin:35px; background:white; padding:25px; border-radius:12px; box-shadow:0 4px 18px rgba(0,0,0,.06); }
h4 { color:#202b46; font-weight:bold; }
</style>
</head>
<body>

<div class="container-page">
    <h4><i class="fas fa-clipboard-check mr-2"></i> Tindakan Pelanggaran</h4>
    <hr>

    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Jenis Pelanggaran</th>
                <th>Guru Pelapor</th>
                <th>Poin</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
        <?php $no=1; while ($d=mysqli_fetch_assoc($sql)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($d['tanggal']); ?></td>
                <td><?= htmlspecialchars($d['nama_siswa']); ?></td>
                <td><?= htmlspecialchars($d['nama_pelanggaran']); ?></td>
                <td><?= htmlspecialchars($d['nama_guru']); ?></td>
                <td><?= (int)$d['poin']; ?></td>
                <td>
                    <form action="proses_tindakan.php" method="POST"
                          onsubmit="return confirm('Simpan tindakan dan pindahkan ke riwayat?')">
                        <input type="hidden" name="id" value="<?= (int)$d['id']; ?>">
                        <input type="text" name="tindakan" class="form-control mb-2"
                               placeholder="Masukkan tindakan" required>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-check"></i> Selesai
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

</body>
</html>
