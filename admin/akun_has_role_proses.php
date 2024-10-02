<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_akun = $_POST['id_akun'];
    $status = $_POST['status'];

    if ($status === 'edit') {
        // Lakukan update data (hanya untuk edit single role jika diperlukan)
        $id_akun_has_role = $_POST['id_akun_has_role'];
        $id_role = $_POST['id_role'];
        $query = "UPDATE akun_has_role SET id_role = '$id_role' WHERE id_akun_has_role = '$id_akun_has_role'";
        mysqli_query($koneksi, $query);
    } else {
        // Insert data: Tambahkan 4 role untuk 1 akun
        $roles = ['ADMIN', 'PIC INTERNAL', 'PIC EXTERNAL', 'SISWA'];
        
        foreach ($roles as $role) {
            // Ambil id_role berdasarkan nama role
            $result_role = mysqli_query($koneksi, "SELECT id_role FROM role WHERE role = '$role'");
            $role_data = mysqli_fetch_array($result_role);
            $id_role = $role_data['id_role'];
            
            // Lakukan insert data
            $query = "INSERT INTO akun_has_role (id_role, id_akun) VALUES ('$id_role', '$id_akun')";
            mysqli_query($koneksi, $query);
        }
    }
    header("Location: index.php?page=akun_has_role_tampil");
    exit();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
    $id_akun_has_role = $_GET['id_akun_has_role'];

    // Lakukan delete data dari tabel akun_has_role
    $query = "DELETE FROM akun_has_role WHERE id_akun_has_role = '$id_akun_has_role'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?page=akun_has_role_tampil");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
