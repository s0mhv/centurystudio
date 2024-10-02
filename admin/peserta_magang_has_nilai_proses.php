<?php 
include "../config/koneksi.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

$id_role = $_SESSION['id_role'];
$id_page = 14; // Sesuaikan dengan id_page untuk halaman peserta_magang_has_kategori_nilai tampil

// Ambil izin delete, create, dan update dari role_has_page
$query = "SELECT `delete`, `create`, `update` FROM `role_has_page` WHERE `id_role` = $id_role AND `id_page` = $id_page";
$result = mysqli_query($koneksi, $query);
$permissions = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_has_nilai = isset($_POST['id_has_nilai']) ? $_POST['id_has_nilai'] : null;
    
    if ($permissions['create'] == 1 || $permissions['update'] == 1) {
        $id_anggota = $_POST['id_anggota'];
        $id_kategori_nilai = $_POST['id_kategori_nilai'];
        $nilai = $_POST['nilai'];
        $nilai_kualitatif = $_POST['nilai_kualitatif'];
        $status = $_POST['status'];

        // Cek jika data sudah ada untuk kombinasi id_anggota dan id_kategori_nilai
        $check_query = "SELECT COUNT(*) as count FROM peserta_magang_has_kategori_nilai WHERE id_anggota = '$id_anggota' AND id_kategori_nilai = '$id_kategori_nilai'";
        $check_result = mysqli_query($koneksi, $check_query);
        $result = mysqli_fetch_assoc($check_result);

        if ($result['count'] > 0 && $status === 'create') {
            // Jika data sudah ada, hentikan proses
            $_SESSION['error'] = "Data duplikat! Kombinasi anggota dan kategori nilai sudah ada.";
            header("Location: index.php?page=peserta_magang_has_nilai_tampil");
            exit();
        } else {
            // Lanjutkan proses create atau update
            if ($status === 'edit' && $permissions['update'] == 1) {
                // Lakukan update data
                $query = "UPDATE peserta_magang_has_kategori_nilai SET 
                            id_anggota = '$id_anggota', 
                            id_kategori_nilai = '$id_kategori_nilai', 
                            nilai = '$nilai', 
                            nilai_kualitatif = '$nilai_kualitatif' 
                          WHERE id_has_nilai = '$id_has_nilai'";
            } elseif ($status === 'create' && $permissions['create'] == 1) {
                // Lakukan insert data
                $query = "INSERT INTO peserta_magang_has_kategori_nilai (id_anggota, id_kategori_nilai, nilai, nilai_kualitatif) 
                          VALUES ('$id_anggota', '$id_kategori_nilai', '$nilai', '$nilai_kualitatif')";
            }

            if (mysqli_query($koneksi, $query)) {
                $_SESSION['success'] = "Data berhasil disimpan!";
                header("Location: index.php?page=peserta_magang_has_nilai_tampil");
                exit();
            } else {
                $_SESSION['error'] = "Error: " . mysqli_error($koneksi);
                header("Location: index.php?page=peserta_magang_has_nilai_tampil");
                exit();
            }
        }
    } else {
        $_SESSION['error'] = "You do not have permission to create or update data.";
        header("Location: index.php?page=peserta_magang_has_nilai_tampil");
        exit();
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    // Validasi Delete berdasarkan izin role
    if ($permissions['delete'] == 1) {
        $id_has_nilai = $_GET['id_has_nilai'];

        // Lakukan delete data
        $query = "DELETE FROM peserta_magang_has_kategori_nilai WHERE id_has_nilai = '$id_has_nilai'";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['success'] = "Data berhasil dihapus!";
            header("Location: index.php?page=peserta_magang_has_nilai_tampil");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($koneksi);
            header("Location: index.php?page=peserta_magang_has_nilai_tampil");
            exit();
        }
    } else {
        $_SESSION['error'] = "You do not have permission to delete data.";
        header("Location: index.php?page=peserta_magang_has_nilai_tampil");
        exit();
    }
}
?>
