<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

$roleArray = $_SESSION['role'] ?? []; // Ambil array role dari session
$role = $roleArray[0] ?? null; // Ambil role pertama dari array

if (!$role) {
    // Jika tidak ada role, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Ambil id_role dan id_page
$id_role = $_SESSION['id_role']; // Sesuaikan dengan session yang digunakan
$id_page = 8; // Misalkan id_page untuk halaman kehadiran tampil adalah 7 (sesuaikan dengan data tabel page)

// Ambil hak akses CRUD berdasarkan role dan page
include "../config/koneksi.php";
$query = "SELECT `create`, `read`, `update`, `delete` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kehadiran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }
        h3 {
            text-align: center;
            color: #444;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .button-container {
            text-align: left;
            margin: 20px 0;
        }
        .button-container input {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-container input:hover {
            background-color: #45a049;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #228B22;
            color: white;
        }
        table tr:hover {
            background-color: #f5f5f5;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #0066cc;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h3>DATA Kehadiran</h3>
    <div class="container">
        <div class="button-container">
            <?php if ($permissions['create'] == 1) { ?>
                <a href='index.php?page=kehadiran_tambah'><input type='submit' name='input' value='TAMBAH DATA'></a>
            <?php } ?>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="10%">Nama</th>
                    <th width="10%">Tanggal Kehadiran</th>
                    <th width="10%">Waktu Masuk</th>
                    <th width="10%">Waktu Keluar</th>
                    <th width="10%">Status Kehadiran</th>
                    <th width="10%">Keterangan</th>
                    <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                        <th width="10%" colspan="2">AKSI</th>
                    <?php endif; ?>
                </tr>
                <?php
                    include "../config/koneksi.php";
                    $no = 1;
                    $tampil_kota = mysqli_query($koneksi, "SELECT kehadiran.*, peserta_magang.nama
                                                           FROM kehadiran 
                                                           JOIN peserta_magang ON kehadiran.id_anggota = peserta_magang.id_anggota 
                                                           WHERE peserta_magang.keterangan = 'Masih proses'
                                                           ORDER BY id_kehadiran");
                    while ($hasil = mysqli_fetch_array($tampil_kota)) {
                        echo "<tr>";
                        echo "<td align='center'>$no</td>";
                        echo "<td>$hasil[nama]</td>";
                        echo "<td>$hasil[tanggal]</td>";
                        echo "<td>$hasil[waktu_masuk]</td>";
                        echo "<td>$hasil[waktu_keluar]</td>";
                        echo "<td>$hasil[status_kehadiran]</td>";
                        echo "<td>$hasil[keterangan]</td>";
                        // Tombol EDIT berdasarkan role
                        if ($permissions['update'] == 1) {
                            echo "<td align='center'><a href='index.php?page=kehadiran_tambah&id_kehadiran=$hasil[id_kehadiran]'>EDIT</a></td>";
                        }
                        // Tombol DELETE berdasarkan role
                        if ($permissions['delete'] == 1) {
                            echo "<td align='center'><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='kehadiran_proses.php?status=hapus&id_kehadiran=$hasil[id_kehadiran]';}\">HAPUS</a></td>";
                        }
                        echo "</tr>";
                        $no++;
                    }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
