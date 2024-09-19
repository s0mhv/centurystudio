<?php
define('ROLE_SISWA', 4); // Ganti angka dengan ID role untuk siswa
define('ROLE_PIC_EXTERNAL', 1); // Ganti angka dengan ID role untuk PIC_EXTERNAL

// Pastikan file koneksi di-include
include "../config/koneksi.php";

// Cek apakah koneksi berhasil di-include
if (!isset($koneksi)) {
    die("Koneksi database tidak ditemukan. Periksa file koneksi.php.");
}

// Ambil data dari session
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : null;
$id_role = isset($_SESSION['id_role']) ? $_SESSION['id_role'] : null;
$id_anggota = null;
$id_pic = null; // Inisialisasi id_pic sebagai null

// Hanya ambil id_anggota jika role adalah 'Siswa'
if ($id_role === ROLE_SISWA) {
    $id_anggota = isset($_SESSION['id_anggota']) ? $_SESSION['id_anggota'] : null;
}

// Debugging: Periksa apakah id_anggota ada di session
if ($id_role === ROLE_SISWA) {
    if ($id_anggota === null) {
        echo "<p style='color: red;'>ID Anggota tidak ditemukan dalam session.</p>";
    } else {
        echo "<p style='color: green;'>ID Anggota ditemukan: $id_anggota</p>";
    }
} else {
    echo "<p style='color: blue;'>ID Anggota tidak diperlukan untuk role ini.</p>";
}

// Cek apakah role adalah PIC_EXTERNAL
if ($id_role === ROLE_PIC_EXTERNAL) {
    // Ambil id_pic dari session
    $id_pic = isset($_SESSION['id_pic']) ? $_SESSION['id_pic'] : null;

    // Debugging: Periksa apakah id_pic ada di session
    if ($id_pic === null) {
        echo "<p style='color: red;'>ID PIC tidak ditemukan dalam session.</p>";
    } else {
        echo "<p style='color: green;'>ID PIC ditemukan: $id_pic</p>";
    }
} else {
    echo "<p style='color: blue;'>ID PIC tidak diperlukan untuk role ini.</p>";
}

// Query untuk mendapatkan akses page dari tabel role_has_page
if ($id_role) {
    $query = "SELECT p.nama_menu, p.link 
              FROM role_has_page rhp
              JOIN page p ON rhp.id_page = p.id_page
              WHERE rhp.id_role = ? AND rhp.read = 1";

    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        die("Prepare statement gagal: " . $koneksi->error);
    }

    $stmt->bind_param("i", $id_role);
    $stmt->execute();
    $result = $stmt->get_result();

    $pages = [];
    while ($row = $result->fetch_assoc()) {
        $pages[] = $row;
    }

    $stmt->close();
} else {
    die("ID Role tidak ditemukan di session.");
}

// Pastikan current_page di-set sebelumnya
$current_page = isset($_GET['page']) ? $_GET['page'] : '';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<span>
    <br align="center">SELAMAT DATANG, <?= htmlspecialchars($nama) ?>, ROLE: 
    <?php 
        // Ensure that $_SESSION['role'] is an array
        $roles = is_array($_SESSION['role']) ? $_SESSION['role'] : [$_SESSION['role']];
        echo htmlspecialchars(implode(', ', $roles));

        // Debug: Cek apakah ada id_proposal di sesi atau dari query
        if (isset($_SESSION['id_proposal'])) {
            echo "<br>ID Proposal (from session): " . htmlspecialchars($_SESSION['id_proposal']);
        } else {
            echo "<br>ID Proposal not found in session.";
        }
    ?></br>

    <ul>
    <?php if (!empty($pages)): ?>
        <?php foreach ($pages as $page): ?>
            <?php
            // Ambil parameter 'page' dari link
            $page_query = parse_url($page['link'], PHP_URL_QUERY);
            parse_str($page_query, $params);
            $page_name = isset($params['page']) ? $params['page'] : '';
            ?>
            <li class="<?= $current_page == $page_name ? 'active' :''?>">
            <a href="<?= htmlspecialchars($page['link'], ENT_QUOTES, 'UTF-8') ?>"><?= strtoupper(htmlspecialchars($page['nama_menu'], ENT_QUOTES, 'UTF-8')) ?></a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p align="center">Tidak ada menu yang tersedia untuk role ini.</p>
    <?php endif; ?>
    </ul>
    <ul>
        <li class="logout">
            <a href="logout.php">LOGOUT</a>
        </li>
    </ul>
</span>

