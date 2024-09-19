<?php
session_start(); // Pastikan session dimulai
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Query untuk mendapatkan data pengguna berdasarkan email
    $query = "SELECT * FROM akun WHERE email = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($pass, $user['hashed_pass'])) {
            // Set sesi pengguna
            $_SESSION['email'] = $user['email']; // Simpan email di session
            $_SESSION['nama'] = $user['nama']; // Simpan nama di session
            $id_akun = $user['id_akun'];

            // Query untuk mendapatkan role
            $role_query = "SELECT r.id_role, r.role
                           FROM akun_has_role ahr
                           JOIN role r ON ahr.id_role = r.id_role
                           WHERE ahr.id_akun = ?";
            $role_stmt = $koneksi->prepare($role_query);
            $role_stmt->bind_param("i", $id_akun);
            $role_stmt->execute();
            $role_result = $role_stmt->get_result();

            if ($role_result->num_rows > 0) {
                $role_data = $role_result->fetch_assoc();
                $_SESSION['role'] = $role_data['role']; // Simpan nama role di session
                $_SESSION['id_role'] = $role_data['id_role']; // Simpan id_role di session

                // Jika role adalah Siswa, ambil id_proposal dari peserta_magang
                if ($role_data['role'] == 'Siswa') {
                    // Ambil id_anggota dari tabel akun
                    $id_anggota_query = "SELECT id_anggota FROM akun WHERE id_akun = ?";
                    $id_anggota_stmt = $koneksi->prepare($id_anggota_query);
                    $id_anggota_stmt->bind_param("i", $id_akun);
                    $id_anggota_stmt->execute();
                    $id_anggota_result = $id_anggota_stmt->get_result();

                    if ($id_anggota_result->num_rows > 0) {
                        $id_anggota_data = $id_anggota_result->fetch_assoc();
                        $_SESSION['id_anggota'] = $id_anggota_data['id_anggota']; // Simpan id_anggota dalam session

                        // Ambil id_proposal dari peserta_magang berdasarkan id_anggota
                        $id_anggota = $_SESSION['id_anggota'];
                        $proposal_query = "SELECT id_proposal FROM peserta_magang WHERE id_anggota = ?";
                        $proposal_stmt = $koneksi->prepare($proposal_query);
                        $proposal_stmt->bind_param("i", $id_anggota);
                        $proposal_stmt->execute();
                        $proposal_result = $proposal_stmt->get_result();

                        if ($proposal_result->num_rows > 0) {
                            $proposal_data = $proposal_result->fetch_assoc();
                            $_SESSION['id_proposal'] = $proposal_data['id_proposal']; // Simpan id_proposal dalam session
                            error_log("ID Proposal Disimpan dalam Session: " . $_SESSION['id_proposal']);
                        } else {
                            $_SESSION['error'] = "ID Proposal tidak ditemukan!";
                            header("Location: login.php");
                            exit;
                        }
                    } else {
                        $_SESSION['error'] = "ID Anggota tidak ditemukan!";
                        header("Location: login.php");
                        exit;
                    }
                } 
                // Jika role adalah Pic External, ambil id_proposal dari proposal_pic
                else if ($role_data['role'] == 'Pic External') {
                    // Ambil id_pic dari tabel akun
                    $id_pic_query = "SELECT id_pic FROM akun WHERE id_akun = ?";
                    $id_pic_stmt = $koneksi->prepare($id_pic_query);
                    $id_pic_stmt->bind_param("i", $id_akun);
                    $id_pic_stmt->execute();
                    $id_pic_result = $id_pic_stmt->get_result();

                    if ($id_pic_result->num_rows > 0) {
                        $id_pic_data = $id_pic_result->fetch_assoc();
                        $_SESSION['id_pic'] = $id_pic_data['id_pic']; // Simpan id_pic dalam session

                        // Ambil id_proposal dari proposal_pic berdasarkan id_pic
                        $id_pic = $_SESSION['id_pic'];
                        $proposal_query = "SELECT id_proposal FROM proposal_pic WHERE id_pic = ?";
                        $proposal_stmt = $koneksi->prepare($proposal_query);
                        $proposal_stmt->bind_param("i", $id_pic);
                        $proposal_stmt->execute();
                        $proposal_result = $proposal_stmt->get_result();

                        if ($proposal_result->num_rows > 0) {
                            $proposal_data = $proposal_result->fetch_assoc();
                            $_SESSION['id_proposal'] = $proposal_data['id_proposal']; // Simpan id_proposal dalam session
                            error_log("ID Proposal Disimpan dalam Session: " . $_SESSION['id_proposal']);
                        } else {
                            $_SESSION['error'] = "ID Proposal tidak ditemukan!";
                            header("Location: login.php");
                            exit;
                        }
                    } else {
                        $_SESSION['error'] = "ID Pic tidak ditemukan!";
                        header("Location: login.php");
                        exit;
                    }
                } 
                // Jika role lain, langsung login tanpa pengecekan id_proposal
                else {
                    // Role lainnya, tidak memerlukan id_proposal atau id_anggota
                    error_log("Role ditemukan: " . $role_data['role']);
                }

                // Redirect ke halaman index atau dashboard
                header("Location: index.php");
                exit;
            } else {
                // Role tidak ditemukan
                $_SESSION['error'] = "Role tidak ditemukan!";
                header("Location: login.php");
                exit;
            }
        } else {
            // Password salah
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit;
        }
    } else {
        // Email tidak ditemukan
        $_SESSION['error'] = "Email tidak ditemukan!";
        header("Location: login.php");
        exit;
    }
}
?>
