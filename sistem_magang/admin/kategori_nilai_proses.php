<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kategori_nilai = $_POST['id_kategori_nilai'];
    $kategori_nilai = $_POST['kategori_nilai'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE kategori_nilai SET kategori_nilai = '$kategori_nilai' WHERE id_kategori_nilai = '$id_kategori_nilai'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO kategori_nilai (id_kategori_nilai, kategori_nilai) VALUES ('$id_kategori_nilai', '$kategori_nilai')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kategori_nilai_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_kategori_nilai = $_GET['id_kategori_nilai'];

    // Lakukan delete data
    $query = "DELETE FROM kategori_nilai WHERE id_kategori_nilai = '$id_kategori_nilai'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kategori_nilai_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>