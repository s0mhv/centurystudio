<?php
include "../config/koneksi.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

// Ambil id_role dan id_page
$id_role = $_SESSION['id_role']; // Sesuaikan dengan session yang digunakan
$id_page = 12; // Misalkan id_page untuk halaman proposal tampil adalah 11 (sesuaikan dengan data tabel page)

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

// Ambil hak akses CRUD berdasarkan role dan page
$query = "SELECT `create`, `read`, `update`, `delete` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Proposal Magang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 75%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-x: auto; /* Membuat kontainer dapat discroll horizontal jika tabel melebihi lebar */
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #000000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #228B22;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-buttons {
            text-align: center;
        }
        .action-buttons form {
            display: inline-block;
        }
        .action-buttons input[type="submit"], .action-buttons input[type="button"] {
            padding: 8px 16px;
            margin: 4px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        .action-buttons input[type="submit"]:hover, .action-buttons input[type="button"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h3>Data Proposal Magang</h3>
    <div class="action-buttons">
        <?php if ($permissions['create'] == 1) { ?>
            <input type="button" value="Tambah Data" onclick="window.location.href='index.php?page=proposal_magang_tambah'">
        <?php } ?>
    </div>
    <div class="container">
        <table>
            <tr>
                <th>NO</th>
                <th>NAMA ORGANISASI</th>
                <th>Nomor Surat</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Jumlah Anggota</th>
                <th>Tanggal Pengajuan</th>
                <th>Nama Pembimbing</th>
                <th>No Telefon Pembimbing</th>
                <th>Email Pembimbing</th>
                <th>Jabatan Pembimbing</th>
                <th>Surat Pengajuan</th>
                <th>Keterangan</th>
                <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                    <th>Aksi</th>
                <?php endif; ?>
            </tr>
<?php
$no = 1;
if ($_SESSION['role'] === 'Siswa') {
    // Query untuk Siswa, filter berdasarkan id_proposal dari sesi
    $query = "SELECT proposal.*, organisasi.nama_organisasi 
              FROM proposal 
              JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
              WHERE proposal.id_proposal = ? 
              ORDER BY proposal.id_proposal";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_proposal);  // Menggunakan id_proposal dari sesi
    $stmt->execute();
    $result = $stmt->get_result();

} elseif ($_SESSION['role'] === 'Pic External') {
    // Query untuk Pic External, filter berdasarkan id_pic di proposal_pic
    $query = "SELECT proposal.*, organisasi.nama_organisasi 
              FROM proposal 
              JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
              JOIN proposal_pic ON proposal.id_proposal = proposal_pic.id_proposal
              WHERE proposal_pic.id_pic = ? 
              ORDER BY proposal.id_proposal";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pic);  // Menggunakan id_pic dari sesi
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT proposal.*, organisasi.nama_organisasi 
          FROM proposal 
          JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
          ORDER BY proposal.id_proposal";
    $result = $koneksi->query($query);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $row['nama_organisasi'] . "</td>
                <td>" . $row['nomor_surat'] . "</td>
                <td>" . $row['tanggal_mulai'] . "</td>
                <td>" . $row['tanggal_selesai'] . "</td>
                <td>" . $row['jumlah_anggota'] . "</td>
                <td>" . $row['tanggal_pengajuan'] . "</td>
                <td>" . $row['nama_pembimbing'] . "</td>
                <td>" . $row['no_telefon_pembimbing'] . "</td>
                <td>" . $row['email_pembimbing'] . "</td>
                <td>" . $row['jabatan_pembimbing'] . "</td>
                <td>";

        $file_path = 'C:/xampp/htdocs/sistem_magang/file/' . $row['surat_pengajuan'];
        $file_url = '/sistem_magang/file/' . $row['surat_pengajuan'];
        
        if ($row['surat_pengajuan']) {
            if (file_exists($file_path)) {
                echo "<a href='" . $file_url . "' target='_blank'>Lihat File</a>";
            } else {
                echo "File tidak ditemukan";
            }
        } else {
            echo "Tidak ada file";
        }

        echo "</td>
                <td>" . $row['keterangan'] . "</td>";

        if ($permissions['update'] == 1) {
            echo "<td><a href='index.php?page=proposal_magang_tambah&id_proposal=" . $row['id_proposal'] . "'>EDIT</a></td>";
        }

        if ($permissions['delete'] == 1) {
            echo "<td><a href='index.php?page=proposal_magang_proses&id_proposal=" . $row['id_proposal'] . "' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>HAPUS</a></td>";
        }

        echo "</tr>";
    }
}
 else {
    echo "<tr><td colspan='7'>Tidak ada data.</td></tr>";
}
?>
        </table>
    </div>
</body>
</html>

<?php
mysqli_close($koneksi);
?>
