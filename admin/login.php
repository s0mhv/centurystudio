<?php 
session_start(); 
// Buat koneksi ke database
include '../config/koneksi.php'; 

// Ambil role dari tabel role
$query = "SELECT role FROM role WHERE role IN ('ADMIN', 'SISWA', 'PIC INTERNAL', 'PIC EXTERNAL')";
$result = mysqli_query($koneksi, $query);
$roles = [];

while ($row = mysqli_fetch_assoc($result)) {
    $roles[$row['role']] = $row['role']; // Gunakan nama role sebagai key dan value
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG-IN</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="video-background">
        <video autoplay muted loop id="bg-video">
            <source src="../bg_img/fuji.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="login-box">
    <h2>Log-In</h2>
    <!-- Tampilkan pesan error jika ada -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Hapus pesan error setelah ditampilkan
            ?>
        </div>
    <?php endif; ?>

    <form action="login_proses.php" method="POST">
        <div class="textbox">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="textbox">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="textbox">
            <label for="role">Role:</label>
            <select name="id_role" id="role" required>
                <option value="">--Pilih Role--</option>
                <option value="4">SISWA</option>
                <option value="1">PIC External</option>
                <option value="2">PIC Internal</option>
                <option value="3">ADMIN</option>
            </select>
        </div>

        <button type="submit" class="btn">Login</button>
    </form>
</div>
    </div>
    <script>
        // JavaScript untuk menambahkan animasi flip pada dropdown
        const roleSelect = document.getElementById('role');
        roleSelect.addEventListener('focus', function () {
            this.parentElement.classList.add('active');
        });

        roleSelect.addEventListener('blur', function () {
            this.parentElement.classList.remove('active');
        });
    </script>
</body>
</html>