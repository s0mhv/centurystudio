<?php
session_start();
include "../config/koneksi.php"; // Pastikan file koneksi terhubung

// Ambil data dari POST
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$id_role = isset($_POST['id_role']) ? $_POST['id_role'] : ''; // Ambil id_role dari form login

// Debugging: menampilkan data yang diambil dari form
echo "<p style='color: blue;'>Email: $email</p>";
echo "<p style='color: blue;'>Password: $password</p>";
echo "<p style='color: blue;'>ID Role (dari POST): $id_role</p>";

// Cek apakah semua data form sudah diisi
if (empty($email) || empty($password) || empty($id_role)) {
    die("<p style='color: red;'>Semua field harus diisi.</p>");
}

// Cek apakah koneksi berhasil
if (!$koneksi) {
    die("<p style='color: red;'>Koneksi database gagal: " . mysqli_connect_error() . "</p>");
}

// Cek data login dari database
$query = "SELECT * FROM akun WHERE email = '$email' AND pass = '$password'";
$result = mysqli_query($koneksi, $query);

// Debugging: cek apakah query berjalan dengan benar
if (!$result) {
    die("<p style='color: red;'>Query error: " . mysqli_error($koneksi) . "</p>");
}

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    // Simpan data user ke session
    $_SESSION['email'] = $row['email'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['id_akun'] = $row['id_akun']; // Simpan id_akun ke session
    $_SESSION['id_role'] = $id_role; // Simpan id_role yang diinput di form

    // Cek role yang dipilih
    if ($id_role == 4) { // Role Siswa
        $query_siswa = "SELECT id_anggota FROM akun WHERE email = '$email'";
        $result_siswa = mysqli_query($koneksi, $query_siswa);
        
        if (mysqli_num_rows($result_siswa) > 0) {
            $row_siswa = mysqli_fetch_assoc($result_siswa);
            $_SESSION['id_anggota'] = $row_siswa['id_anggota']; // Simpan id_anggota ke session
            
            // Query untuk mengambil id_proposal berdasarkan id_anggota
            $id_anggota = $row_siswa['id_anggota'];
            $query_proposal = "SELECT id_proposal FROM peserta_magang WHERE id_anggota = '$id_anggota'";
            $result_proposal = mysqli_query($koneksi, $query_proposal);
            
            if (mysqli_num_rows($result_proposal) > 0) {
                $row_proposal = mysqli_fetch_assoc($result_proposal);
                $_SESSION['id_proposal'] = $row_proposal['id_proposal']; // Simpan id_proposal ke session
            }
        }
    }elseif ($id_role == 1 || $id_role == 2) { // Role PIC_EXTERNAL atau PIC_INTERNAL
        // Ambil id_pic dari database
        $id_pic_query = "SELECT id_pic FROM pic WHERE id_akun = ?";
        $id_pic_stmt = $koneksi->prepare($id_pic_query);
        
        // Pastikan id_akun diambil dari session dan tidak null
        if (!$id_pic_stmt) {
            die("<p style='color: red;'>Error preparing statement: " . mysqli_error($koneksi) . "</p>");
        }
        
        $id_pic_stmt->bind_param("i", $_SESSION['id_akun']);
        $id_pic_stmt->execute();
        $id_pic_result = $id_pic_stmt->get_result();
        
        if ($id_pic_result->num_rows > 0) {
            $id_pic_data = $id_pic_result->fetch_assoc();
            $_SESSION['id_pic'] = $id_pic_data['id_pic']; // Simpan id_pic dalam session
            
            // Ambil id_proposal dari proposal_pic berdasarkan id_pic
            $id_pic = $_SESSION['id_pic'];
            $proposal_query = "SELECT id_proposal FROM proposal_pic WHERE id_pic = ?";
            $proposal_stmt = $koneksi->prepare($proposal_query);
            
            if (!$proposal_stmt) {
                die("<p style='color: red;'>Error preparing statement for proposal: " . mysqli_error($koneksi) . "</p>");
            }
            
            $proposal_stmt->bind_param("i", $id_pic);
            $proposal_stmt->execute();
            $proposal_result = $proposal_stmt->get_result();
            
            if ($proposal_result->num_rows > 0) {
                $proposal_data = $proposal_result->fetch_assoc();
                $_SESSION['id_proposal'] = $proposal_data['id_proposal']; // Simpan id_proposal dalam session
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

    // Redirect ke halaman index.php
    header("Location: index.php");
    exit();
} else {
    echo "<p style='color: red;'>Email atau password salah.</p>";
    header("Location: login.php?error=1");
    exit();
}
?>
