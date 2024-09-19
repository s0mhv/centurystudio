<?php
include "../config/koneksi.php";

// Inisialisasi variabel $organisasi_edit
$organisasi_edit = [];

// Cek apakah ada parameter id_organisasi dari GET request untuk edit
if (isset($_GET['id_organisasi'])) {
    $id_organisasi = $_GET['id_organisasi'];
    $query = "SELECT * FROM organisasi WHERE id_organisasi = '$id_organisasi'";
    $result = mysqli_query($koneksi, $query);
    $organisasi_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Organisasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .form-container {
            width: 60%;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"], select {
            width: calc(100% - 20px);
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="button"] {
            padding: 10px 20px;
            margin: 10px 5px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #45a049;
        }
        input[type="submit"]:focus, input[type="button"]:focus {
            outline: none;
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
            color:#000;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form action="organisasi_proses.php" method="post">
        <?php if (isset($_GET['id_organisasi'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_organisasi" value="<?php echo htmlspecialchars($organisasi_edit['id_organisasi']); ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="create">
        <?php endif; ?>

        <h3><?php echo isset($_GET['id_organisasi']) ? 'EDIT DATA ORGANISASI' : 'TAMBAH DATA ORGANISASI'; ?></h3>

        <table>
            <tr>
                <td>KOTA</td>
                <td>:</td>
                <td>
                    <select name="kota" required>
                        <option value="">Pilih Kota</option>
                        <?php 
                        $ambil_kota = mysqli_query($koneksi, "SELECT * FROM kota");
                        while ($kota = mysqli_fetch_assoc($ambil_kota)) {
                            $selected = ($organisasi_edit['id_kota'] == $kota['id_kota']) ? "selected" : "";
                            echo "<option value='{$kota['id_kota']}' $selected>{$kota['kota']}</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>JENIS ORGANISASI</td>
                <td>:</td>
                <td>
                    <select name="jenis_organisasi" required>
                        <option value="">Pilih Jenis Organisasi</option>
                        <?php 
                        $ambil_jenis_organisasi = mysqli_query($koneksi, "SELECT * FROM jenis_organisasi");
                        while ($jenis_organisasi = mysqli_fetch_assoc($ambil_jenis_organisasi)) {
                            $selected = ($organisasi_edit['id_jenis_organisasi'] == $jenis_organisasi['id_jenis_organisasi']) ? "selected" : "";
                            echo "<option value='{$jenis_organisasi['id_jenis_organisasi']}' $selected>{$jenis_organisasi['jenis_organisasi']}</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Nama Organisasi</td>
                <td>:</td>
                <td><input type="text" name="nama_organisasi" value="<?php echo isset($organisasi_edit['nama_organisasi']) ? htmlspecialchars($organisasi_edit['nama_organisasi']) : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td><input type="text" name="email" value="<?php echo isset($organisasi_edit['email']) ? htmlspecialchars($organisasi_edit['email']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Nomor Telepon</td>
                <td>:</td>
                <td><input type="text" name="no_telepon" value="<?php echo isset($organisasi_edit['no_telepon']) ? htmlspecialchars($organisasi_edit['no_telepon']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><input type="text" name="alamat" value="<?php echo isset($organisasi_edit['alamat']) ? htmlspecialchars($organisasi_edit['alamat']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Website</td>
                <td>:</td>
                <td><input type="text" name="website" value="<?php echo isset($organisasi_edit['website']) ? htmlspecialchars($organisasi_edit['website']) : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=organisasi_tampil';">
                </td>
            </tr>
        </table>
    </form>

    <?php
    // Menampilkan pesan error jika ada
    if (!empty($errors)) {
        echo '<div class="error-message">';
        echo '<ul>';
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
        echo '</div>';
    }

    // Menampilkan pesan sukses jika ada
    if (!empty($success_message)) {
        echo '<div class="success-message">';
        echo $success_message;
        echo '</div>';
    }
    ?>
</div>

</body>
</html>