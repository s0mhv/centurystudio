<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bidang_keahlian = $_POST['id_bidang_keahlian'];
    $bidang_keahlian = $_POST['bidang_keahlian'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE bidang_keahlian SET bidang_keahlian = '$bidang_keahlian' WHERE id_bidang_keahlian = '$id_bidang_keahlian'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO bidang_keahlian (id_bidang_keahlian, bidang_keahlian) VALUES ('$id_bidang_keahlian', '$bidang_keahlian')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=bidang_keahlian_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_bidang_keahlian = $_GET['id_bidang_keahlian'];

    // Lakukan delete data
    $query = "DELETE FROM bidang_keahlian WHERE id_bidang_keahlian = '$id_bidang_keahlian'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=bidang_keahlian_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>