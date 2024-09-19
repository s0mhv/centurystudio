<?php
include "../config/koneksi.php";

session_start();
$id_role = $_SESSION['id_role'];
$id_page = 10; // Sesuaikan dengan id_page untuk halaman kehadiran tampil

// Ambil izin delete dari role_has_page
$query = "SELECT `delete`,`create`,`update` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kehadiran = $_POST['id_kehadiran'];
    // Validasi Create dan Update berdasarkan izin role
    if ($permissions['create'] == 1 || $permissions['update'] == 1) {
        $id_anggota = $_POST['id_anggota'];
        $tanggal = $_POST['tanggal'];
        $waktu_masuk = $_POST['waktu_masuk'];
        $waktu_keluar = $_POST['waktu_keluar'];
        $status_kehadiran = $_POST['status_kehadiran'];
        $keterangan = $_POST['keterangan'];
        $status = $_POST['status'];

        if ($status === 'edit' && $permissions['update'] == 1) {
            // Lakukan update data
            $query = "UPDATE kehadiran SET 
                        id_anggota = '$id_anggota', 
                        tanggal = '$tanggal', 
                        waktu_masuk = '$waktu_masuk', 
                        waktu_keluar = '$waktu_keluar', 
                        status_kehadiran = '$status_kehadiran', 
                        keterangan = '$keterangan'
                      WHERE id_kehadiran = '$id_kehadiran'";
        } elseif ($status === 'create' && $permissions['create'] == 1) {
            // Lakukan insert data
            $query = "INSERT INTO kehadiran (id_anggota, tanggal, waktu_masuk, waktu_keluar, status_kehadiran, keterangan) 
                      VALUES ('$id_anggota', '$tanggal', '$waktu_masuk', '$waktu_keluar', '$status_kehadiran', '$keterangan')";
        }

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=kehadiran_tampil");
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
        $id_kehadiran = $_GET['id_kehadiran'];

        // Lakukan delete data
        $query = "DELETE FROM kehadiran WHERE id_kehadiran = '$id_kehadiran'";

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=kehadiran_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to delete data.";
    }
}
?>