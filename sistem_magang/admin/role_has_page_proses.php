<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_role_has_page = $_POST['id_role_has_page'];
    $id_role = $_POST['id_role'];
    $id_page = $_POST['id_page'];
    $create = isset($_POST['create']) ? 1 : 0;
    $read = isset($_POST['read']) ? 1 : 0;
    $update = isset($_POST['update']) ? 1 : 0;
    $delete = isset($_POST['delete']) ? 1 : 0;
    $status = $_POST['status'];

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE role_has_page SET id_role = '$id_role',id_page = '$id_page', `create` = $create, `read` = $read, `update` = $update, `delete` = $delete WHERE id_role_has_page = '$id_role_has_page'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO role_has_page ( id_role,id_page, `create`, `read`, `update`, `delete`) VALUES ('$id_role', '$id_page', $create, $read, $update, $delete)";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=role_has_page_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_role_has_page = $_GET['id_role_has_page'];

    // Lakukan delete data
    $query = "DELETE FROM role_has_page WHERE id_role_has_page = '$id_role_has_page'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=role_has_page_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
