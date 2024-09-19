<!DOCTYPE html>
<html>
<head>
    <title>Form PIC</title>
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 20px auto;
            border: 1px solid #ddd;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #black;
        }
        td {
            background-color: #f9f9f9;
        }
        h3 {
            color:#000;
            text-align: center;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        input[type="submit"], input[type="button"] {
            padding: 10px 20px;
            margin: 10px 5px;
        }
        .form-container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
    </style>
</head>
<body>

<?php
include "../config/koneksi.php";

// Cek apakah ada parameter id_pic dari GET request
if (isset($_GET['id_pic'])) {
    // Mengambil data berdasarkan id_pic yang dikirimkan
    $id_pic = $_GET['id_pic'];
    $pic_ambil = mysqli_query($koneksi, "SELECT * FROM pic WHERE id_pic='$id_pic'");
    $pic_edit = mysqli_fetch_array($pic_ambil);
}
?>

<div class="form-container">
    <form action="pic_proses.php" method="post">
        <?php if (isset($_GET['id_pic'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_pic" value="<?php echo $pic_edit['id_pic']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_pic']) ? 'EDIT DATA PIC' : 'TAMBAH DATA PIC'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><input type="text" name="nama" value="<?php echo isset($pic_edit['nama']) ? $pic_edit['nama'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>
                    <select name="keterangan">
                        <option value="internal" <?php echo isset($pic_edit['keterangan']) && $pic_edit['keterangan'] == 'internal' ? 'selected' : ''; ?>>Internal</option>
                        <option value="external" <?php echo isset($pic_edit['keterangan']) && $pic_edit['keterangan'] == 'external' ? 'selected' : ''; ?>>External</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Username</td>
                <td>:</td>
                <td>
                <select name="id_akun">
                    <?php 
                        $ambil_akun = mysqli_query($koneksi,"SELECT * FROM akun");
                        while ($akun = mysqli_fetch_array($ambil_akun))
                        {
                            $selected = isset($pic_edit['id_akun']) && $pic_edit['id_akun'] == $akun['id_akun'] ? 'selected' : '';
                            echo "<option value='{$akun['email']}' $selected>{$akun['email']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=pic_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
