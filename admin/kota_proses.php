<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kota = $_POST['id_kota'];
    $kota = $_POST['kota'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE kota SET kota = '$kota' WHERE id_kota = '$id_kota'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO kota (id_kota, kota) VALUES ('$id_kota', '$kota')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kota_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_kota = $_GET['id_kota'];

    // Lakukan delete data
    $query = "DELETE FROM kota WHERE id_kota = '$id_kota'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kota_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>