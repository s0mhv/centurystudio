<?php
include "../config/koneksi.php";

if (isset($_POST['id_proposal'])) {
    $id_proposal = $_POST['id_proposal'];

    $query = "SELECT organisasi.id_organisasi, organisasi.nama_organisasi 
              FROM proposal 
              JOIN organisasi ON proposal.id_organisasi = organisasi.id_organisasi 
              WHERE proposal.id_proposal = '$id_proposal'";
    
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo "<option value='{$row['id_organisasi']}'>{$row['nama_organisasi']}</option>";
        }
    } else {
        echo "<option value=''>Organisasi tidak ditemukan</option>";
    }
}
?>
