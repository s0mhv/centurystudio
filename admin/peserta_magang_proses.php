<?php
include "../config/koneksi.php";

session_start();
$id_role = $_SESSION['id_role'];
$id_page = 13; // Sesuaikan dengan id_page untuk halaman peserta_magang tampil

// Ambil izin delete dari role_has_page
$query = "SELECT `delete`,`create`,`update` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_anggota = $_POST['id_anggota'];
    // Validasi Create dan Update berdasarkan izin role
    if ($permissions['create'] == 1 || $permissions['update'] == 1) {
        $id_proposal = $_POST['id_proposal'];
        $nik = $_POST['nik'];
        $nama = $_POST['nama'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $id_agama = $_POST['id_agama'];
        $alamat = $_POST['alamat'];
        $golongan_darah = $_POST['golongan_darah'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $id_bidang_keahlian = $_POST['id_bidang_keahlian'];
        $id_kota = $_POST['id_kota'];
        $keterangan = $_POST['keterangan'];
        $status = $_POST['status'];

        if ($status === 'edit' && $permissions['update'] == 1) {
            // Lakukan update data
            $query = "UPDATE peserta_magang SET 
                        id_proposal = '$id_proposal', 
                        nik = '$nik', 
                        nama = '$nama', 
                        tanggal_lahir = '$tanggal_lahir', 
                        id_agama = '$id_agama', 
                        alamat = '$alamat', 
                        golongan_darah = '$golongan_darah',
                        jenis_kelamin = '$jenis_kelamin',
                        id_bidang_keahlian = '$id_bidang_keahlian',
                        id_kota = '$id_kota',
                        keterangan = '$keterangan'
                        WHERE id_anggota = '$id_anggota'";
        } elseif ($status === 'create' && $permissions['create'] == 1) {
            // Lakukan insert data
            $query = "INSERT INTO peserta_magang (id_proposal, nik, nama, tanggal_lahir, alamat, golongan_darah, jenis_kelamin, id_bidang_keahlian, id_kota, id_agama, keterangan) 
                      VALUES ('$id_proposal', '$nik', '$nama', '$tanggal_lahir', '$alamat', '$golongan_darah', '$jenis_kelamin', '$id_bidang_keahlian', '$id_kota', '$id_agama', '$keterangan')";
        }

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=peserta_magang_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to create or update data.";
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    // Validasi Delete berdasarkan izin role
    if ($permissions['delete'] == 1) {
        $id_anggota = $_GET['id_anggota'];

        // Lakukan delete data
        $query = "DELETE FROM peserta_magang WHERE id_anggota = '$id_anggota'";

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=peserta_magang_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to delete data.";
    }
}
?>