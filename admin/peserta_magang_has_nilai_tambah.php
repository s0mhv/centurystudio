<!DOCTYPE html>
<html>
<head>
    <title>Form Edit Data Peserta Magang Has Nilai</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
            border: 1px solid #ddd;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #228B22;
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
        input[type="submit"], input[type="reset"] {
            padding: 10px 20px;
            margin: 10px 5px;
        }
        .form-container {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            width: 50%;
            margin: 20px auto;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>

<?php
include "../config/koneksi.php";

// Mengecek apakah ada parameter id_has_nilai yang diterima dari URL
if (isset($_GET['id_has_nilai'])) {
    $id_has_nilai = $_GET['id_has_nilai'];

    // Mengambil data dari database berdasarkan id_has_nilai
    $query = "SELECT * FROM peserta_magang_has_kategori_nilai WHERE id_has_nilai='$id_has_nilai'";
    $result = mysqli_query($koneksi, $query);

    // Memeriksa apakah query berhasil dieksekusi
    if ($result) {
        $data_edit = mysqli_fetch_array($result);
    } else {
        die("Query error: " . mysqli_error($koneksi));
    }
}
?>

<div class="form-container">
    <form action="peserta_magang_has_nilai_proses.php" method="post">
        <?php
        // Menentukan nilai dari input hidden status
        if (isset($_GET['id_has_nilai'])) {
            echo "<input type='hidden' name='status' value='edit'>";
            echo "<input type='hidden' name='id_has_nilai' value='$id_has_nilai'>";
        } else {
            echo "<input type='hidden' name='status' value='create'>";
        }
        ?>
        <table>
            <tr>
                <td colspan="3" align="center">
                    <h3><?php echo isset($_GET['id_has_nilai']) ? 'EDIT DATA' : 'TAMBAH DATA'; ?></h3>
                </td>
            </tr>
            <tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>
                    <select id="nama-select" name="id_anggota">
                        <?php 
                        $ambil_peserta_magang = mysqli_query($koneksi, "SELECT * FROM peserta_magang WHERE keterangan = 'Masih proses' ORDER BY nama ASC");
                        while ($peserta_magang = mysqli_fetch_array($ambil_peserta_magang)) {
                            $selected = isset($data_edit['id_anggota']) && $data_edit['id_anggota'] == $peserta_magang['id_anggota'] ? 'selected' : '';
                            echo "<option value='" . $peserta_magang['id_anggota'] . "' $selected>" . $peserta_magang['nama'] . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
    <td>Kategori Nilai</td>
    <td>:</td>
    <td>
        <select id="nama-select" name="id_kategori_nilai">
            <?php 
            $ambil_kategori_nilai = mysqli_query($koneksi, "SELECT kategori_nilai.id_kategori_nilai, kategori_nilai.kategori_nilai, organisasi.nama_organisasi 
                FROM kategori_nilai
                JOIN proposal_has_kategori_nilai ON kategori_nilai.id_kategori_nilai = proposal_has_kategori_nilai.id_kategori_nilai
                JOIN proposal ON proposal_has_kategori_nilai.id_proposal = proposal.id_proposal
                JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi
            ");
            if (!$ambil_kategori_nilai) {
                die("Query error: " . mysqli_error($koneksi));
            }
            while ($kategori_nilai = mysqli_fetch_array($ambil_kategori_nilai)) {
                echo "<option value='" . $kategori_nilai['id_kategori_nilai'] . "'>" . $kategori_nilai['kategori_nilai'] . " - " . $kategori_nilai['nama_organisasi'] . "</option>";
            }
            ?>
        </select>
    </td>
</tr>
            <tr>
                <td>Nilai</td>
                <td>:</td>
                <td><input type="text" name="nilai" value="<?php echo isset($data_edit['nilai']) ? $data_edit['nilai'] : ''; ?>" required></td>
            </tr>
            <tr>
                <td>Nilai Kualitatif</td>
                <td>:</td>
                <td>
                    <select name="nilai_kualitatif" required>
                        <option value="kurang" <?php if(isset($data_edit['nilai_kualitatif']) && $data_edit['nilai_kualitatif'] == 'kurang') echo 'selected'; ?>>kurang</option>
                        <option value="cukup" <?php if(isset($data_edit['nilai_kualitatif']) && $data_edit['nilai_kualitatif'] == 'cukup') echo 'selected'; ?>>cukup</option>
                        <option value="baik" <?php if(isset($data_edit['nilai_kualitatif']) && $data_edit['nilai_kualitatif'] == 'baik') echo 'selected'; ?>>baik</option>
                        <option value="sangat baik" <?php if(isset($data_edit['nilai_kualitatif']) && $data_edit['nilai_kualitatif'] == 'sangat baik') echo 'selected'; ?>>sangat baik</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <input type="submit" name="simpan" value="SIMPAN">
                    <input type="reset" value="Batal"  onclick="window.location.href='index.php?page=peserta_magang_has_nilai_tampil';">
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
