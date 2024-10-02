<title>Form Agama</title>
<!DOCTYPE html>
<html>
<head>
    <title>Form Agama</title>
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

// Cek apakah ada parameter id_agama dari GET request
if (isset($_GET['id_agama'])) {
    // Mengambil data berdasarkan id_agama yang dikirimkan
    $id_agama = $_GET['id_agama'];
    $agama_ambil = mysqli_query($koneksi, "SELECT * FROM agama WHERE id_agama='$id_agama'");
    $agama_edit = mysqli_fetch_array($agama_ambil);
}
?>

<div class="form-container">
    <form action="agama_proses.php" method="post">
        <?php if (isset($_GET['id_agama'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_agama" value="<?php echo $agama_edit['id_agama']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_agama']) ? 'EDIT DATA AGAMA' : 'TAMBAH DATA JENIS ORGANISASI'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td><input type="text" name="agama" value="<?php echo isset($agama_edit['agama']) ? $agama_edit['agama'] : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=agama_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
