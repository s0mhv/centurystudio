<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM comments WHERE id = $id");
    $data = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $komentar = $_POST['komentar'];
    $conn->query("UPDATE comments SET nama = '$nama', komentar = '$komentar' WHERE id = $id");
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Komentar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Komentar</h2>
        <form method="POST" action="">
            <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required>
            <textarea name="komentar" required><?php echo $data['komentar']; ?></textarea>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
