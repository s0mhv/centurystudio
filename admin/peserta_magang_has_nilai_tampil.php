<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}   

$id_role = $_SESSION['id_role'];

if (!$id_role) {
    // Jika tidak ada role, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Jika role adalah siswa, cek id_proposal dari session
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
$id_role = $_SESSION['id_role'];
$id_page = 14; // Misalkan id_page untuk halaman peserta_magang_has_nilai tampil adalah 2 (sesuaikan dengan data tabel page)

// Ambil hak akses CRUD berdasarkan role dan page
include "../config/koneksi.php";
$query = "SELECT `create`, `read`, `update`, `delete` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>DATA NILAI PESERTA MAGANG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h3 {
            text-align: center;
            color: #228B22;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #228B22;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a.button {
            display: inline-block;
            background-color: #228B22;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        a.button:hover {
            background-color: #1E7C1E;
        }
        .action-links a {
            margin: 0 5px;
            color: #0066cc;
            text-decoration: none;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>DATA NILAI PESERTA MAGANG</h3>
            <?php if ($permissions['create'] == 1) { ?>
                <a href='index.php?page=peserta_magang_has_nilai_tambah'><input type='submit' name='input' value='TAMBAH DATA'></a>
            <?php } ?>
        <table border="1">
            <tr>
                <th width="5%">No.</th>
                <th width="20%">Nama</th>
                <th width="20%">Kategori Nilai</th>
                <th width="10%">Nilai</th>
                <th width="20%">Nilai Kualitatif</th>
                <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                        <th width="10%" colspan="2">AKSI</th>
                <?php endif; ?>
            </tr>
            <?php
$no = 1;
if ($id_role == 4) {
    $query = "SELECT peserta_magang_has_kategori_nilai.*, kategori_nilai.kategori_nilai, peserta_magang.nama 
              FROM peserta_magang_has_kategori_nilai
              JOIN peserta_magang ON peserta_magang_has_kategori_nilai.id_anggota = peserta_magang.id_anggota
              JOIN proposal_has_kategori_nilai ON peserta_magang_has_kategori_nilai.id_kategori_nilai = proposal_has_kategori_nilai.id_kategori_nilai
              JOIN kategori_nilai ON proposal_has_kategori_nilai.id_kategori_nilai = kategori_nilai.id_kategori_nilai
              WHERE peserta_magang.id_proposal = ?
              ORDER BY peserta_magang_has_kategori_nilai.id_has_nilai";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_proposal);
    $stmt->execute();
    $result = $stmt->get_result();

} elseif ($id_role == 1 || $id_role == 2) {
    $id_pic = $_SESSION['id_pic'];
    $query = "SELECT peserta_magang_has_kategori_nilai.*, kategori_nilai.kategori_nilai, peserta_magang.nama 
              FROM peserta_magang_has_kategori_nilai
              JOIN peserta_magang ON peserta_magang_has_kategori_nilai.id_anggota = peserta_magang.id_anggota
              JOIN proposal_pic ON proposal_pic.id_proposal = peserta_magang.id_proposal
              JOIN proposal_has_kategori_nilai ON peserta_magang_has_kategori_nilai.id_kategori_nilai = proposal_has_kategori_nilai.id_kategori_nilai
              JOIN kategori_nilai ON proposal_has_kategori_nilai.id_kategori_nilai = kategori_nilai.id_kategori_nilai
              WHERE proposal_pic.id_pic = ?
              ORDER BY peserta_magang_has_kategori_nilai.id_has_nilai";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pic);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT peserta_magang_has_kategori_nilai.*, proposal_has_kategori_nilai.id_proposal, kategori_nilai.kategori_nilai, peserta_magang.nama 
            FROM peserta_magang_has_kategori_nilai 
            JOIN proposal_has_kategori_nilai ON peserta_magang_has_kategori_nilai.id_kategori_nilai = proposal_has_kategori_nilai.id_kategori_nilai
            JOIN kategori_nilai ON proposal_has_kategori_nilai.id_kategori_nilai = kategori_nilai.id_kategori_nilai
            JOIN peserta_magang ON peserta_magang_has_kategori_nilai.id_anggota = peserta_magang.id_anggota 
            ORDER BY peserta_magang_has_kategori_nilai.id_has_nilai";
    $result = $koneksi->query($query);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $row['nama'] . "</td>
                <td>" . $row['kategori_nilai'] . "</td>
                <td>" . $row['nilai'] . "</td>
                <td>" . $row['nilai_kualitatif'] . "</td>";
    
        if ($permissions['update'] == 1) {
            echo "<td><a href='index.php?page=peserta_magang_has_nilai_tambah&id_has_nilai=" . $row['id_has_nilai'] . "'>EDIT</a></td>";
        }
    
        if ($permissions['delete'] == 1) {
            echo "<td><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='peserta_magang_has_nilai_proses.php?status=hapus&id_has_nilai=$row[id_has_nilai]';}\">HAPUS</a></td>";
        }
    
        echo "</tr>";
    }
    } else {
        echo "<tr><td colspan='7'>Tidak ada data.</td></tr>";
    }
    ?>    
        </table>
    </div>
</body>
</html>