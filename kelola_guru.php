<?php

// kelola_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_users WHERE role = 'nama' ORDER BY id ASC";

$hasil = mysqli_query($koneksi, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Kelola Guru - Sistem Pelanggaran Siswa</title>
</head>

<body>

    <h1>Kelola Guru</h1>

    <a href="dashboard.php">Kembali ke Dashboard</a> |
    <a href="buat_users_guru.php">Tambah Guru</a>

    <br><br>

    <table border="1" cellpadding="6">

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;

        while ($row = mysqli_fetch_assoc($hasil)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>

            <td>
                <?php echo htmlspecialchars($row['nama']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['email']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['role']); ?>
            </td>

            <td>
                <a href="edit_guru.php?id=<?php echo $row['id']; ?>">Edit</a>
                |
                <a href="hapus_guru.php?id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Yakin ingin menghapus guru ini?')">
                    Hapus
                </a>
            </td>
        </tr>

        <?php } ?>

    </table>

</body>

</html>