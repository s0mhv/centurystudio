<title>Form proposal_pic</title>
<!DOCTYPE html>
<html>
<head>
    <title>Form proposal_pic</title>
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

// Cek apakah ada parameter id_proposal_pic dari GET request
if (isset($_GET['id_proposal_pic'])) {
    // Mengambil data berdasarkan id_proposal_pic yang dikirimkan
    $id_proposal_pic = $_GET['id_proposal_pic'];
    $proposal_pic_ambil = mysqli_query($koneksi, "SELECT * FROM proposal_pic WHERE id_proposal_pic='$id_proposal_pic'");
    $proposal_pic_edit = mysqli_fetch_array($proposal_pic_ambil);
}
?>

<div class="form-container">
    <form action="proposal_pic_proses.php" method="post">
        <?php if (isset($_GET['id_proposal_pic'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_proposal_pic" value="<?php echo $proposal_pic_edit['id_proposal_pic']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_proposal_pic']) ? 'EDIT DATA proposal_pic' : 'TAMBAH DATA proposal_pic'; ?></h3>
                </td>
            </tr>
            <tr>
                <td>Nomor Surat</td>
                <td>:</td>
                <td>
                <select name="id_proposal">
                    <?php 
                        $ambil_proposal = mysqli_query($koneksi,"SELECT * FROM proposal");
                        while ($proposal = mysqli_fetch_array($ambil_proposal))
                        {
                            $selected = isset($proposal_pic_edit['id_proposal']) && $proposal_pic_edit['id_proposal'] == $proposal['id_proposal'] ? 'selected' : '';
                            echo "<option value='{$proposal['id_proposal']}' $selected>{$proposal['nomor_surat']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>
                    <select id="nama-select" name="id_pic">
                        <?php 
                        $ambil_pic = mysqli_query($koneksi, "SELECT * FROM pic WHERE keterangan = 'external' ORDER BY nama ASC");
                        while ($pic = mysqli_fetch_array($ambil_pic)) {
                            $selected = isset($proposal_pic_edit['id_pic']) && $proposal_pic_edit['id_pic'] == $pic['id_pic'] ? 'selected' : '';
                            echo "<option value='" . $pic['id_pic'] . "' $selected>" . $pic['nama'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=proposal_pic_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
