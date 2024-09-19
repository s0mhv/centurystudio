<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'load') {
        $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
        
        // Query untuk mengambil data kehadiran berdasarkan tanggal
        $query = "SELECT p.id_anggota, p.nama, COALESCE(k.status_kehadiran, '') as status_kehadiran, COALESCE(k.keterangan, '') as keterangan 
                  FROM peserta_magang p 
                  LEFT JOIN kehadiran2 k ON p.id_anggota = k.id_anggota AND DATE(k.tanggal) = '$tanggal' 
                  WHERE p.keterangan = 'Masih proses'
                  ORDER BY p.nama";
        
        $result = mysqli_query($koneksi, $query);
        
        if (!$result) {
            die('Query Error: ' . mysqli_error($koneksi));
        }
        
        $output = '';
        while ($row = mysqli_fetch_array($result)) {
            // Debug output
            error_log(print_r($row, true));
            
            $id_anggota = htmlspecialchars($row['id_anggota']);
            $nama = htmlspecialchars($row['nama']);
            $status_kehadiran = htmlspecialchars($row['status_kehadiran']);
            $keterangan = htmlspecialchars($row['keterangan']);
            
            $checked_hadir = $status_kehadiran == 'Hadir' ? 'checked' : '';
            $checked_tidak_hadir = $status_kehadiran == 'Tidak Hadir' ? 'checked' : '';
            $checked_tidak_ada_keterangan = $status_kehadiran == '' ? 'checked' : '';
            
            $readonly_keterangan = $status_kehadiran == 'Tidak Hadir' ? '' : 'readonly';
            
            $output .= "<tr>
                          <td>$nama</td>
                          <td>
                              <input type='radio' name='status[$id_anggota]' value='Hadir' $checked_hadir onclick='updateKeterangan(this, \"$id_anggota\")'> Hadir
                              <input type='radio' name='status[$id_anggota]' value='Tidak Hadir' $checked_tidak_hadir onclick='updateKeterangan(this, \"$id_anggota\")'> Tidak Hadir
                              <input type='radio' name='status[$id_anggota]' value='' $checked_tidak_ada_keterangan onclick='updateKeterangan(this, \"$id_anggota\")'> Tidak Ada Keterangan
                          </td>
                          <td><input type='text' id='keterangan_$id_anggota' name='keterangan[$id_anggota]' value='$keterangan' $readonly_keterangan></td>
                       </tr>";
        }
        
        echo $output;
    } else {
        if (isset($_POST['status']) && is_array($_POST['status'])) {
            foreach ($_POST['status'] as $id_anggota => $status_kehadiran) {
                $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'][$id_anggota]);
                
                $tanggal = date('Y-m-d');
                $check_query = "SELECT * FROM kehadiran2 WHERE id_anggota = '$id_anggota' AND DATE(tanggal) = '$tanggal'";
                $result = mysqli_query($koneksi, $check_query);
                
                if (!$result) {
                    die('Query Error: ' . mysqli_error($koneksi));
                }
                
                if (mysqli_num_rows($result) > 0) {
                    // Update data jika sudah ada
                    $query = "UPDATE kehadiran2 SET status_kehadiran = '$status_kehadiran', keterangan = '$keterangan' 
                              WHERE id_anggota = '$id_anggota' AND DATE(tanggal) = '$tanggal'";
                } else {
                    // Insert data baru jika belum ada
                    $query = "INSERT INTO kehadiran2 (id_anggota, tanggal, status_kehadiran, keterangan, waktu_masuk, waktu_keluar) 
                              VALUES ('$id_anggota', '$tanggal', '$status_kehadiran', '$keterangan', '08:00:00', '16:00:00')";
                }
                
                if (!mysqli_query($koneksi, $query)) {
                    die('Insert/Update Error: ' . mysqli_error($koneksi));
                }
            }
            echo "Data berhasil disimpan.";
        } else {
            echo "Tidak ada data status yang ditemukan.";
        }
    }
}
?>

<script>
function updateKeterangan(radio, id_anggota) {
    var keteranganField = document.getElementById('keterangan_' + id_anggota);
    
    if (radio.value === 'Hadir') {
        keteranganField.value = 'Hadir';
        keteranganField.readOnly = true;
    } else if (radio.value === 'Tidak Hadir') {
        keteranganField.value = '';
        keteranganField.readOnly = false;
    } else {
        keteranganField.value = 'Tidak Ada Keterangan';
        keteranganField.readOnly = true;
    }
}
</script>
