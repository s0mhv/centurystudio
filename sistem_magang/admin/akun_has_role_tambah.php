<title>Form Role Akun</title>
<!DOCTYPE html>
<html>
<head>
    <title>Form Role Akun</title>
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

// Cek apakah ada parameter id_akun_has_role dari GET request
if (isset($_GET['id_akun_has_role'])) {
    // Mengambil data berdasarkan id_akun_has_role yang dikirimkan
    $id_akun_has_role = $_GET['id_akun_has_role'];
    $akun_has_role_ambil = mysqli_query($koneksi, "SELECT * FROM akun_has_role WHERE id_akun_has_role='$id_akun_has_role'");
    $akun_has_role_edit = mysqli_fetch_array($akun_has_role_ambil);
}
?>

<div class="form-container">
    <form action="akun_has_role_proses.php" method="post">
        <?php if (isset($_GET['id_akun_has_role'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_akun_has_role" value="<?php echo $akun_has_role_edit['id_akun_has_role']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_akun_has_role']) ? 'EDIT DATA ROLE AKUN' : 'TAMBAH DATA ROLE AKUN'; ?></h3>
                </td>
            </tr>
            <tr>
            <td>Email</td>
            <td>:</td>
            <td>
                <select id="nama-select" name="id_akun">
                    <?php 
                    $ambil_akun = mysqli_query($koneksi, "SELECT id_akun, email FROM akun");
                    while ($akun = mysqli_fetch_array($ambil_akun)) {
                        echo "<option value='" . $akun['id_akun'] . "'>" . $akun['email'] ."</option>";
                    }
                    ?>
                </select>
            </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=akun_has_role_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
