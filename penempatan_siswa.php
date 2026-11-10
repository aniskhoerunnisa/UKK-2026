<?php
// penempatan_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Daftar siswa aktif
$siswa = mysqli_query($koneksi, "
    SELECT id, nis, nama
    FROM t_siswa
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Daftar tahun ajaran aktif
$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
");

// Daftar kelas aktif
$kelas = mysqli_query($koneksi, "
    SELECT id, nama, tingkat, jurusan
    FROM t_kelas
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// Data penempatan siswa
$sql = "
    SELECT
        ks.id,
        s.nis,
        s.nama AS nama_siswa,
        ta.nama AS tahun_ajaran,
        k.nama AS nama_kelas,
        k.tingkat,
        k.jurusan,
        ks.tanggal_mulai,
        ks.tanggal_selesai,
        ks.status_aktif
    FROM t_kelas_siswa ks
    LEFT JOIN t_siswa s ON ks.siswa_id = s.id
    LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    LEFT JOIN t_kelas k ON ks.kelas_id = k.id
    ORDER BY ks.id DESC
";

$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penempatan Siswa</title>

    <!-- Bootstrap 4.6.2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            background: #f4f5fb;
            font-family: 'Segoe UI', sans-serif;
            color: #343a50;
        }

        /* Layout utama: sidebar dan konten sejajar */
        .container-fluid > .row {
            display: flex;
            flex-wrap: nowrap;
            align-items: flex-start;
        }

        main {
            flex: 1 1 0;
            min-width: 0;
            margin-left: 0;
            padding: 30px !important;
            width: auto;
            box-sizing: border-box;
        }

        .header-page {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 27px 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, .2);
        }

        .header-page h2 {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .header-page p {
            margin-bottom: 0;
            opacity: .9;
        }

        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(40, 45, 80, .06);
            margin-bottom: 25px;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 20px 24px;
            border-radius: 14px 14px 0 0 !important;
        }

        .card-header h5 {
            font-weight: 700;
            color: #343a60;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        .form-control {
            border: 1px solid #e0e2ee;
            border-radius: 8px;
            height: 43px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, .1);
        }

        label {
            font-weight: 600;
            font-size: 14px;
            color: #454b65;
        }

        .btn-purple {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-purple:hover {
            color: white;
            opacity: .9;
        }

        .btn-back {
            background: #e9eaf3;
            color: #454b65;
            border-radius: 8px;
            padding: 9px 16px;
            font-weight: 600;
        }

        .btn-back:hover {
            background: #dcddec;
            color: #343a50;
        }

        .table thead th {
            background: #202b46;
            color: white;
            border: none;
            font-size: 13px;
            white-space: nowrap;
            padding: 14px;
        }

        .table tbody td {
            font-size: 13px;
            vertical-align: middle;
            padding: 13px;
            color: #50556b;
        }

        .table tbody tr:hover {
            background: #f5f3ff;
        }

        .badge-aktif {
            background: #d8f5e5;
            color: #16834c;
            padding: 7px 12px;
            border-radius: 20px;
        }

        .badge-nonaktif {
            background: #ececf1;
            color: #686b78;
            padding: 7px 12px;
            border-radius: 20px;
        }

        .alert {
            border: none;
            border-radius: 10px;
        }

        .dataTables_wrapper {
            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px 9px;
        }

        .page-item.active .page-link {
            background: #764ba2;
            border-color: #764ba2;
        }

        .page-link {
            color: #764ba2;
        }

        @media (max-width: 768px) {
            .container-fluid > .row {
                flex-wrap: wrap;
            }

            main {
                flex: 0 0 100%;
                min-width: 0;
                margin-left: 0;
                width: 100%;
                padding: 15px !important;
            }

            .card-body {
                padding: 15px;
            }

            .header-page {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row flex-nowrap">

        <?php include 'layout/sidebar.php'; ?>

        <main>

            <!-- HEADER -->
            <div class="header-page">
                <h2><i class="fas fa-user-graduate mr-2"></i>Penempatan Siswa</h2>
                <p>Kelola penempatan siswa berdasarkan tahun ajaran dan kelas.</p>
            </div>

            <a href="dashboard.php" class="btn btn-back btn-sm mb-4">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
            </a>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?= htmlspecialchars($_SESSION['pesan_sukses']); ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['pesan_sukses']); ?>
            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?= htmlspecialchars($_SESSION['pesan_error']); ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['pesan_error']); ?>
            <?php } ?>

            <!-- FORM PENEMPATAN -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-plus-circle mr-2 text-primary"></i>Form Penempatan Siswa</h5>
                </div>

                <div class="card-body">
                    <form action="proses_simpan_penempatan.php" method="POST">

                        <div class="form-group">
                            <label for="siswa_id">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="form-control" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                    <option value="<?= htmlspecialchars($s['id']); ?>">
                                        <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tahun_ajaran_id">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>
                                    <option value="<?= htmlspecialchars($ta['id']); ?>">
                                        <?= htmlspecialchars($ta['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kelas_id">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-control" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?= htmlspecialchars($k['id']); ?>">
                                        <?= htmlspecialchars(
                                            $k['nama'] . ' - Tingkat ' .
                                            $k['tingkat'] . ' - ' . $k['jurusan']
                                        ); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai"
                                           id="tanggal_mulai" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai"
                                           id="tanggal_selesai" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="status_aktif">Status</label>
                            <select name="status_aktif" id="status_aktif" class="form-control" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-purple">
                            <i class="fas fa-save mr-1"></i> Simpan Penempatan
                        </button>

                    </form>
                </div>
            </div>

            <!-- TABEL DATA -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-table mr-2 text-primary"></i>Data Penempatan Siswa</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table id="tabelPenempatan" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil)) {
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nis'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['tingkat'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['jurusan'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['tanggal_mulai'] ?? '-'); ?></td>
                                        <td><?= htmlspecialchars($row['tanggal_selesai'] ?? '-'); ?></td>
                                        <td>
                                            <?php if ($row['status_aktif'] == 1) { ?>
                                                <span class="badge-aktif">Aktif</span>
                                            <?php } else { ?>
                                                <span class="badge-nonaktif">Tidak Aktif</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelPenempatan').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data penempatan siswa",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data penempatan siswa tidak ditemukan",
            emptyTable: "Belum ada data penempatan siswa",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[0, 'asc']]
    });
});
</script>

</body>
</html>