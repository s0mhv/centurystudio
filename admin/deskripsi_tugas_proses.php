<?php
include "../config/koneksi.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan variabel sudah di-set sebelum digunakan
    $id_deskripsi_tugas = isset($_POST['id_deskripsi_tugas']) ? $_POST['id_deskripsi_tugas'] : null;
    $id_proposal = $_POST['id_proposal'];
    $deskripsi_tugas = $_POST['deskripsi_tugas'];
    $tanggal_pemberian = $_POST['tanggal_pemberian'];
    $tanggal_pengumpulan = $_POST['tanggal_pengumpulan'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit' && !empty($id_deskripsi_tugas)) {
        // Lakukan update data jika id_deskripsi_tugas ada
        $query = "UPDATE deskripsi_tugas SET id_proposal = '$id_proposal', deskripsi_tugas = '$deskripsi_tugas', tanggal_pemberian = '$tanggal_pemberian', tanggal_pengumpulan = '$tanggal_pengumpulan' WHERE id_deskripsi_tugas = '$id_deskripsi_tugas'";
    } else if ($status === 'tambah') {
        // Lakukan insert data
        $query = "INSERT INTO deskripsi_tugas (id_proposal, deskripsi_tugas, tanggal_pemberian, tanggal_pengumpulan) VALUES ('$id_proposal', '$deskripsi_tugas', '$tanggal_pemberian', '$tanggal_pengumpulan')";
    }

    // Eksekusi query jika sudah di-set
    if (isset($query) && mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=deskripsi_tugas_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    // Pastikan variabel sudah di-set sebelum digunakan
    if (isset($_GET['id_deskripsi_tugas'])) {
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
        echo "ID Deskripsi Tugas tidak ditemukan.";
    }
}
?>
