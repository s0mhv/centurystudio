<?php
ob_start(); // Mulai buffering output

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

// Ambil id_role dari session
$id_role = $_SESSION['id_role'];

// Cek jika tidak ada role atau id_anggota
if (empty($id_role)) {
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
$id_role = $_SESSION['id_role']; // Sesuaikan dengan session yang digunakan
$id_page = 11; // Misalkan id_page untuk halaman organisasi tampil adalah 11 (sesuaikan dengan data tabel page)

// Ambil hak akses CRUD berdasarkan role dan page
include "../config/koneksi.php";
$query = "SELECT `create`, `read`, `update`, `delete` FROM role_has_page WHERE id_role = ? AND id_page = ?";
$stmt = $koneksi->prepare($query);  
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

ob_end_flush(); // Kirim output ke browser
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Peserta Magang</title>
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
    <h3>DATA Peserta Magang</h3>
    <div class="container">
        <div class="button-container">
            <?php if ($permissions['create'] == 1) { ?>
                <a href='index.php?page=peserta_magang_tambah'><input type='submit' name='input' value='TAMBAH DATA'></a>
            <?php } ?>
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th width="5%">NO.</th>
                    <th width="5%">NOMOR SURAT</th>
                    <th width="5%">ASAL ORGANISASI</th>
                    <th width="5%">NIK</th>
                    <th width="5%">NAMA</th>
                    <th width="5%">TANGGAL LAHIR</th>
                    <th width="5%">AGAMA</th>
                    <th width="5%">ALAMAT</th>
                    <th width="5%">GOLONGAN DARAH</th>
                    <th width="5%">JENIS KELAMIN</th>
                    <th width="5%">BIDANG KEAHLIAN</th>
                    <th width="5%">KOTA</th>
                    <?php if ($permissions['update'] == 1 || $permissions['delete'] == 1): ?>
                        <th width="5%" colspan="2">AKSI</th>
                    <?php endif; ?>
                </tr>
<?php
$no = 1;
if ($id_role == 4) {
    // Role Siswa: Gunakan id_proposal dari session untuk menampilkan data peserta magang terkait
    $id_proposal = $_SESSION['id_proposal'];
    $query = "SELECT peserta_magang.*, proposal.nomor_surat, organisasi.nama_organisasi, kota_organisasi.kota AS kota_organisasi, bidang_keahlian.bidang_keahlian, kota.kota, agama.agama 
              FROM peserta_magang 
              JOIN proposal ON peserta_magang.id_proposal = proposal.id_proposal 
              JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
              JOIN kota AS kota_organisasi ON organisasi.id_kota = kota_organisasi.id_kota 
              JOIN bidang_keahlian ON peserta_magang.id_bidang_keahlian = bidang_keahlian.id_bidang_keahlian 
              JOIN kota ON peserta_magang.id_kota = kota.id_kota 
              JOIN agama ON peserta_magang.id_agama = agama.id_agama 
              WHERE peserta_magang.keterangan = 'Masih proses' AND peserta_magang.id_proposal = ? 
              ORDER BY peserta_magang.id_proposal";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_proposal);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$no}</td>";
        echo "<td>{$row['nomor_surat']}</td>";
        echo "<td>{$row['nama_organisasi']}</td>";
        echo "<td>{$row['nik']}</td>";
        echo "<td>{$row['nama']}</td>";
        echo "<td>{$row['tanggal_lahir']}</td>";
        echo "<td>{$row['agama']}</td>";
        echo "<td>{$row['alamat']}</td>";
        echo "<td>{$row['golongan_darah']}</td>";
        echo "<td>{$row['jenis_kelamin']}</td>";
        echo "<td>{$row['bidang_keahlian']}</td>";
        echo "<td>{$row['kota']}</td>";
        echo "</tr>";
        $no++;
    }

} elseif ($id_role == 1 || $id_role == 2) {
    // Role PIC External: Gunakan id_pic dari session untuk mencari id_proposal di tabel proposal_pic
    $id_pic = $_SESSION['id_pic'];

    $query = "SELECT peserta_magang.*, proposal.nomor_surat, organisasi.nama_organisasi, kota_organisasi.kota AS kota_organisasi, bidang_keahlian.bidang_keahlian, kota.kota, agama.agama 
              FROM peserta_magang 
              JOIN proposal ON peserta_magang.id_proposal = proposal.id_proposal 
              JOIN proposal_pic ON proposal.id_proposal = proposal_pic.id_proposal
              JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
              JOIN kota AS kota_organisasi ON organisasi.id_kota = kota_organisasi.id_kota 
              JOIN bidang_keahlian ON peserta_magang.id_bidang_keahlian = bidang_keahlian.id_bidang_keahlian 
              JOIN kota ON peserta_magang.id_kota = kota.id_kota 
              JOIN agama ON peserta_magang.id_agama = agama.id_agama 
              WHERE peserta_magang.keterangan = 'Masih proses' AND proposal_pic.id_pic = ?
              ORDER BY peserta_magang.id_proposal";

    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pic);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$no}</td>";
        echo "<td>{$row['nomor_surat']}</td>";
        echo "<td>{$row['nama_organisasi']}</td>";
        echo "<td>{$row['nik']}</td>";
        echo "<td>{$row['nama']}</td>";
        echo "<td>{$row['tanggal_lahir']}</td>";
        echo "<td>{$row['agama']}</td>";
        echo "<td>{$row['alamat']}</td>";
        echo "<td>{$row['golongan_darah']}</td>";
        echo "<td>{$row['jenis_kelamin']}</td>";
        echo "<td>{$row['bidang_keahlian']}</td>";
        echo "<td>{$row['kota']}</td>";
        echo "</tr>";
        $no++;
    }

} else {
    // Tampilkan semua data peserta magang jika role bukan Siswa atau PIC External
    $query = "SELECT peserta_magang.*, proposal.id_proposal, organisasi.nama_organisasi, kota_organisasi.kota AS kota_organisasi, bidang_keahlian.bidang_keahlian, kota.kota, agama.agama
              FROM peserta_magang 
              JOIN proposal ON peserta_magang.id_proposal = proposal.id_proposal
              JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
              JOIN kota AS kota_organisasi ON organisasi.id_kota = kota_organisasi.id_kota 
              JOIN bidang_keahlian ON peserta_magang.id_bidang_keahlian = bidang_keahlian.id_bidang_keahlian
              JOIN kota ON peserta_magang.id_kota = kota.id_kota
              JOIN agama ON peserta_magang.id_agama = agama.id_agama
              WHERE peserta_magang.keterangan = 'Masih proses'
              ORDER BY peserta_magang.id_proposal";

    $stmt = $koneksi->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$no}</td>";
        echo "<td>{$row['id_proposal']}</td>";
        echo "<td>{$row['nama_organisasi']}</td>";
        echo "<td>{$row['nik']}</td>";
        echo "<td>{$row['nama']}</td>";
        echo "<td>{$row['tanggal_lahir']}</td>";
        echo "<td>{$row['agama']}</td>";
        echo "<td>{$row['alamat']}</td>";
        echo "<td>{$row['golongan_darah']}</td>";
        echo "<td>{$row['jenis_kelamin']}</td>";
        echo "<td>{$row['bidang_keahlian']}</td>";
        echo "<td>{$row['kota']}</td>";
    
        if ($permissions['update'] == 1) {
            echo "<td><a href='index.php?page=peserta_magang_tambah&id_anggota={$row['id_anggota']}'>Edit</a></td>";
        }
        if ($permissions['delete'] == 1) {
            echo "<td><a href='#' onclick=\"if (confirm('Apakah anda yakin data dihapus ?')) {window.location.href='peserta_magang_proses.php?status=hapus&id_anggota=$row[id_anggota]';}\">HAPUS</a></td>";
        }
    
        echo "</tr>";
        $no++;
    }
}
?>

                </tr>
            </table>
        </div>
    </div>
</body>
</html>
