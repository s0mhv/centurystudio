<!DOCTYPE html>
<html>
<head>
    <title>Form Jenis Organisasi</title>
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

// Cek apakah ada parameter id_kota dari GET request
if (isset($_GET['id_kota'])) {
    // Mengambil data berdasarkan id_kota yang dikirimkan
    $id_kota = $_GET['id_kota'];
    $kota_ambil = mysqli_query($koneksi, "SELECT * FROM kota WHERE id_kota='$id_kota'");
    $kota_edit = mysqli_fetch_array($kota_ambil);
}
?>

<div class="form-container">
    <form action="kota_proses.php" method="post">
        <?php if (isset($_GET['id_kota'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_kota" value="<?php echo $kota_edit['id_kota']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_kota']) ? 'EDIT DATA JENIS ORGANISASI' : 'TAMBAH DATA JENIS ORGANISASI'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Kota</td>
                <td>:</td>
                <td><input type="text" name="kota" value="<?php echo isset($kota_edit['kota']) ? $kota_edit['kota'] : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=kota_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>
