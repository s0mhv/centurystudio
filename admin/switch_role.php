<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['role'])) {
    $role_id = $_POST['role']; // Role ID yang dipilih dari form
    
    // Tentukan role berdasarkan ID
    switch ($role_id) {
        case '1':
            $role_name = 'Admin';
            break;
        case '2':
            $role_name = 'Siswa';
            break;
        case '3':
            $role_name = 'Pic_Internal';
            break;
        case '4':
            $role_name = 'Pic_External';
            break;
        default:
            $_SESSION['error'] = 'Role tidak valid';
            header("Location: index.php");
            exit;
    }

    // Update session dengan role baru
    $_SESSION['role'] = $role_name;
    $_SESSION['id_role'] = $role_id;

    // Redirect ke halaman utama atau halaman yang sesuai
    header("Location: index.php");
    exit;
} else {
    $_SESSION['error'] = 'Tidak ada role yang dipilih';
    header("Location: index.php");
    exit;
}
?>
