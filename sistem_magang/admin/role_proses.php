<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_role = $_POST['id_role'];
    $role = $_POST['role'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE role SET role = '$role' WHERE id_role = '$id_role'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO role (id_role, role) VALUES ('$id_role', '$role')";
    }

    if (mysqli_query($koneksi, $query)) { 
        header("Location:index.php?page=role_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_role = $_GET['id_role'];

    // Lakukan delete data
    $query = "DELETE FROM role WHERE id_role = '$id_role'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=role_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>