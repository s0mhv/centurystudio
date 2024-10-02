<?php
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'load') {
        $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
        
        // Query untuk mengambil data kehadiran berdasarkan tanggal
        $query = "SELECT p.id_anggota, p.nama, k.status_kehadiran, k.keterangan 
                  FROM peserta_magang p 
                  LEFT JOIN kehadiran2 k ON p.id_anggota = k.id_anggota AND DATE(k.tanggal) = '$tanggal' 
                  WHERE p.keterangan = 'Masih proses'
                  ORDER BY p.nama";
        
        $result = mysqli_query($koneksi, $query);
        
        $output = '';
        while ($row = mysqli_fetch_array($result)) {
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
                              <input type='radio' name='status[$id_anggota]' value='Hadir' $checked_hadir> Hadir
                              <input type='radio' name='status[$id_anggota]' value='Tidak Hadir' $checked_tidak_hadir> Tidak Hadir
                              <input type='radio' name='status[$id_anggota]' value='' $checked_tidak_ada_keterangan> Tidak Ada Keterangan
                          </td>
                          <td><input type='text' name='keterangan[$id_anggota]' value='$keterangan' $readonly_keterangan></td>
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
                
                if (mysqli_num_rows($result) > 0) {
                    // Update data jika sudah ada
                    $query = "UPDATE kehadiran2 SET status_kehadiran = '$status_kehadiran', keterangan = '$keterangan' 
                              WHERE id_anggota = '$id_anggota' AND DATE(tanggal) = '$tanggal'";
                } else {
                    // Insert data baru jika belum ada
                    $query = "INSERT INTO kehadiran2 (id_anggota, tanggal, status_kehadiran, keterangan) 
                              VALUES ('$id_anggota', '$tanggal', '$status_kehadiran', '$keterangan')";
                }
                
                if (!mysqli_query($koneksi, $query)) {
                    echo "Error: " . mysqli_error($koneksi);
                }
            }
            echo "Data berhasil disimpan.";
        } else {
            echo "Tidak ada data status yang ditemukan.";
        }
    }
}
?>
