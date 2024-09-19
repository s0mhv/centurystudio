<title>Form role</title>
<!DOCTYPE html>
<html>
<head>
    <title>Form role</title>
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

// Cek apakah ada parameter id_role dari GET request
if (isset($_GET['id_role'])) {
    // Mengambil data berdasarkan id_role yang dikirimkan
    $id_role = $_GET['id_role'];
    $role_ambil = mysqli_query($koneksi, "SELECT * FROM role WHERE id_role='$id_role'");
    $role_edit = mysqli_fetch_array($role_ambil);
}
?>

<div class="form-container">
    <form action="role_proses.php" method="post">
        <?php if (isset($_GET['id_role'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_role" value="<?php echo $role_edit['id_role']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_role']) ? 'EDIT DATA ROLE' : 'TAMBAH DATA ROLE'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>role</td>
                <td>:</td>
                <td><input type="text" name="role" value="<?php echo isset($role_edit['role']) ? $role_edit['role'] : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=role_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
