<?php
include "../config/koneksi.php";

session_start();
$id_role = $_SESSION['id_role'];
$id_page = 16; // Sesuaikan dengan id_page untuk halaman organisasi tampil

// Ambil izin delete dari role_has_page
$query = "SELECT `delete`,`create`,`update` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proposal_has_kategori_nilai = $_POST['id_proposal_has_kategori_nilai'];
    $id_proposal = $_POST['id_proposal'];
    $id_kategori_nilai = $_POST['id_kategori_nilai'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit' && $permissions['update'] == 1) {
        // Lakukan update data
        $query = "UPDATE proposal_has_kategori_nilai SET id_proposal = '$id_proposal',id_kategori_nilai = '$id_kategori_nilai' WHERE id_proposal_has_kategori_nilai = '$id_proposal_has_kategori_nilai'";
    } elseif ($status === 'create' && $permissions['create'] == 1) {
        // Lakukan insert data
        $query = "INSERT INTO proposal_has_kategori_nilai (id_proposal_has_kategori_nilai, id_proposal,id_kategori_nilai) VALUES ('$id_proposal_has_kategori_nilai', '$id_proposal','$id_kategori_nilai')";
    }

    if (mysqli_query($koneksi, $query)) { 
        header("Location:index.php?page=proposal_has_kategori_nilai_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    // Validasi Delete berdasarkan izin role
    if ($permissions['delete'] == 1) {
        $id_proposal_has_kategori_nilai = $_GET['id_proposal_has_kategori_nilai'];

        // Lakukan delete data
        $query = "DELETE FROM proposal_has_kategori_nilai WHERE id_proposal_has_kategori_nilai = '$id_proposal_has_kategori_nilai'";

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=proposal_has_kategori_nilai_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to delete data.";
    }
}
?>