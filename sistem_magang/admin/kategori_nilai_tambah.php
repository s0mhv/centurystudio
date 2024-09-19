<!DOCTYPE html>
<html>
<head>
    <title>Form Kategori Nilai</title>
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

// Cek apakah ada parameter id_kategori_nilai dari GET request
if (isset($_GET['id_kategori_nilai'])) {
    // Mengambil data berdasarkan id_kategori_nilai yang dikirimkan
    $id_kategori_nilai = $_GET['id_kategori_nilai'];
    $kategori_nilai_ambil = mysqli_query($koneksi, "SELECT * FROM kategori_nilai WHERE id_kategori_nilai='$id_kategori_nilai'");
    $kategori_nilai_edit = mysqli_fetch_array($kategori_nilai_ambil);
}
?>

<div class="form-container">
    <form action="kategori_nilai_proses.php" method="post">
        <?php if (isset($_GET['id_kategori_nilai'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_kategori_nilai" value="<?php echo $kategori_nilai_edit['id_kategori_nilai']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_kategori_nilai']) ? 'EDIT DATA KATEGORI NILAI' : 'TAMBAH DATA KATEGORI NILAI'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Kategori Nilai</td>
                <td>:</td>
                <td><input type="text" name="kategori_nilai" value="<?php echo isset($kategori_nilai_edit['kategori_nilai']) ? $kategori_nilai_edit['kategori_nilai'] : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=kategori_nilai_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>
