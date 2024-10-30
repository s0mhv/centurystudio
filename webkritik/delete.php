<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM comments WHERE id = $id";
    $conn->query($sql);
}

header('Location: index.php');
?>
