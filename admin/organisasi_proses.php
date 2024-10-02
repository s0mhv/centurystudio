<?php
include "../config/koneksi.php";

session_start();
$id_role = $_SESSION['id_role'];
$id_page = 10; // Sesuaikan dengan id_page untuk halaman organisasi tampil

// Ambil izin delete dari role_has_page
$query = "SELECT `delete`,`create`,`update` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_organisasi = $_POST['id_organisasi'];
    // Validasi Create dan Update berdasarkan izin role
    if ($permissions['create'] == 1 || $permissions['update'] == 1) {
        $id_kota = $_POST['id_kota'];
        $id_jenis_organisasi = $_POST['id_jenis_organisasi'];
        $nama_organisasi = $_POST['nama_organisasi'];
        $email = $_POST['email'];
        $no_telepon = $_POST['no_telepon'];
        $alamat = $_POST['alamat'];
        $website = $_POST['website'];
        $status = $_POST['status'];

        if ($status === 'edit' && $permissions['update'] == 1) {
            // Lakukan update data
            $query = "UPDATE organisasi SET 
                        id_kota = '$id_kota', 
                        id_jenis_organisasi = '$id_jenis_organisasi', 
                        nama_organisasi = '$nama_organisasi', 
                        email = '$email', 
                        no_telepon = '$no_telepon', 
                        alamat = '$alamat', 
                        website = '$website' 
                      WHERE id_organisasi = '$id_organisasi'";
        } elseif ($status === 'create' && $permissions['create'] == 1) {
            // Lakukan insert data
            $query = "INSERT INTO organisasi (id_kota, id_jenis_organisasi, nama_organisasi, email, no_telepon, alamat, website) 
                      VALUES ('$id_kota', '$id_jenis_organisasi', '$nama_organisasi', '$email', '$no_telepon', '$alamat', '$website')";
        }

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=organisasi_tampil");
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
        $id_organisasi = $_GET['id_organisasi'];

        // Lakukan delete data
        $query = "DELETE FROM organisasi WHERE id_organisasi = '$id_organisasi'";

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=organisasi_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to delete data.";
    }
}
?>