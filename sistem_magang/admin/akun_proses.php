<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $nama = $_POST['nama'];
    $id_role = $_POST['id_role'];

    // Hash password before saving
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insert new account data with hashed password
    $query_akun = "INSERT INTO akun (email, pass, hashed_pass, nama) VALUES ('$email', '$pass', '$hashed_pass', '$nama')";
    
    if (mysqli_query($koneksi, $query_akun)) {
        // Get the newly inserted account's id
        $id_akun = mysqli_insert_id($koneksi);

        // Insert selected role into akun_has_role
        if ($id_role) {
            $query_role = "INSERT INTO akun_has_role (id_role, id_akun) VALUES ('$id_role', '$id_akun')";
            mysqli_query($koneksi, $query_role);
        }

        // Redirect after process is complete
        header("Location: index.php?page=akun_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_akun = $_GET['id_akun'];

    // Delete data from akun_has_role first
    $query_hapus_role = "DELETE FROM akun_has_role WHERE id_akun = '$id_akun'";
    
    if (mysqli_query($koneksi, $query_hapus_role)) {
        // After role are deleted, delete the account from akun table
        $query_hapus_akun = "DELETE FROM akun WHERE id_akun = '$id_akun'";
        
        if (mysqli_query($koneksi, $query_hapus_akun)) {
            // Redirect after deletion process is complete
            header("Location: index.php?page=akun_tampil");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>