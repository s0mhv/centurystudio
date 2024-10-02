<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jenis_organisasi = $_POST['id_jenis_organisasi'];
    $jenis_organisasi = $_POST['jenis_organisasi'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE jenis_organisasi SET jenis_organisasi = '$jenis_organisasi' WHERE id_jenis_organisasi = '$id_jenis_organisasi'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO jenis_organisasi (id_jenis_organisasi, jenis_organisasi) VALUES ('$id_jenis_organisasi', '$jenis_organisasi')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=jenis_organisasi_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_jenis_organisasi = $_GET['id_jenis_organisasi'];

    // Lakukan delete data
    $query = "DELETE FROM jenis_organisasi WHERE id_jenis_organisasi = '$id_jenis_organisasi'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=jenis_organisasi_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>