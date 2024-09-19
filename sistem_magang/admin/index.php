<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>HALAMAN DATA MAGANG</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="header">HALAMAN DATA MAGANG</div>
    
    <div id="main-content">
        <div id="menu">
            <?php include "menu.php"; ?>
        </div>
        
        <div id="isi">
            <?php include "isi.php";?>
        </div>
    </div>

    <div id="footer">INFOKAN SPONSORSHIP</div>
</body>

</html>
