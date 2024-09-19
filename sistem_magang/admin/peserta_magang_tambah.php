<!DOCTYPE html>
<html>
<head>
    <title>Form Peserta Magang</title>
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
            color:#000;
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script>
        $(document).ready(function() {
            $("select[name='id_proposal']").change(function() {
                var idProposal = $(this).val();
                $.ajax({
                    url: 'get_organisasi.php',
                    type: 'POST',
                    data: {id_proposal: idProposal},
                    success: function(data) {
                        $("select[name='id_organisasi']").html(data);
                    }
                });
            });
        });
    </script> -->
</head>
<body>

<?php
include "../config/koneksi.php";

// Cek apakah ada parameter id_anggota dari GET request
if (isset($_GET['id_anggota'])) {
    // Mengambil data berdasarkan id_anggota yang dikirimkan
    $id_anggota = $_GET['id_anggota'];
    $peserta_magang_ambil = mysqli_query($koneksi, "SELECT * FROM peserta_magang WHERE id_anggota='$id_anggota'");
    $peserta_magang_edit = mysqli_fetch_array($peserta_magang_ambil);
}
?>

<div class="form-container">
    <form action="peserta_magang_proses.php" method="post">
        <?php if (isset($_GET['id_anggota'])) : ?>
            <input type="hidden" name="status" value="edit">
            <input type="hidden" name="id_anggota" value="<?php echo $peserta_magang_edit['id_anggota']; ?>">
        <?php else : ?>
            <input type="hidden" name="status" value="create">
        <?php endif; ?>

        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_anggota']) ? 'EDIT DATA PESERTA MAGANG' : 'TAMBAH DATA PESERTA MAGANG'; ?></h3>
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
                            $selected = isset($peserta_magang_edit['id_proposal']) && $peserta_magang_edit['id_proposal'] == $proposal['id_proposal'] ? 'selected' : '';
                            echo "<option value='{$proposal['id_proposal']}' $selected>{$proposal['nomor_surat']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><input type="text" name="nik" value="<?php echo isset($peserta_magang_edit['nik']) ? $peserta_magang_edit['nik'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><input type="text" name="nama" value="<?php echo isset($peserta_magang_edit['nama']) ? $peserta_magang_edit['nama'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Tanggal Lahir</td>
                <td>:</td>
                <td><input type="date" name="tanggal_lahir" value="<?php echo isset($peserta_magang_edit['tanggal_lahir']) ? $peserta_magang_edit['tanggal_lahir'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><input type="text" name="alamat" value="<?php echo isset($peserta_magang_edit['alamat']) ? $peserta_magang_edit['alamat'] : ''; ?>"></td>
            </tr>
            <tr>
                <td>Golongan Darah</td>
                <td>:</td>
                <td>
                    <select name="golongan_darah">
                        <option value="A" <?php echo isset($peserta_magang_edit['golongan_darah']) && $peserta_magang_edit['golongan_darah'] == 'A' ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?php echo isset($peserta_magang_edit['golongan_darah']) && $peserta_magang_edit['golongan_darah'] == 'B' ? 'selected' : ''; ?>>B</option>
                        <option value="AB" <?php echo isset($peserta_magang_edit['golongan_darah']) && $peserta_magang_edit['golongan_darah'] == 'AB' ? 'selected' : ''; ?>>AB</option>
                        <option value="O" <?php echo isset($peserta_magang_edit['golongan_darah']) && $peserta_magang_edit['golongan_darah'] == 'O' ? 'selected' : ''; ?>>O</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>
                    <select name="jenis_kelamin">
                        <option value="L" <?php echo isset($peserta_magang_edit['jenis_kelamin']) && $peserta_magang_edit['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-Laki</option>
                        <option value="P" <?php echo isset($peserta_magang_edit['jenis_kelamin']) && $peserta_magang_edit['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Bidang Keahlian</td>
                <td>:</td>
                <td>
                <select name="id_bidang_keahlian">
                    <?php 
                        $ambil_bidang_keahlian = mysqli_query($koneksi,"SELECT * FROM bidang_keahlian");
                        while ($bidang_keahlian = mysqli_fetch_array($ambil_bidang_keahlian))
                        {
                            $selected = isset($peserta_magang_edit['id_bidang_keahlian']) && $peserta_magang_edit['id_bidang_keahlian'] == $bidang_keahlian['id_bidang_keahlian'] ? 'selected' : '';
                            echo "<option value='{$bidang_keahlian['id_bidang_keahlian']}' $selected>{$bidang_keahlian['bidang_keahlian']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>Kota</td>
                <td>:</td>
                <td>
                <select name="id_kota">
                    <?php 
                        $ambil_kota = mysqli_query($koneksi,"SELECT * FROM kota");
                        while ($kota = mysqli_fetch_array($ambil_kota))
                        {
                            $selected = isset($peserta_magang_edit['id_kota']) && $peserta_magang_edit['id_kota'] == $kota['id_kota'] ? 'selected' : '';
                            echo "<option value='{$kota['id_kota']}' $selected>{$kota['kota']}</option>";
                        }
                    ?>
                </select>
                </td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>
                <select name="id_agama">
                    <?php 
                        $ambil_agama = mysqli_query($koneksi,"SELECT * FROM agama");
                        while ($agama = mysqli_fetch_array($ambil_agama))
                        {
                            $selected = isset($peserta_magang_edit['id_agama']) && $peserta_magang_edit['id_agama'] == $agama['id_agama'] ? 'selected' : '';
                            echo "<option value='{$agama['id_agama']}' $selected>{$agama['agama']}</option>";
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
                        <option value="Lulus" <?php echo isset($peserta_magang_edit['keterangan']) && $peserta_magang_edit['keterangan'] == 'Lulus' ? 'selected' : ''; ?>>Lulus</option>
                        <option value="Tidak Lulus" <?php echo isset($peserta_magang_edit['keterangan']) && $peserta_magang_edit['keterangan'] == 'Tidak Lulus' ? 'selected' : ''; ?>>Tidak Lulus</option>
                        <option value="Masih Proses" <?php echo isset($peserta_magang_edit['keterangan']) && $peserta_magang_edit['keterangan'] == 'Masih Proses' ? 'selected' : ''; ?>>Masih Proses</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" name="simpan" value="SIMPAN">
                    <input type="button" value="BATAL" onclick="self.history.back()">
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>