<!DOCTYPE html>
<html>
<head>
    <title>Form Pengawas Magang</title>
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
        input[type="text"], input[type="date"], select {
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

if (isset($_GET['id_has_tugas'])) {
    $id_has_tugas = $_GET['id_has_tugas'];
    $id_has_tugas_ambil = mysqli_query($koneksi, "SELECT * FROM peserta_magang_has_tugas WHERE id_has_tugas='$id_has_tugas'")
    or die(mysqli_error($koneksi));
    $id_has_tugas_edit = mysqli_fetch_array($id_has_tugas_ambil);
}
?>

<div class="form-container">
    <form action="peserta_magang_has_tugas_proses.php" method="post" enctype="multipart/form-data">
        <?php if (isset($_GET['id_has_tugas'])) : ?>
            <input type="hidden" name="status" value="edit">
        <?php else : ?>
            <input type="hidden" name="status" value="tambah">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3>TAMBAH DATA</h3>
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
                <td>Deskripsi Tugas</td>
                <td>:</td>
                <td>
                    <select name="id_deskripsi_tugas" id="id_deskripsi_tugas" required>
                        <option value="">Pilih Deskripsi Tugas</option>
                        <?php
                        $deskripsi_tugas = mysqli_query($koneksi, "SELECT deskripsi_tugas.id_deskripsi_tugas, deskripsi_tugas.deskripsi_tugas, organisasi.nama_organisasi 
                            FROM deskripsi_tugas
                            JOIN proposal ON deskripsi_tugas.id_proposal = proposal.id_proposal
                            JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
                            ORDER BY deskripsi_tugas.id_deskripsi_tugas");
                        while ($data_deskripsi_tugas = mysqli_fetch_array($deskripsi_tugas)) {
                            $selected = ($id_has_tugas_edit['id_deskripsi_tugas'] == $data_deskripsi_tugas['id_deskripsi_tugas']) ? 'selected' : '';
                            echo "<option value='" . $data_deskripsi_tugas['id_deskripsi_tugas'] . "'>" . $data_deskripsi_tugas['deskripsi_tugas'] . " - " . $data_deskripsi_tugas['nama_organisasi'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>
                    <select name="keterangan">
                        <option value="selesai" <?php if(isset($id_has_tugas_edit['keterangan']) && $id_has_tugas_edit['keterangan'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                        <option value="belum selesai" <?php if(isset($id_has_tugas_edit['keterangan']) && $id_has_tugas_edit['keterangan'] == 'belum selesai') echo 'selected'; ?>>belum selesai</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Realisasi Pengumpulan</td>
                <td>:</td>
                <td><input type="datetime-local" name="realisasi_pengumpulan" value="<?php echo isset($id_has_tugas_edit['realisasi_pengumpulan']) ? date('Y-m-d\TH:i', strtotime($id_has_tugas_edit['realisasi_pengumpulan'])) : ''; ?>"></td>
            </tr>
            <tr>
                <td>nilai</td>
                <td>:</td>
                <td><input type="int" name="nilai" value="<?php echo @$id_has_tugas_edit['nilai']; ?>"></td>
            </tr>
            <tr>
                <td>File Tugas</td>
                <td>:</td>
                <td>
                    <input type="file" name="file_tugas">
                    <?php if (!empty($id_has_tugas_edit['file_tugas'])): ?>
                        <a href="http://localhost/sistem_magang/file/<?php echo $id_has_tugas_edit['file_tugas']; ?>" target="_blank">Lihat File</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" value="SIMPAN">
                    <input type="reset" value="BATAL" onclick="self.history.back()">
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
