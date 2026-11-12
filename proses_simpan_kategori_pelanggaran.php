
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: catat_pelanggaran.php");
    exit;
}

$tahun_id = (int)($_POST['tahun_ajaran_id'] ?? 0);
$guru_id = (int)($_POST['guru_id'] ?? 0);
$siswa_id = (int)($_POST['siswa_id'] ?? 0);
$pelanggaran_id = (int)($_POST['pelanggaran_id'] ?? 0);
$tanggal = $_POST['tanggal'] ?? '';
$keterangan = trim($_POST['keterangan'] ?? '');

if (!$tahun_id || !$guru_id || !$siswa_id || !$pelanggaran_id || !$tanggal) {
    die("Data wajib belum lengkap.");
}

// Pastikan tahun ajaran masih aktif
$cekTahun = mysqli_prepare($koneksi, "
    SELECT id FROM t_tahun_ajaran
    WHERE id = ? AND status_aktif = 1
");
mysqli_stmt_bind_param($cekTahun, "i", $tahun_id);
mysqli_stmt_execute($cekTahun);

if (!mysqli_stmt_get_result($cekTahun)->num_rows) {
    die("Tahun ajaran aktif tidak ditemukan.");
}

// Ambil poin dan nama jenis pelanggaran dari database
$cekPelanggaran = mysqli_prepare($koneksi, "
    SELECT nama, poin FROM t_pelanggaran WHERE id = ?
");
mysqli_stmt_bind_param($cekPelanggaran, "i", $pelanggaran_id);
mysqli_stmt_execute($cekPelanggaran);
$dataPelanggaran = mysqli_fetch_assoc(mysqli_stmt_get_result($cekPelanggaran));

if (!$dataPelanggaran) {
    die("Jenis pelanggaran tidak ditemukan.");
}

// Ambil nama siswa
$cekSiswa = mysqli_prepare($koneksi, "
    SELECT nama FROM t_siswa WHERE id = ?
");
mysqli_stmt_bind_param($cekSiswa, "i", $siswa_id);
mysqli_stmt_execute($cekSiswa);
$dataSiswa = mysqli_fetch_assoc(mysqli_stmt_get_result($cekSiswa));

if (!$dataSiswa) {
    die("Siswa tidak ditemukan.");
}

// Ambil nama guru
$cekGuru = mysqli_prepare($koneksi, "
    SELECT nama FROM t_guru WHERE id = ?
");
mysqli_stmt_bind_param($cekGuru, "i", $guru_id);
mysqli_stmt_execute($cekGuru);
$dataGuru = mysqli_fetch_assoc(mysqli_stmt_get_result($cekGuru));

if (!$dataGuru) {
    die("Guru tidak ditemukan.");
}

$nama_siswa = $dataSiswa['nama'];
$nama_pelanggaran = $dataPelanggaran['nama'];
$nama_guru = $dataGuru['nama'];
$poin = (int)$dataPelanggaran['poin'];
$status = 'belum';
$tindakan = '';

$stmt = mysqli_prepare($koneksi, "
    INSERT INTO t_pelanggaran_siswa
    (
        tahun_ajaran_id,
        siswa_id,
        nama_siswa,
        pelanggaran_id,
        nama_pelanggaran,
        guru_id,
        nama_guru,
        tanggal,
        keterangan,
        poin,
        tindakan,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

mysqli_stmt_bind_param(
    $stmt,
    "iisissssssss",
    $tahun_id,
    $siswa_id,
    $nama_siswa,
    $pelanggaran_id,
    $nama_pelanggaran,
    $guru_id,
    $nama_guru,
    $tanggal,
    $keterangan,
    $poin,
    $tindakan,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    header("Location: catat_pelanggaran.php?pesan=berhasil");
    exit;
}

die("Gagal menyimpan pelanggaran: " . mysqli_stmt_error($stmt));
