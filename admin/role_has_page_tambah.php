<!DOCTYPE html>
<html>
<head>
    <title>Form role_has_page</title>
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

// Cek apakah ada parameter id_role_has_page dari GET request
if (isset($_GET['id_role_has_page'])) {
    // Mengambil data berdasarkan id_role_has_page yang dikirimkan
    $id_role_has_page = $_GET['id_role_has_page'];
    $role_has_page_ambil = mysqli_query($koneksi, "SELECT * FROM role_has_page WHERE id_role_has_page='$id_role_has_page'");
    $role_has_page_edit = mysqli_fetch_array($role_has_page_ambil);
}
?>

<div class="form-container">
    <form action="role_has_page_proses.php" method="post">
        <?php if (isset($_GET['id_role_has_page'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_role_has_page" value="<?php echo $role_has_page_edit['id_role_has_page']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_role_has_page']) ? 'EDIT DATA role_has_page' : 'TAMBAH DATA role_has_page'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Role</td>
                <td>:</td>
                <td>
                <select name="id_role">
                    <?php 
                        $ambil_role = mysqli_query($koneksi,"SELECT * FROM role ORDER BY role ASC ");
                        while ($role = mysqli_fetch_array($ambil_role))
                        {
                            $selected = isset($role_pic_edit['id_role']) && $role_pic_edit['id_role'] == $role['id_role'] ? 'selected' : '';
                            echo "<option value='{$role['id_role']}' $selected>{$role['role']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>Page</td>
                <td>:</td>
                <td>
                <select name="id_page">
                    <?php 
                        $ambil_page = mysqli_query($koneksi,"SELECT * FROM page ORDER BY nama_menu ASC");
                        while ($page = mysqli_fetch_array($ambil_page))
                        {
                            $selected = isset($page_pic_edit['id_page']) && $page_pic_edit['id_page'] == $page['id_page'] ? 'selected' : '';
                            echo "<option value='{$page['id_page']}' $selected>{$page['nama_menu']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>CREATE</td>
                <td>:</td>
                <td><input type="checkbox" name="create" <?php echo isset($role_has_page_edit['create']) && $role_has_page_edit['create'] ? 'checked' : ''; ?>></td>
            </tr>
            <tr>
                <td>READ</td>
                <td>:</td>
                <td><input type="checkbox" name="read" <?php echo isset($role_has_page_edit['read']) && $role_has_page_edit['read'] ? 'checked' : ''; ?>></td>
            </tr>
            <tr>
                <td>UPDATE</td>
                <td>:</td>
                <td><input type="checkbox" name="update" <?php echo isset($role_has_page_edit['update']) && $role_has_page_edit['update'] ? 'checked' : ''; ?>></td>
            </tr>
            <tr>
                <td>DELETE</td>
                <td>:</td>
                <td><input type="checkbox" name="delete" <?php echo isset($role_has_page_edit['delete']) && $role_has_page_edit['delete'] ? 'checked' : ''; ?>></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=role_has_page_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
