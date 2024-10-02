<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proposal_pic = $_POST['id_proposal_pic'];
    $id_proposal = $_POST['id_proposal'];
    $id_pic = $_POST['id_pic'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE proposal_pic SET id_proposal = '$id_proposal',id_pic = '$id_pic' WHERE id_proposal_pic = '$id_proposal_pic'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO proposal_pic (id_proposal, id_pic) VALUES ('$id_proposal', '$id_pic')";
    }

    if (mysqli_query($koneksi, $query)) { 
        header("Location:index.php?page=proposal_pic_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_proposal_pic = $_GET['id_proposal_pic'];

    // Lakukan delete data
    $query = "DELETE FROM proposal_pic WHERE id_proposal_pic = '$id_proposal_pic'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=proposal_pic_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>