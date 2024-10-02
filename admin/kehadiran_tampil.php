<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if (!$id_role) {
    // Jika tidak ada role, redirect ke halaman login
    header("Location: login.php");
    exit();
}

if ($id_role == 4) {
    if (!isset($_SESSION['id_proposal'])) {
        // Ambil id_proposal berdasarkan id_anggota dari session
        include "../config/koneksi.php"; // Pastikan koneksi ke database sudah ada
        $id_proposal_query = "SELECT proposal.id_proposal 
                              FROM peserta_magang 
                              JOIN proposal ON peserta_magang.id_proposal = proposal.id_proposal 
                              WHERE peserta_magang.id_anggota = ?";
        $stmt = $koneksi->prepare($id_proposal_query);
        $stmt->bind_param("i", $id_anggota);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['id_proposal'] = $row['id_proposal']; // Simpan id_proposal di session
        } else {
            echo "ID Proposal tidak ditemukan untuk anggota ini!";
            exit();
        }
    }

    $id_proposal = $_SESSION['id_proposal']; // Gunakan id_proposal dari session
} elseif ($id_role == 1 || $id_role == 2) {
    if (!isset($_SESSION['id_proposal'])) {
        // Ambil id_proposal berdasarkan id_pic dari session
        include "../config/koneksi.php"; // Pastikan koneksi ke database sudah ada
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
            $_SESSION['id_proposal'] = $row['id_proposal']; // Simpan id_proposal di session
        } else {
            echo "ID Proposal tidak ditemukan untuk PIC ini!";
            exit();
        }
    }

    $id_proposal = $_SESSION['id_proposal']; // Gunakan id_proposal dari session
}

// Ambil id_role dan id_page
$id_role = $_SESSION['id_role']; // Sesuaikan dengan session yang digunakan
$id_page = 7; // Misalkan id_page untuk halaman kehadiran tampil adalah 7 (sesuaikan dengan data tabel page)

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
$no = 1;
if ($id_role == 4) {
    // Ambil id_proposal dari session
    $id_proposal = $_SESSION['id_proposal']; 
    
    $query = "SELECT kehadiran.*, peserta_magang.nama
              FROM kehadiran
              JOIN peserta_magang ON kehadiran.id_anggota = peserta_magang.id_anggota
              WHERE peserta_magang.keterangan = 'Masih proses'
              AND peserta_magang.id_proposal = ?
              ORDER BY id_kehadiran";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_proposal);
    $stmt->execute();
    $result = $stmt->get_result();
    
} elseif ($id_role == 1 || $id_role == 2) {
    // Ambil id_pic dari session
    $id_pic = $_SESSION['id_pic']; 
    
    $query = "SELECT kehadiran.*, peserta_magang.nama
              FROM kehadiran
              JOIN peserta_magang ON kehadiran.id_anggota = peserta_magang.id_anggota
              JOIN proposal_pic ON peserta_magang.id_proposal = proposal_pic.id_proposal
              WHERE peserta_magang.keterangan = 'Masih proses'
              AND proposal_pic.id_pic = ?
              ORDER BY id_kehadiran";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pic);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT kehadiran.*, peserta_magang.nama
                        FROM kehadiran 
                        JOIN peserta_magang ON kehadiran.id_anggota = peserta_magang.id_anggota 
                        WHERE peserta_magang.keterangan = 'Masih proses'
                        ORDER BY id_kehadiran";
    $result = $koneksi->query($query);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $row['nama'] . "</td>
                <td>" . $row['tanggal'] . "</td>
                <td>" . $row['waktu_masuk'] . "</td>
                <td>" . $row['waktu_keluar'] . "</td>
                <td>" . $row['status_kehadiran'] . "</td>
                <td>" . $row['keterangan'] . "</td>";

        if ($permissions['update'] == 1) {
            echo "<td><a href='index.php?page=kehadiran_tambah&id_kehadiran=" . $row['id_kehadiran'] . "'>EDIT</a></td>";
        }

        if ($permissions['delete'] == 1) {
            echo "<td><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='kehadiran_proses.php?status=hapus&id_kehadiran=$row[id_kehadiran]';}\">HAPUS</a></td>";
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>Tidak ada data.</td></tr>";
}
?>
            </table>
        </div>
    </div>
</body>
</html>
