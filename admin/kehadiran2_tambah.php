<!DOCTYPE html>
<html>
<head>
    <title>Form Kehadiran</title>
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
            background-color: black;
            color: white;
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

<?php
include "../config/koneksi.php";

// Cek apakah ada parameter id_kehadiran2 dari GET request
if (isset($_GET['id_kehadiran2'])) {
    // Mengambil data berdasarkan id_kehadiran2 yang dikirimkan
    $id_kehadiran2 = mysqli_real_escape_string($koneksi, $_GET['id_kehadiran2']);
    $kehadiran_ambil = mysqli_query($koneksi, "SELECT * FROM kehadiran2 WHERE id_kehadiran2='$id_kehadiran2'");
    $kehadiran_edit = mysqli_fetch_array($kehadiran_ambil);
}
?>

<div class="form-container">
    <form action="kehadiran2_proses.php" method="post">
        <?php if (isset($_GET['id_kehadiran2'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_kehadiran2" value="<?php echo htmlspecialchars($kehadiran_edit['id_kehadiran2']); ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_kehadiran2']) ? 'EDIT DATA KEHADIRAN' : 'TAMBAH DATA KEHADIRAN'; ?></h3>
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
                <td>Tanggal</td>
                <td>:</td>
                <td><input type="date" name="tanggal" value="<?php echo isset($kehadiran_edit['tanggal']) ? htmlspecialchars($kehadiran_edit['tanggal']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Waktu Masuk</td>
                <td>:</td>
                <td><input type="time" name="waktu_masuk" value="<?php echo isset($kehadiran_edit['waktu_masuk']) ? htmlspecialchars($kehadiran_edit['waktu_masuk']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Waktu Keluar</td>
                <td>:</td>
                <td><input type="time" name="waktu_keluar" value="<?php echo isset($kehadiran_edit['waktu_keluar']) ? htmlspecialchars($kehadiran_edit['waktu_keluar']) : ''; ?>"></td>
            </tr>
            <tr>
                <td>Status Kehadiran</td>
                <td>:</td>
                <td>
                    <select name="status_kehadiran">
                        <option value="Hadir" <?php if(isset($kehadiran_edit['status_kehadiran']) && $kehadiran_edit['status_kehadiran'] == 'Hadir') echo 'selected'; ?>>Hadir</option>
                        <option value="Tidak Hadir" <?php if(isset($kehadiran_edit['status_kehadiran']) && $kehadiran_edit['status_kehadiran'] == 'Tidak Hadir') echo 'selected'; ?>>Tidak Hadir</option>
                        <option value="Setengah Hari" <?php if(isset($kehadiran_edit['status_kehadiran']) && $kehadiran_edit['status_kehadiran'] == 'Setengah Hari') echo 'selected'; ?>>Setengah Hari</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>
                    <select name="keterangan">
                        <option value="Hadir" <?php if(isset($kehadiran_edit['keterangan']) && $kehadiran_edit['keterangan'] == 'Hadir') echo 'selected'; ?>>Hadir</option>
                        <option value="Sakit" <?php if(isset($kehadiran_edit['keterangan']) && $kehadiran_edit['keterangan'] == 'Sakit') echo 'selected'; ?>>Sakit</option>
                        <option value="Izin" <?php if(isset($kehadiran_edit['keterangan']) && $kehadiran_edit['keterangan'] == 'Izin') echo 'selected'; ?>>Izin</option>
                        <option value="Tidak Ada Keterangan" <?php if(isset($kehadiran_edit['keterangan']) && $kehadiran_edit['keterangan'] == 'Tidak Ada Keterangan') echo 'selected'; ?>>Tidak Ada Keterangan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="window.location.href='index.php?page=kehadiran2_tampil';">
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