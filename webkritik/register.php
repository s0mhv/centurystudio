<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah username sudah ada
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Jika username belum ada, simpan pengguna baru
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $error = "Terjadi kesalahan, silakan coba lagi.";
        }
    } else {
        $error = "Username sudah terdaftar!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul class="navbar">
                <li><a href="index.php">Back</a></li> <!-- Link ke halaman utama -->
            </ul>
        </nav>

        <h1>Register</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
