
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = "";

// PROSES HAPUS CATATAN
if (isset($_POST['hapus'])) {
    $id_hapus = (int)($_POST['id'] ?? 0);

    if ($id_hapus > 0) {
        $stmtHapus = mysqli_prepare($koneksi, "
            DELETE FROM t_pelanggaran_siswa
            WHERE id = ?
        ");

        if ($stmtHapus) {
            mysqli_stmt_bind_param($stmtHapus, "i", $id_hapus);

            if (mysqli_stmt_execute($stmtHapus)) {
                mysqli_stmt_close($stmtHapus);
                header("Location: kelola_catat.php?pesan=berhasil_hapus");
                exit;
            } else {
                $pesan = "Gagal menghapus data: " . mysqli_stmt_error($stmtHapus);
                mysqli_stmt_close($stmtHapus);
            }
        } else {
            $pesan = "Query hapus gagal: " . mysqli_error($koneksi);
        }
    }
}

// PESAN BERHASIL
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'berhasil_hapus') {
        $pesan = "Catatan pelanggaran berhasil dihapus.";
    } elseif ($_GET['pesan'] == 'berhasil_edit') {
        $pesan = "Catatan pelanggaran berhasil diperbarui.";
    }
}

// PENCARIAN
$cari = trim($_GET['cari'] ?? '');
$cari_sql = "%" . $cari . "%";

// AMBIL DATA CATATAN PELANGGARAN
$stmt = mysqli_prepare($koneksi, "
    SELECT
        ps.id,
        ps.siswa_id,
        ps.pelanggaran_id,
        ps.tanggal,
        ps.keterangan,
        ps.status,
        s.nis,
        s.nama AS nama_siswa,
        p.nama AS nama_pelanggaran,
        p.poin,
        g.nama AS nama_guru
    FROM t_pelanggaran_siswa ps
    LEFT JOIN t_siswa s ON ps.siswa_id = s.id
    LEFT JOIN t_pelanggaran p ON ps.pelanggaran_id = p.id
    LEFT JOIN t_guru g ON ps.guru_id = g.id
    WHERE
        s.nama LIKE ?
        OR s.nis LIKE ?
        OR p.nama LIKE ?
        OR g.nama LIKE ?
        OR ps.keterangan LIKE ?
    ORDER BY ps.tanggal DESC, ps.id DESC
");

if (!$stmt) {
    die("Query data gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "sssss",
    $cari_sql,
    $cari_sql,
    $cari_sql,
    $cari_sql,
    $cari_sql
);

mysqli_stmt_execute($stmt);
$dataCatat = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kelola Catat Pelanggaran - MyWebsite</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background: #f4f6ff;
            font-family: Arial, sans-serif;
        }

        .content-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(0,0,0,.06);
        }

        .page-title {
            color: #202b46;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #777;
            font-size: 14px;
        }

        .table thead th {
            background: #202b46;
            color: white;
            white-space: nowrap;
            font-size: 13px;
            padding: 13px;
        }

        .table tbody td {
            vertical-align: middle;
            font-size: 13px;
            padding: 12px;
        }

        .btn-tambah {
            background: #5368ed;
            color: white;
            border: none;
            border-radius: 7px;
            padding: 10px 15px;
        }

        .btn-tambah:hover {
            background: #4055d8;
            color: white;
        }

        .search-box {
            max-width: 350px;
        }

        .search-box .form-control {
            min-height: 40px;
        }

        .badge-status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
        }

        .table-responsive {
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <?php include "layout/sidebar.php"; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <div class="content-card">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">

                    <div>
                        <h4 class="page-title">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Kelola Catat Pelanggaran
                        </h4>

                        <div class="page-subtitle">
                            Daftar seluruh catatan pelanggaran siswa.
                        </div>
                    </div>

                    <a href="catat_pelanggaran.php" class="btn btn-tambah">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Catatan
                    </a>

                </div>

                <hr>

                <?php if ($pesan != ""): ?>
                    <div class="alert alert-info alert-dismissible fade show">
                        <?= htmlspecialchars($pesan); ?>
                        <button type="button" class="btn-close"
                                data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="GET" class="mb-3">
                    <div class="input-group search-box">
                        <input type="text"
                               name="cari"
                               class="form-control"
                               placeholder="Cari siswa atau pelanggaran..."
                               value="<?= htmlspecialchars($cari); ?>">

                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>

                        <?php if ($cari != ""): ?>
                            <a href="kelola_catat.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Tanggal</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Guru Pelapor</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                                <th width="130">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $no = 1;

                        if (mysqli_num_rows($dataCatat) > 0):
                            while ($d = mysqli_fetch_assoc($dataCatat)):
                        ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?= !empty($d['tanggal'])
                                        ? date('d-m-Y', strtotime($d['tanggal']))
                                        : '-'; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($d['nis'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($d['nama_siswa'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($d['nama_pelanggaran'] ?? '-'); ?>
                                </td>

                                <td>
                                    <span class="badge bg-danger">
                                        <?= (int)($d['poin'] ?? 0); ?>
                                    </span>
                                </td>

                                <td>
                                    <?= htmlspecialchars($d['nama_guru'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($d['keterangan'] ?? '-'); ?>
                                </td>

                                <td>
                                    <?php
                                    $status = strtolower($d['status'] ?? 'belum');

                                    if ($status == 'selesai') {
                                        echo '<span class="badge bg-success badge-status">Selesai</span>';
                                    } elseif ($status == 'proses') {
                                        echo '<span class="badge bg-warning text-dark badge-status">Proses</span>';
                                    } elseif ($status == 'ditindaklanjuti') {
                                        echo '<span class="badge bg-info text-dark badge-status">Ditindaklanjuti</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary badge-status">Belum Ditindak</span>';
                                    }
                                    ?>
                                </td>

                                <td>
                                    <div class="d-flex gap-1">

                                        <a href="edit_catat_pelanggaran.php?id=<?= (int)$d['id']; ?>"
                                           class="btn btn-warning btn-sm"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus catatan pelanggaran ini?')">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= (int)$d['id']; ?>">

                                            <button type="submit"
                                                    name="hapus"
                                                    class="btn btn-danger btn-sm"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </form>

                                    </div>
                                </td>
                            </tr>

                        <?php
                            endwhile;
                        else:
                        ?>

                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                    Data catatan pelanggaran belum tersedia.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>
                    </table>

                </div>

                <div class="text-muted small mt-2">
                    Menampilkan <?= mysqli_num_rows($dataCatat); ?> data catatan pelanggaran.
                </div>

            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
?>
