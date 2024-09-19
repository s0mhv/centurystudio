<?php
include "../config/koneksi.php";

session_start();
$id_role = $_SESSION['id_role'];
$id_page = 3; // Sesuaikan dengan id_page untuk halaman deskripsi tugas tampil

// Ambil izin delete dari role_has_page
$query = "SELECT `delete`,`create`,`update` FROM `role_has_page` WHERE `id_role` = ? AND `id_page` = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("ii", $id_role, $id_page);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_deskripsi_tugas = $_POST['id_deskripsi_tugas'];
    $id_proposal = $_POST['id_proposal'];
    $deskripsi_tugas = $_POST['deskripsi_tugas'];
    $tanggal_pemberian = $_POST['tanggal_pemberian'];
    $tanggal_pengumpulan = $_POST['tanggal_pengumpulan'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit' && $permissions['update'] == 1) {
        // Lakukan update data
        $query = "UPDATE deskripsi_tugas SET id_proposal = '$id_proposal', deskripsi_tugas = '$deskripsi_tugas', tanggal_pemberian = '$tanggal_pemberian', tanggal_pengumpulan = '$tanggal_pengumpulan' WHERE id_deskripsi_tugas = '$id_deskripsi_tugas'";
    } else if ($status === 'create' && $permissions['create'] == 1) {
        // Lakukan insert data
        $query = "INSERT INTO deskripsi_tugas (id_deskripsi_tugas, id_proposal, deskripsi_tugas, tanggal_pemberian, tanggal_pengumpulan) VALUES ('$id_deskripsi_tugas', '$id_proposal', '$deskripsi_tugas', '$tanggal_pemberian', '$tanggal_pengumpulan')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=deskripsi_tugas_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    // Validasi Delete berdasarkan izin role
    if ($permissions['delete'] == 1) {
        $id_deskripsi_tugas = $_GET['id_deskripsi_tugas'];

        // Lakukan delete data
        $query = "DELETE FROM deskripsi_tugas WHERE id_deskripsi_tugas = '$id_deskripsi_tugas'";

        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?page=deskripsi_tugas_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "You do not have permission to delete data.";
    }
}
?>
