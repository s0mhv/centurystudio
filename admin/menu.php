<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Mulai sesi jika belum dimulai
}

define('ROLE_SISWA', 4); // Ganti angka dengan ID role untuk siswa
define('ROLE_PIC_EXTERNAL', 1); // Ganti angka dengan ID role untuk PIC_EXTERNAL
define('ROLE_PIC_INTERNAL', 2); // Ganti angka dengan ID role untuk PIC_INTERNAL
define('ROLE_ADMIN', 3); // Ganti angka dengan ID role untuk PIC_INTERNAL

// Mapping ID role ke nama role
$role_names = [
    ROLE_SISWA => 'SISWA',
    ROLE_PIC_EXTERNAL => 'PIC External',
    ROLE_PIC_INTERNAL => 'PIC Internal',
    ROLE_ADMIN => 'Admin',
];

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
        // Tampilkan nama role, bukan ID-nya
        if (isset($role_names[$id_role])) {
            echo htmlspecialchars($role_names[$id_role]);
        } else {
            echo 'Role tidak ditemukan';
        }

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
