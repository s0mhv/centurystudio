<?php
include "../config/koneksi.php";
session_start();
$id_role = $_SESSION['id_role'];
$id_page = 6; // Sesuaikan dengan id_page untuk halaman kegiatan_harian tampil

// Ambil izin delete dari role_has_page
$query = "SELECT `delete`,`create`,`update` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kegiatan_harian = isset($_POST['id_kegiatan_harian']) ? $_POST['id_kegiatan_harian'] : null;
    $id_anggota = $_POST['id_anggota'];
    $kegiatan_harian = $_POST['kegiatan_harian'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_pengerjaan = $_POST['tanggal_pengerjaan'];
    $status_kegiatan = $_POST['status_kegiatan'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit' && $permissions['update'] == 1) {
        // Lakukan update data
        $query = "UPDATE kegiatan_harian SET id_anggota = '$id_anggota', kegiatan_harian = '$kegiatan_harian', deskripsi = '$deskripsi', tanggal_pengerjaan= '$tanggal_pengerjaan', status_kegiatan = '$status_kegiatan' WHERE id_kegiatan_harian = '$id_kegiatan_harian'";
    } else if ($status === 'create' && $permissions['create'] == 1) {
        // Lakukan insert data
        $query = "INSERT INTO kegiatan_harian (id_kegiatan_harian, id_anggota, kegiatan_harian, deskripsi, tanggal_pengerjaan, status_kegiatan) VALUES ('$id_kegiatan_harian', '$id_anggota', '$kegiatan_harian', '$deskripsi', '$tanggal_pengerjaan', '$status_kegiatan')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kegiatan_harian_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    // Validasi Delete berdasarkan izin role
    if ($permissions['delete'] == 1) {
        $id_kegiatan_harian = $_GET['id_kegiatan_harian'];

        // Lakukan delete data
        $query = "DELETE FROM kegiatan_harian WHERE id_kegiatan_harian = '$id_kegiatan_harian'";

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=kegiatan_harian_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to delete data.";
    }
}
?>