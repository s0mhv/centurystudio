<?php
include "../config/koneksi.php";

// Inisialisasi variabel $proposal_has_kategori_nilai_edit
$proposal_has_kategori_nilai_edit = [];

// Cek apakah ada parameter id_proposal_has_kategori_nilai dari GET request untuk edit
if (isset($_GET['id_proposal_has_kategori_nilai'])) {
    $id_proposal_has_kategori_nilai = $_GET['id_proposal_has_kategori_nilai'];
    $query = "SELECT * FROM proposal_has_kategori_nilai WHERE id_proposal_has_kategori_nilai = '$id_proposal_has_kategori_nilai'";
    $result = mysqli_query($koneksi, $query);
    $proposal_has_kategori_nilai_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Proposal Nilai</title>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

<div class="form-container">
    <form action="proposal_has_kategori_nilai_proses.php" method="post">
        <?php if (isset($_GET['id_proposal_has_kategori_nilai'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_proposal_has_kategori_nilai" value="<?php echo $proposal_has_kategori_nilai_edit['id_proposal_has_kategori_nilai']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="create">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_proposal_has_kategori_nilai']) ? 'EDIT DATA PROPOSAL NILAI' : 'TAMBAH DATA PROPOSAL NILAI'; ?></h3>
                </td>
            </tr>
            <tr>
            <td>Nomor Surat</td>
            <td>:</td>
            <td>
                <select id="proposal-select" name="id_proposal">
                    <?php 
                    $ambil_proposal = mysqli_query($koneksi, "SELECT proposal.*, organisasi.nama_organisasi 
                        FROM proposal 
                        JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
                        ORDER BY proposal.nomor_surat");
                    while ($proposal = mysqli_fetch_array($ambil_proposal)) {
                    echo "<option value='" . $proposal['id_proposal'] . "'>" . $proposal['nomor_surat'] . " - " . $proposal['nama_organisasi'] . "</option>";
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
            <td>Kategori Nilai</td>
            <td>:</td>
            <td>
                <select id="kategori_nilai-select" name="id_kategori_nilai">
                    <?php 
                    $ambil_kategori_nilai = mysqli_query($koneksi, "SELECT * FROM kategori_nilai");
                    while ($kategori_nilai = mysqli_fetch_array($ambil_kategori_nilai)) {
                    echo "<option value='" . $kategori_nilai['id_kategori_nilai'] . "'>" . $kategori_nilai['kategori_nilai'] ."</option>";
                    }
                    ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=proposal_has_kategori_nilai_tampil';">
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('#nama-select').select2({
            placeholder: "Cari nama...",
            allowClear: true
        });
    });
</script>
</body>
</html>
