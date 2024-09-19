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
$id_page = 16; // Misalkan id_page untuk halaman organisasi tampil adalah 2 (sesuaikan dengan data tabel page)

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
    <title>Form Proposal Nilai</title>
</head>
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
        .button-container input[type="submit"] {
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
        .button-container input[type="submit"]:hover {
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
<body>
    <h3>DATA Proposal Nilai</h3>
    <div class="container">
        <div class="button-container">
            <?php if ($permissions['create'] == 1) { ?>
                <a href='index.php?page=proposal_has_kategori_nilai_tambah'><input type='submit' name='input' value='TAMBAH DATA'></a>
            <?php } ?>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="10%">Nomor Surat</th>
                    <th width="10%">Kategori Nilai</th>
                    <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                        <th width="10%" colspan="2">AKSI</th>
                    <?php endif; ?>
                </tr>
<?php
$no = 1;

if ($_SESSION['role'] === 'Siswa') {
    // Ambil id_proposal dari sesi
    $id_proposal = $_SESSION['id_proposal'];
    // Query untuk Siswa, memfilter berdasarkan id_proposal dari sesi
    $query = "SELECT proposal_has_kategori_nilai.*, proposal.nomor_surat, kategori_nilai.kategori_nilai
              FROM proposal_has_kategori_nilai
              JOIN proposal ON proposal_has_kategori_nilai.id_proposal = proposal.id_proposal
              JOIN kategori_nilai ON proposal_has_kategori_nilai.id_kategori_nilai = kategori_nilai.id_kategori_nilai
              WHERE proposal_has_kategori_nilai.id_proposal = ?
              ORDER BY proposal_has_kategori_nilai.id_proposal_has_kategori_nilai";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_proposal); // Menggunakan id_proposal dari sesi
    $stmt->execute();
    $result = $stmt->get_result();

} elseif ($_SESSION['role'] === 'Pic External') {
    // Ambil id_proposal dari sesi
    $id_proposal = $_SESSION['id_proposal'];
    // Query untuk Pic External, memfilter berdasarkan id_proposal dari tabel proposal_pic
    $query = "SELECT proposal_has_kategori_nilai.*, proposal.nomor_surat, kategori_nilai.kategori_nilai
              FROM proposal_has_kategori_nilai
              JOIN proposal ON proposal_has_kategori_nilai.id_proposal = proposal.id_proposal
              JOIN kategori_nilai ON proposal_has_kategori_nilai.id_kategori_nilai = kategori_nilai.id_kategori_nilai
              JOIN proposal_pic ON proposal.id_proposal = proposal_pic.id_proposal
              WHERE proposal_pic.id_pic = ?
              ORDER BY proposal_has_kategori_nilai.id_proposal_has_kategori_nilai";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $_SESSION['id_pic']); // Menggunakan id_pic dari sesi
    $stmt->execute();
    $result = $stmt->get_result();
}else {
    $query = "SELECT proposal_has_kategori_nilai.*, proposal.nomor_surat, kategori_nilai.kategori_nilai
                                                                    FROM proposal_has_kategori_nilai
                                                                    JOIN proposal  ON proposal_has_kategori_nilai.id_proposal = proposal.id_proposal
                                                                    JOIN kategori_nilai ON proposal_has_kategori_nilai.id_kategori_nilai = kategori_nilai.id_kategori_nilai
                                                                    ORDER BY proposal_has_kategori_nilai.id_proposal_has_kategori_nilai";
    $result = $koneksi->query($query);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $row['nomor_surat'] . "</td>
                <td>" . $row['kategori_nilai'] . "</td>";

        if ($permissions['update'] == 1) {
            echo "<td><a href='index.php?page=proposal_has_kategori_nilai_tambah&id_proposal_has_kategori_nilai=" . $row['id_proposal_has_kategori_nilai'] . "'>EDIT</a></td>";
        }

        if ($permissions['delete'] == 1) {
            echo "<td><a href='index.php?page=proposal_has_kategori_nilai_proses&id_proposal_has_kategori_nilai=" . $row['id_proposal_has_kategori_nilai'] . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>HAPUS</a></td>";
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
