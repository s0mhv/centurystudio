<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG-IN</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://fonts.google.com/share?selection.family=Montserrat:ital,wght@0,100..900;1,100..900">
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
                    <input type="text" name="email" placeholder="Username" required>
                </div>
                <div class="textbox">
                    <input type="password" name="pass" placeholder="Password" required>
                </div>
                <input type="submit" class="btn" value="Login">
            </form>
        </div>
    </div>

    <style>
        /* Tambahkan styling untuk pesan error */
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</body>
</html>
