<?php
include "../config/koneksi.php";

// Inisialisasi variabel $kegiatan_harian_edit
$kegiatan_harian_edit = [];

// Cek apakah ada parameter id_kegiatan_harian dari GET request untuk edit
if (isset($_GET['id_kegiatan_harian'])) {
    $id_kegiatan_harian = $_GET['id_kegiatan_harian'];
    $query = "SELECT * FROM kegiatan_harian WHERE id_kegiatan_harian = '$id_kegiatan_harian'";
    $result = mysqli_query($koneksi, $query);
    $kegiatan_harian_edit = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Kegiatan Harian</title>
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
    <form action="kegiatan_harian_proses.php" method="post">
        <?php if (isset($_GET['id_kegiatan_harian'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_kegiatan_harian" value="<?php echo $kegiatan_harian_edit['id_kegiatan_harian']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="create">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_kegiatan_harian']) ? 'EDIT DATA KEGIATAN HARIAN' : 'TAMBAH DATA KEGIATAN HARIAN'; ?></h3>
                </td>
            </tr>
            <tr>
            <td>Nama</td>
            <td>:</td>
            <td>
                <select id="nama-select" name="id_anggota">
                    <?php 
                    $ambil_peserta_magang = mysqli_query($koneksi, "SELECT peserta_magang.*, organisasi.nama_organisasi 
                        FROM peserta_magang 
                        JOIN proposal ON peserta_magang.id_proposal = proposal.id_proposal 
                        JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
                        WHERE peserta_magang.keterangan = 'Masih proses' 
                        ORDER BY peserta_magang.nama ASC");
                    while ($peserta_magang = mysqli_fetch_array($ambil_peserta_magang)) {
                    echo "<option value='" . $peserta_magang['id_anggota'] . "'>" . $peserta_magang['nama'] . " - " . $peserta_magang['nama_organisasi'] . "</option>";
                    }
                    ?>
                    </select>
                </td>
            </tr> 
            <tr>
                <td>Kegiatan Harian</td>
                <td>:</td>
                <td><input type="text" name="kegiatan_harian" value="<?php echo isset($kegiatan_harian_edit['kegiatan_harian']) ? $kegiatan_harian_edit['kegiatan_harian'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Desksripsi</td>
                <td>:</td>
                <td><input type="text" name="deskripsi" value="<?php echo isset($kegiatan_harian_edit['deskripsi']) ? $kegiatan_harian_edit['deskripsi'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Tanaggal Pengerjaan</td>
                <td>:</td>
                <td><input type="date" name="tanggal_pengerjaan" value="<?php echo isset($kegiatan_harian_edit['tanggal_pengerjaan']) ? $kegiatan_harian_edit['tanggal_pengerjaan'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Status Kegiatan</td>
                <td>:</td>
                <td>
                    <select name="status_kegiatan">
                        <option value="Selesai" <?php if(isset($kegiatan_harian_edit['status_kegiatan']) && $kegiatan_harian_edit['status_kegiatan'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                        <option value="Tidak Selesai" <?php if(isset($kegiatan_harian_edit['status_kegiatan']) && $kegiatan_harian_edit['status_kegiatan'] == 'Tidak Selesai') echo 'selected'; ?>>Tidak Selesai</option>
                    </select>

                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=kegiatan_harian_tampil';">
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
