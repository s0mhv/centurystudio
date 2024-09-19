<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pic = $_POST['id_pic'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $id_akun = $_POST['id_akun'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE role SET nama = '$nama', keterangan = '$keterangan', id_akun = '$id_akun' WHERE id_pic = '$id_pic'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO role (id_pic, nama, keterangan, id_akun) VALUES ('$id_pic', '$nama','$keterangan','$id_akun')";
    }

    if (mysqli_query($koneksi, $query)) { 
        header("Location:index.php?page=role_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_pic = $_GET['id_pic'];

    // Lakukan delete data
    $query = "DELETE FROM role WHERE id_pic = '$id_pic'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=role_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>