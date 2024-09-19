<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_agama = $_POST['id_agama'];
    $agama = $_POST['agama'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE agama SET agama = '$agama' WHERE id_agama = '$id_agama'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO agama (id_agama, agama) VALUES ('$id_agama', '$agama')";
    }

    if (mysqli_query($koneksi, $query)) { 
        header("Location:index.php?page=agama_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_agama = $_GET['id_agama'];

    // Lakukan delete data
    $query = "DELETE FROM agama WHERE id_agama = '$id_agama'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=agama_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>