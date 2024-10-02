<!DOCTYPE html>
<html>
<head>
    <title>Form Bidang Keahlian</title>
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

// Cek apakah ada parameter id_bidang_keahlian dari GET request
if (isset($_GET['id_bidang_keahlian'])) {
    // Mengambil data berdasarkan id_bidang_keahlian yang dikirimkan
    $id_bidang_keahlian = $_GET['id_bidang_keahlian'];
    $bidang_keahlian_ambil = mysqli_query($koneksi, "SELECT * FROM bidang_keahlian WHERE id_bidang_keahlian='$id_bidang_keahlian'");
    $bidang_keahlian_edit = mysqli_fetch_array($bidang_keahlian_ambil);
}
?>

<div class="form-container">
    <form action="bidang_keahlian_proses.php" method="post">
        <?php if (isset($_GET['id_bidang_keahlian'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_bidang_keahlian" value="<?php echo $bidang_keahlian_edit['id_bidang_keahlian']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_bidang_keahlian']) ? 'EDIT DATA Bidang Keahlian' : 'TAMBAH DATA JENIS ORGANISASI'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Bidang Keahlian</td>
                <td>:</td>
                <td><input type="text" name="bidang_keahlian" value="<?php echo isset($bidang_keahlian_edit['bidang_keahlian']) ? $bidang_keahlian_edit['bidang_keahlian'] : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=bidang_keahlian_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>
