<?php
// Establish database connection
include "../config/koneksi.php";

$proposal_edit = [];
$isEdit = false;

// Check if ID proposal is set and numeric
if(isset($_GET['id_proposal']) && is_numeric($_GET['id_proposal'])) {
    $id_proposal = $_GET['id_proposal'];

    // Fetch proposal data from database
    $query = "SELECT * FROM proposal WHERE id_proposal = $id_proposal";
    $result = mysqli_query($koneksi, $query);

    // Check if proposal exists
    if(mysqli_num_rows($result) > 0) {
        $proposal_edit = mysqli_fetch_assoc($result);
        $isEdit = true;
    } else {
        echo "Proposal not found.";
        exit; // Exit if proposal not found
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Proposal Magang</title>
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
        input[type="text"], input[type="date"], input[type="file"], select {
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
            background-color: #ffffff;
            max-width: 800px;
            margin: 0 auto;
        }
        input::placeholder {
            color: rgba(0, 0, 0, 0.3); /* Transparansi */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h3><?php echo $isEdit ? 'Edit' : 'Tambah'; ?> Data Proposal</h3>
        <form action="proposal_magang_proses.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="status" value="<?php echo $isEdit ? 'edit' : 'tambah'; ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id_proposal" value="<?php echo $proposal_edit['id_proposal']; ?>">
            <?php endif; ?>
            <table>
                <tr>
                    <td>Organisasi</td>
                    <td>:</td>
                    <td>
                        <select name="id_organisasi" id="id_organisasi" required>
                            <option value="">Pilih Organisasi</option>
                            <?php
                                $query_organisasi = mysqli_query($koneksi, "SELECT id_organisasi, nama_organisasi FROM organisasi ORDER BY nama_organisasi");
                                while ($data_organisasi = mysqli_fetch_array($query_organisasi)) {
                                    $selected = ($isEdit && $proposal_edit['id_organisasi'] == $data_organisasi['id_organisasi']) ? 'selected' : '';
                                    echo "<option value='{$data_organisasi['id_organisasi']}' $selected>{$data_organisasi['nama_organisasi']}</option>";
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Nomor Surat</td>
                    <td>:</td>
                    <td><input type="text" name="nomor_surat" value="<?php echo $isEdit ? $proposal_edit['nomor_surat'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>:</td>
                    <td><input type="date" name="tanggal_mulai" value="<?php echo $isEdit ? $proposal_edit['tanggal_mulai'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>:</td>
                    <td><input type="date" name="tanggal_selesai" value="<?php echo $isEdit ? $proposal_edit['tanggal_selesai'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Jumlah Anggota</td>
                    <td>:</td>
                    <td><input type="text" name="jumlah_anggota" value="<?php echo $isEdit ? $proposal_edit['jumlah_anggota'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Tanggal Pengajuan</td>
                    <td>:</td>
                    <td><input type="date" name="tanggal_pengajuan" value="<?php echo $isEdit ? $proposal_edit['tanggal_pengajuan'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Nama Pembimbing</td>
                    <td>:</td>
                    <td><input type="text" name="nama_pembimbing" value="<?php echo $isEdit ? $proposal_edit['nama_pembimbing'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>No Telefon Pembimbing</td>
                    <td>:</td>
                    <td><input type="text" name="no_telefon_pembimbing" value="<?php echo $isEdit ? $proposal_edit['no_telefon_pembimbing'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Email Pembimbing</td>
                    <td>:</td>
                    <td><input type="text" name="email_pembimbing" id="email_pembimbing" value="<?php echo $isEdit ? $proposal_edit['email_pembimbing'] : ''; ?>" placeholder="12345@gmail.com" required></td>
                </tr>
                <tr>
                    <td>Jabatan Pembimbing</td>
                    <td>:</td>
                    <td><input type="text" name="jabatan_pembimbing" value="<?php echo $isEdit ? $proposal_edit['jabatan_pembimbing'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td>Surat Pengajuan</td>
                    <td>:</td>
                    <td>
                        <?php if ($isEdit && $proposal_edit['surat_pengajuan']) : ?>
                            <input type="file" name="surat_pengajuan">
                            <p>File saat ini: <?php echo $proposal_edit['surat_pengajuan']; ?></p>
                        <?php else : ?>
                            <input type="file" name="surat_pengajuan" required>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>
                    <select name="keterangan">
                        <option value="diterima" <?php if(isset($proposal_edit['keterangan']) && $proposal_edit['keterangan'] == 'diterima') echo 'selected'; ?>>Diterima</option>
                        <option value="ditolak" <?php if(isset($proposal_edit['keterangan']) && $proposal_edit['keterangan'] == 'ditolak') echo 'selected'; ?>>Ditolak</option>
                        <option value="selesai" <?php if(isset($proposal_edit['keterangan']) && $proposal_edit['keterangan'] == 'selesai') echo 'selected'; ?>>Selesai</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <input type="submit" value="<?php echo $isEdit ? 'Update' : 'Submit'; ?>">
                        <input type="button" value="Kembali" onclick="window.location.href='index.php?page=proposal_magang_tampil'">
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <script>
        const emailInput = document.getElementById('email_pembimbing');

        emailInput.addEventListener('input', function() {
            const currentValue = emailInput.value;
            const gmailSuffix = '@gmail.com';
            const atPosition = currentValue.indexOf('@');
            const suffixPosition = currentValue.indexOf(gmailSuffix);
            
            // Hanya lakukan autofill jika pengguna belum mengetik domain lengkap
            if (suffixPosition === -1 && currentValue.includes('gmail')) {
                emailInput.value = currentValue.split('@')[0] + gmailSuffix;
            }

            // Tidak mengisi ulang jika pengguna menghapus domain
            if (atPosition === -1) {
                emailInput.value = currentValue;
            }
        });
    </script>
</body>
</html>

<?php
mysqli_close($koneksi);
?>