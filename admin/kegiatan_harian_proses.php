<?php
include "../config/koneksi.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kegiatan_harian = isset($_POST['id_kegiatan_harian']) ? $_POST['id_kegiatan_harian'] : null;
    $id_anggota = $_POST['id_anggota'];
    $kegiatan_harian = $_POST['kegiatan_harian'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_pengerjaan = $_POST['tanggal_pengerjaan'];
    $status_kegiatan = $_POST['status_kegiatan'];
    $status = $_POST['status']; // Ambil status dari input hidden field

    if ($status === 'edit') {
        // Lakukan update data
        $query = "UPDATE kegiatan_harian SET id_anggota = '$id_anggota', kegiatan_harian = '$kegiatan_harian', deskripsi = '$deskripsi', tanggal_pengerjaan= '$tanggal_pengerjaan', status_kegiatan = '$status_kegiatan' WHERE id_kegiatan_harian = '$id_kegiatan_harian'";
    } else if ($status === 'create') {
        // Lakukan insert data
        $query = "INSERT INTO kegiatan_harian (id_kegiatan_harian, id_anggota, kegiatan_harian, deskripsi, tanggal_pengerjaan, status_kegiatan) VALUES ('$id_kegiatan_harian', '$id_anggota', '$kegiatan_harian', '$deskripsi', '$tanggal_pengerjaan', '$status_kegiatan')";
    }

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kegiatan_harian_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_kegiatan_harian = $_GET['id_kegiatan_harian'];

    // Lakukan delete data
    $query = "DELETE FROM kegiatan_harian WHERE id_kegiatan_harian = '$id_kegiatan_harian'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=kegiatan_harian_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}