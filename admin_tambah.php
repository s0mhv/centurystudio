<?php
include "../config/koneksi.php";
if (isset($_GET['username'])) {
    $agama_ambil = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE username='$_GET[username]'")
    or die ();
    $guru_edit = mysqli_fetch_array($agama_ambil);
}
?>

<form action="admin_proses.php" method="post" enctype="multipart/form-data">
    <?php
    if (isset($_GET['username'])) {
        echo "<input type='hidden' name='status' value='edit'>";
    } else {
        echo "<input type='hidden' name='status' value='tambah'>";
    }
    ?>

    <table>
        <tr>
            <td>
                <h3>TAMBAH DATA ADMIN</h3>
            </td>
        </tr>

        <tr>
            <td>USERNAME</td>
            <td>:</td>
            <td><input type="text" maxlength="255" size="11" name="username" value="<?php echo @$guru_edit['username']; ?>" required></td>
        </tr>

        <tr>
            <td>PASSWORD</td>
            <td>:</td>
            <td><input type="password" maxlength="11" size="10" name="password" value="<?php echo @$guru_edit['password']; ?>" required></td>
        </tr>

        <tr>
            <td>NAMA ADMIN</td>
            <td>:</td>
            <td><input type="text" maxlength="11" size="10" name="nama_admin" value="<?php echo @$guru_edit['nama_admin']; ?>" required></td>
        </tr>

        <tr>
            <td>FOTO ADMIN</td>
            <td>:</td>
            <td>
                <?php 
                if (isset($_GET['username'])){
                    echo "<img src='$guru_edit[foto_admin]' width=100 height=100>";
                }
                ?>
                <input type="file" name="image" maxlength="255" size="10" value="<?php echo @$guru_edit['foto_admin']; ?>" >
                <?php 
                if (isset($_GET['username'])){?>
                    </br><input type="checkbox" name="centang" value="1"/>Centang jika ingin ganti
                <?php }?>
            </td>
        </tr>


        <tr>
            <td colspan=2>
                <input type="submit" value="SIMPAN">
                <input type="button" value="BATAL" >
            </td>
        </tr>

    </table>
</form>