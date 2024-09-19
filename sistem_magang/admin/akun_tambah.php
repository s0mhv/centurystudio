<title>Form Akun</title>
<!DOCTYPE html>
<html>
<head>
    <title>Form Akun</title>
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
            color:#000000;
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

// Cek apakah ada parameter id_akun dari GET request
if (isset($_GET['id_akun'])) {
    // Mengambil data berdasarkan id_akun yang dikirimkan
    $id_akun = $_GET['id_akun'];
    $akun_ambil = mysqli_query($koneksi, "SELECT * FROM akun WHERE id_akun='$id_akun'");
    $akun_edit = mysqli_fetch_array($akun_ambil);
}
?>

<div class="form-container">
    <form action="akun_proses.php" method="post">
        <?php if (isset($_GET['id_akun'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_akun" value="<?php echo $akun_edit['id_akun']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_akun']) ? 'EDIT DATA AKUN' : 'TAMBAH DATA AKUN'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td><input type="text" name="email" value="<?php echo isset($akun_edit['email']) ? $akun_edit['email'] : ''; ?>" ></td>
            </tr>
            <tr>
                <td>Password</td>
                <td>:</td>
                <td><input type="text" name="pass" value="<?php echo isset($akun_edit['pass']) ? $akun_edit['pass'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><input type="text" name="nama" value="<?php echo isset($akun_edit['nama']) ? $akun_edit['nama'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Role</td>
                <td>:</td>
                <td>
                    <select name="id_role" id="id_role">
                        <option value="">Pilih Role</option>
                        <?php
                        $role_query = mysqli_query($koneksi, "SELECT id_role, role FROM role");
                        while ($role = mysqli_fetch_assoc($role_query)) {
                            echo "<option value='" . $role['id_role'] . "'>" . $role['role'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=akun_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
