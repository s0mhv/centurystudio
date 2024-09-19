<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_page = $_POST['id_page'];
    $nama_menu = $_POST['nama_menu'];
    $link = $_POST['link'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE page SET nama_menu = '$nama_menu',link = '$link', WHERE id_page = '$id_page'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO page (nama_menu, link) VALUES ('$nama_menu', '$link',)";
    }

    if (mysqli_query($koneksi, $query)) { 
        header("Location:index.php?page=page_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_page = $_GET['id_page'];

    // Lakukan delete data
    $query = "DELETE FROM page WHERE id_page = '$id_page'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=page_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>