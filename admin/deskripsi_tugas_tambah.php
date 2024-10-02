<?php
    include "../config/koneksi.php";
    //untuk menampilkan data yang dipilih kedalam textbox
    if(isset($_GET['id_deskripsi_tugas'])){
        //mengambil data sesuai yang dikklik oleh user
        $deskripsi_tugas_ambil = mysqli_query($koneksi,"SELECT * FROM deskripsi_tugas WHERE id_deskripsi_tugas='$_GET[id_deskripsi_tugas]'")
        or die (mysqli_error($koneksi));
        $deskripsi_tugas_edit = mysqli_fetch_array($deskripsi_tugas_ambil);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Deskripsi Tugas</title>
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
            color : #000;
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

<div class="form-container">
    <form action="deskripsi_tugas_proses.php" method="post">
        <?php
        if(isset($_GET['id_deskripsi_tugas'])) {
            echo "<input type='hidden' name='id_deskripsi_tugas' value='".$_GET['id_deskripsi_tugas']."'>";
            echo "<input type='hidden' name='status' value='edit'>";
        } else {
            echo "<input type='hidden' name='status' value='tambah'>";
        }
        ?>
        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_deskripsi_tugas']) ? 'EDIT DATA DESKRIPSI TUGAS' : 'TAMBAH DATA DESKRIPSI TUGAS'; ?></h3>
                </td>
            </tr>
            <tr>
            <tr>
                <td>Organisasi</td>
                <td>:</td>
                <td>
                    <select id="nama-select" name="id_proposal">
                    <?php 
                        $ambil_proposal = mysqli_query($koneksi, "SELECT proposal.*, organisasi.nama_organisasi 
                            FROM proposal 
                            LEFT JOIN deskripsi_tugas ON deskripsi_tugas.id_proposal = proposal.id_proposal 
                            LEFT JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
                            ORDER BY proposal.id_proposal ASC");

                        if (!$ambil_proposal) {
                            die("Query error: " . mysqli_error($koneksi));
                        }

                        while ($proposal = mysqli_fetch_array($ambil_proposal)) {
                            echo "<option value='" . $proposal['id_proposal'] . "'>" . $proposal['nama_organisasi'] . " - " . $proposal['nomor_surat'] . "</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
                <td>Deskripsi Tugas</td>
                <td>:</td>
                <td><input type="text" name="deskripsi_tugas" value="<?php echo isset($deskripsi_tugas_edit['deskripsi_tugas']) ? $deskripsi_tugas_edit['deskripsi_tugas'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Tanggal Pemberian</td>
                <td>:</td>
                <td><input type="datetime-local" name="tanggal_pemberian" value="<?php echo isset($deskripsi_tugas_edit['tanggal_pemberian']) ? htmlspecialchars($deskripsi_tugas_edit['tanggal_pemberian']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Tanggal Pengumpulan</td>
                <td>:</td>
                <td><input type="datetime-local" name="tanggal_pengumpulan" value="<?php echo isset($deskripsi_tugas_edit['tanggal_pengumpulan']) ? htmlspecialchars($deskripsi_tugas_edit['tanggal_pengumpulan']) : ''; ?>"></td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=deskripsi_tugas_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>