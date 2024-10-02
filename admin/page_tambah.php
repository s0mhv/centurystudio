<!DOCTYPE html>
<html>
<head>
    <title>Form page</title>
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

// Cek apakah ada parameter id_page dari GET request
if (isset($_GET['id_page'])) {
    // Mengambil data berdasarkan id_page yang dikirimkan
    $id_page = $_GET['id_page'];
    $page_ambil = mysqli_query($koneksi, "SELECT * FROM page WHERE id_page='$id_page'");
    $page_edit = mysqli_fetch_array($page_ambil);
}
?>

<div class="form-container">
    <form action="page_proses.php" method="post">
        <?php if (isset($_GET['id_page'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_page" value="<?php echo $page_edit['id_page']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_page']) ? 'EDIT DATA PAGE' : 'TAMBAH DATA PAGE'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Nama Menu</td>
                <td>:</td>
                <td><input type="text" name="nama_menu" value="<?php echo isset($page_edit['nama_menu']) ? $page_edit['nama_menu'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Link Page</td>
                <td>:</td>
                <td><input type="text" name="link" value="<?php echo isset($page_edit['link']) ? $page_edit['link'] : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=page_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
