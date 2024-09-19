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

// Jika role adalah siswa, cek id_proposal dari session
if ($_SESSION['role'] === 'Siswa') {
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
} elseif ($_SESSION['role'] === 'Pic External') {
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
$id_page = 13; // Misalkan id_page untuk halaman peserta_magang_has_nilai tampil adalah 2 (sesuaikan dengan data tabel page)

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
    <title>Form Peserta Magang Has Tugas</title>
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
        .button-container input[type="button"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        .button-container input[type="button"]:hover {
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
            transition: color 0.3s;
        }
        a:hover {
            text-decoration: underline;
            color: #004499;
        }
    </style>
</head>
<body>
    <h3>DATA Peserta Magang Has Tugas</h3>
    <div class="container">
        <!-- Tombol tambah data hanya muncul jika memiliki izin create -->
        <div class="button-container">
            <?php if ($permissions['create'] == 1) { ?>
                <a href='index.php?page=peserta_magang_has_tugas_tambah'>
                    <input type='submit' name='input' value='TAMBAH DATA'>
                </a>
            <?php } ?>
        </div>
        
        <!-- Awal tabel -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Anggota</th>
                        <th>Deskripsi Tugas</th>
                        <th>Keterangan</th>
                        <th>Realisasi Pengumpulan</th>
                        <th>Nilai</th>
                        <th>File Tugas</th>
                        <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                            <th colspan="2">AKSI</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Nomor urut data dimulai dari 1
                $no = 1;

                // Query untuk role 'Siswa'
                if ($_SESSION['role'] === 'Siswa') { 
                    $query = "SELECT DISTINCT peserta_magang_has_tugas.*, peserta_magang.nama, deskripsi_tugas.deskripsi_tugas
                              FROM peserta_magang_has_tugas
                              JOIN peserta_magang ON peserta_magang_has_tugas.id_anggota = peserta_magang.id_anggota
                              JOIN deskripsi_tugas ON peserta_magang_has_tugas.id_deskripsi_tugas = deskripsi_tugas.id_deskripsi_tugas
                              WHERE peserta_magang.id_proposal = ?
                              ORDER BY peserta_magang_has_tugas.id_has_tugas";
                    $stmt = $koneksi->prepare($query);
                    $stmt->bind_param("i", $id_proposal);  
                    $stmt->execute();
                    $result = $stmt->get_result();

                // Query untuk role 'Pic External'
                } elseif ($_SESSION['role'] === 'Pic External') {
                    $query = "SELECT DISTINCT peserta_magang_has_tugas.*, peserta_magang.nama, deskripsi_tugas.deskripsi_tugas
                              FROM peserta_magang_has_tugas
                              JOIN peserta_magang ON peserta_magang_has_tugas.id_anggota = peserta_magang.id_anggota
                              JOIN deskripsi_tugas ON peserta_magang_has_tugas.id_deskripsi_tugas = deskripsi_tugas.id_deskripsi_tugas
                              JOIN proposal_pic ON peserta_magang.id_proposal = proposal_pic.id_proposal
                              WHERE proposal_pic.id_pic = ?
                              ORDER BY peserta_magang_has_tugas.id_has_tugas";
                    $stmt = $koneksi->prepare($query);
                    $stmt->bind_param("i", $id_pic);  
                    $stmt->execute();
                    $result = $stmt->get_result();
                } else {
                    // Query untuk role lain
                    $query = "SELECT DISTINCT peserta_magang_has_tugas.*, peserta_magang.nama, deskripsi_tugas.deskripsi_tugas
                              FROM peserta_magang_has_tugas
                              JOIN peserta_magang ON peserta_magang_has_tugas.id_anggota = peserta_magang.id_anggota
                              JOIN deskripsi_tugas ON peserta_magang_has_tugas.id_deskripsi_tugas = deskripsi_tugas.id_deskripsi_tugas
                              ORDER BY peserta_magang_has_tugas.id_has_tugas";
                    $result = $koneksi->query($query);
                }

                // Menampilkan data dari query
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $no++ . "</td>
                                <td>" . htmlspecialchars($row['nama']) . "</td>
                                <td>" . htmlspecialchars($row['deskripsi_tugas']) . "</td>
                                <td>" . htmlspecialchars($row['keterangan']) . "</td>
                                <td>" . htmlspecialchars($row['realisasi_pengumpulan']) . "</td>
                                <td>" . htmlspecialchars($row['nilai']) . "</td>
                                <td align='center'>";

                        $file_path = 'C:/xampp/htdocs/sistem_magang/file_tugas/' . $row['file_tugas'];
                        $file_url = '/sistem_magang/file_tugas/' . $row['file_tugas'];

                        // Cek file tugas
                        if (!empty($row['file_tugas'])) {
                            if (file_exists($file_path)) {
                                echo "<a href='" . $file_url . "' target='_blank'>Lihat File</a>";
                            } else {
                                echo "File tidak ditemukan";
                            }
                        } else {
                            echo "Tidak ada file";
                        }

                        echo "</td>";

                        // Tombol edit jika user memiliki izin update
                        if ($permissions['update'] == 1) {
                            echo "<td><a href='index.php?page=kegiatan_harian_tambah&id_has_tugas=" . $row['id_has_tugas'] . "'>EDIT</a></td>";
                        }

                        // Tombol hapus jika user memiliki izin delete
                        if ($permissions['delete'] == 1) {
                            echo "<td><a href='index.php?page=kegiatan_harian_proses&id_has_tugas=" . $row['id_has_tugas'] . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>HAPUS</a></td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>