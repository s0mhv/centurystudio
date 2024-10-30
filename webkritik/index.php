<?php
include 'config.php';
session_start();

$is_logged_in = isset($_SESSION['username']);

// Menentukan berapa banyak komentar yang ditampilkan per halaman
$comments_per_page = 5; // Ubah sesuai kebutuhan
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $comments_per_page;

// Menyimpan komentar ke dalam database
if ($is_logged_in && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_SESSION['username'];
    $komentar = $_POST['komentar'];
    $sql = "INSERT INTO comments (nama, komentar) VALUES ('$nama', '$komentar')";
    $conn->query($sql);
}

// Menghapus komentar
if (isset($_GET['delete']) && $is_logged_in) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM comments WHERE id = $id");
    header("Location: index.php");
}

// Mengambil jumlah total komentar
$total_comments_result = $conn->query("SELECT COUNT(*) as total FROM comments");
$total_comments = $total_comments_result->fetch_assoc()['total'];
$total_pages = ceil($total_comments / $comments_per_page);

// Mengambil komentar dengan offset dan limit
$comments = $conn->query("SELECT * FROM comments ORDER BY waktu DESC LIMIT $offset, $comments_per_page");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kritik dan Saran UPN</title>
    <link rel="stylesheet" href="style.css"> <!-- Pastikan file style.css sudah ada -->
    <link href="https://fonts.googleapis.com/css2?family=Chokokutai&family=Libre+Franklin:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> <!-- Tambahkan Google Fonts -->
</head>
<body>
    <div class="container">
        <nav>
            <ul class="navbar">
                <li><img src="images/LogoUPN.png" alt="Logo Perusahaan" class="logo"></li> <!-- Ganti dengan path logo Anda -->
            </ul>
        </nav>
        
        <h1>Kritik dan Saran UPN</h1>

        <?php if ($is_logged_in): ?>
            <p><a href="logout.php" class="logout-button">Logout</a></p>
        <?php endif; ?>

        <?php if ($is_logged_in): ?>
            <form method="POST" action="">
                <textarea name="komentar" placeholder="Tulis komentar..." required></textarea>
                <button type="submit">Kirim</button>
            </form>
        <?php else: ?>
            <p><a href="login.php" class="login-button">Login untuk memberikan komentar</a></p>
        <?php endif; ?>

        <div class="comments-section">
            <div class="comment-description">
                <p>Berikan kritik dan saran Anda tentang tempat magang Anda di bawah ini!</p>
            </div>
            <?php while($row = $comments->fetch_assoc()): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($row['nama']); ?></strong> 
                    <small><?php echo htmlspecialchars($row['waktu']); ?></small>
                    <p><?php echo htmlspecialchars($row['komentar']); ?></p>
                    <?php if ($is_logged_in && $row['nama'] == $_SESSION['username']): ?>
                        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                        <a href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus komentar ini?')">Hapus</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Paginasi -->
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="index.php?page=<?php echo $current_page - 1; ?>">« Sebelumnya</a>
            <?php endif; ?>
            <span>Halaman <?php echo $current_page; ?> dari <?php echo $total_pages; ?></span>
            <?php if ($current_page < $total_pages): ?>
                <a href="index.php?page=<?php echo $current_page + 1; ?>">Selanjutnya »</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
