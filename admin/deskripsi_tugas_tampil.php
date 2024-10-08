<?php
// Memulai session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil id_role dari session
$id_role = $_SESSION['id_role'];

// Cek apakah id_role sudah ada, jika tidak redirect ke login
if (!$id_role) {
    header("Location: login.php");
    exit();
}

// Ambil id_page untuk halaman ini (misalnya id_page = 3 untuk deskripsi tugas)
$id_page = 3;

// Ambil hak akses CRUD berdasarkan id_role dan id_page
include "../config/koneksi.php";
$query = "SELECT `create`, `read`, `update`, `delete` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

// Ambil id_proposal jika role adalah PIC External atau PIC Internal
$id_proposal = null;

// Ganti id_role dengan angka sesuai dengan database
if ($id_role == 1 || $id_role == 2) {  // Misal, 2 adalah Pic External dan 3 adalah Pic Internal
    if (!isset($_SESSION['id_proposal'])) {
        // Ambil id_pic dari session
        $id_pic = $_SESSION['id_pic'];

        // Query untuk mendapatkan id_proposal berdasarkan id_pic
        $id_proposal_query = "SELECT proposal.id_proposal 
                              FROM proposal_pic 
                              JOIN proposal ON proposal_pic.id_proposal = proposal.id_proposal 
                              WHERE proposal_pic.id_pic = ?";
        $stmt = $koneksi->prepare($id_proposal_query);
        $stmt->bind_param("i", $id_pic);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['id_proposal'] = $row['id_proposal']; // Simpan id_proposal ke dalam session
        } else {
            echo "ID Proposal tidak ditemukan untuk PIC ini!";
            exit();
        }
    }

    // Gunakan id_proposal dari session
    $id_proposal = $_SESSION['id_proposal'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Deskripsi Tugas</title>
    <style>
        /* CSS styling yang digunakan */
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
            background-color: #739072;
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
    <h3>DATA DESKRIPSI TUGAS</h3>
    <div class="container">
        <div class="button-container">
            <?php if ($permissions['create'] == 1) { ?>
                <a href='index.php?page=deskripsi_tugas_tambah'><input type='submit' name='input' value='TAMBAH DATA'></a>
            <?php } ?>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="20%">NAMA ORGANISASI</th>
                    <th width="20%">DESKRIPSI TUGAS</th>
                    <th width="20%">TANGGAL PEMBERIAN</th>
                    <th width="20%">TANGGAL PENGUMPULAN</th>
                    <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                        <th width="10%" colspan="2">AKSI</th>
                    <?php endif; ?>
                </tr>
                <?php
                    // Sesuaikan query berdasarkan id_role
                    if ($id_role == 1 || $id_role == 2) {  // 2 untuk Pic External, 3 untuk Pic Internal
                        $id_pic = $_SESSION['id_pic'];
                        // Filter data berdasarkan id_proposal untuk role Pic External atau Pic Internal
                        $query_deskripsi = "SELECT deskripsi_tugas.*, organisasi.nama_organisasi
                            FROM deskripsi_tugas
                            JOIN proposal ON deskripsi_tugas.id_proposal = proposal.id_proposal
                            JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
                            JOIN proposal_pic ON proposal.id_proposal = proposal_pic.id_proposal
                            WHERE proposal_pic.id_pic = ?
                            ORDER BY deskripsi_tugas.id_deskripsi_tugas";
                        $stmt_deskripsi = $koneksi->prepare($query_deskripsi);
                        $stmt_deskripsi->bind_param("i", $id_pic);
                    } else {
                        // Query default tanpa filter
                        $query_deskripsi = "SELECT deskripsi_tugas.*, organisasi.nama_organisasi
                            FROM deskripsi_tugas
                            JOIN proposal ON deskripsi_tugas.id_proposal = proposal.id_proposal
                            JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
                            ORDER BY deskripsi_tugas.id_deskripsi_tugas";
                        $stmt_deskripsi = $koneksi->prepare($query_deskripsi);
                    }

                    $stmt_deskripsi->execute();
                    $result_deskripsi = $stmt_deskripsi->get_result();

                    $no = 1;
                    while ($hasil = $result_deskripsi->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td align='center'>$no</td>";
                        echo "<td>{$hasil['nama_organisasi']}</td>";
                        echo "<td>{$hasil['deskripsi_tugas']}</td>";
                        echo "<td>{$hasil['tanggal_pemberian']}</td>";
                        echo "<td>{$hasil['tanggal_pengumpulan']}</td>";

                        // Tombol EDIT berdasarkan role
                        if ($permissions['update'] == 1) {
                            echo "<td align='center'><a href='index.php?page=deskripsi_tugas_tambah&id_deskripsi_tugas={$hasil['id_deskripsi_tugas']}'>EDIT</a></td>";
                        }

                        // Tombol DELETE berdasarkan role
                        if ($permissions['delete'] == 1) {
                            echo "<td align='center'><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='deskripsi_tugas_proses.php?status=hapus&id_deskripsi_tugas={$hasil['id_deskripsi_tugas']}';}\">HAPUS</a></td>";
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
