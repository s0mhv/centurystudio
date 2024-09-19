<?php
// Memulai session
session_start();
// Menghubungkan ke database
include "../config/koneksi.php"; // Sesuaikan path jika diperlukan

// Ambil semua data pengguna
$query = "SELECT id_akun, pass FROM akun";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

while ($row = mysqli_fetch_assoc($result)) {
    $id_akun = $row['id_akun'];
    $pass = $row['pass'];

    // Hash pass
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);  // Perbaikan di sini

    // Update database dengan hash pass
    $update_query = "UPDATE akun SET hashed_pass = '$hashed_pass' WHERE id_akun = $id_akun";
    $update_result = mysqli_query($koneksi, $update_query);

    if (!$update_result) {
        die("Update gagal: " . mysqli_error($koneksi));
    }
}

// Hapus kolom pass lama jika diperlukan
// ALTER TABLE akun DROP COLUMN pass;

echo "Proses hashing pass selesai.";
?>
