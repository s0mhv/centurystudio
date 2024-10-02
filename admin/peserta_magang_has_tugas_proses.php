<?php
include "../config/koneksi.php";

// Fungsi untuk mendapatkan ekstensi file
function getFileExtension($filename) {
    $path_parts = pathinfo($filename);
    return $path_parts['extension'];
}

// Fungsi untuk mendapatkan nama file dengan timestamp
function getTimestampedFilename($filename) {
    $path_parts = pathinfo($filename);
    $timestamp = date('Ymd_His');
    return $path_parts['filename'] . '_' . $timestamp . '.' . $path_parts['extension'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_has_tugas = $_POST['id_has_tugas'];
    $id_anggota = $_POST['id_anggota'];
    $id_deskripsi_tugas = $_POST['id_deskripsi_tugas'];
    $keterangan = $_POST['keterangan'];
    $realisasi_pengumpulan = $_POST['realisasi_pengumpulan'];
    $nilai = $_POST['nilai'];
    $status = $_POST['status']; // Assuming status is being sent in POST data

    // Proses upload file
    $file_tugas = '';
    if ($_FILES['file_tugas']['name']) {
        $file_name = $_FILES['file_tugas']['name'];
        $file_tmp = $_FILES['file_tugas']['tmp_name'];
        $ext = getFileExtension($file_name);

        // Dapatkan nama file dengan timestamp
        $file_name = getTimestampedFilename($file_name);

        // Simpan file dengan nama unik
        $upload_dir = 'C:/xampp/htdocs/sistem_magang/file_tugas/';
        $upload_path = $upload_dir . $file_name;
        move_uploaded_file($file_tmp, $upload_path);
        $file_tugas = $file_name;
    }

    if ($status === 'edit') {
        $id_has_tugas = $_POST['id_has_tugas'];
        // Jika tidak ada file baru diunggah, gunakan nama file lama dari database
        if (empty($file_tugas)) {
            $file_tugas_query = mysqli_query($koneksi, "SELECT file_tugas FROM peserta_magang_has_tugas WHERE id_has_tugas='$id_has_tugas'");
            $file_tugas_data = mysqli_fetch_assoc($file_tugas_query);
            $file_tugas = $file_tugas_data['file_tugas'];
        }

        // Lakukan update data
        $query = "UPDATE peserta_magang_has_tugas SET id_anggota = '$id_anggota', id_deskripsi_tugas = '$id_deskripsi_tugas', keterangan = '$keterangan', realisasi_pengumpulan = '$realisasi_pengumpulan', nilai = '$nilai', file_tugas = '$file_tugas' WHERE id_has_tugas = '$id_has_tugas'";
    } else {
        // Lakukan insert data
        $query = "INSERT INTO peserta_magang_has_tugas (id_has_tugas, id_anggota, id_deskripsi_tugas, keterangan, realisasi_pengumpulan, nilai, file_tugas) VALUES ('$id_has_tugas','$id_anggota', '$id_deskripsi_tugas', '$keterangan', '$realisasi_pengumpulan', '$nilai', '$file_tugas')";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil disimpan');</script>";
        echo "<meta http-equiv='refresh' content='0; url=index.php?page=peserta_magang_has_tugas_tampil'>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_has_tugas = $_GET['id_has_tugas'];

    // Mengambil nama file untuk dihapus dari direktori uploads
    $file_tugas_query = mysqli_query($koneksi, "SELECT file_tugas FROM peserta_magang_has_tugas WHERE id_has_tugas='$id_has_tugas'");
    $file_tugas_data = mysqli_fetch_assoc($file_tugas_query);
    $file_tugas = $file_tugas_data['file_tugas'];

    // Lakukan delete data
    $query = "DELETE FROM peserta_magang_has_tugas WHERE id_has_tugas='$id_has_tugas'";

    if (mysqli_query($koneksi, $query)) {
        // Hapus file dari direktori uploads jika ada
        $file_path = 'C:/xampp/htdocs/sistem_magang/file_tugas/' . $file_tugas;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<meta http-equiv='refresh'content='0;url=index.php?page=peserta_magang_has_tugas_tampil.'>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>