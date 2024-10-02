<?php
include "../config/koneksi.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $id_proposal = isset($_POST['id_proposal']) ? $_POST['id_proposal'] : null;
    $id_organisasi = $_POST['id_organisasi'];
    $nomor_surat = $_POST['nomor_surat'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $jumlah_anggota = $_POST['jumlah_anggota'];
    $tanggal_pengajuan = $_POST['tanggal_pengajuan'];
    $nama_pembimbing = $_POST['nama_pembimbing'];
    $no_telefon_pembimbing = $_POST['no_telefon_pembimbing'];
    $email_pembimbing = $_POST['email_pembimbing'];
    $jabatan_pembimbing = $_POST['jabatan_pembimbing'];
    $keterangan = $_POST['keterangan'];

    // Handle file upload
    $surat_pengajuan = $_FILES['surat_pengajuan']['name'];
    $surat_pengajuan_tmp = $_FILES['surat_pengajuan']['tmp_name']; // Tambahan untuk menyimpan file temporary
    $target_dir = "C:/xampp/htdocs/sistem_magang/file/";
    $target_file = $target_dir . basename($_FILES['surat_pengajuan']['name']);

    // Check if file is uploaded
    $isFileUploaded = false;
    if (!empty($surat_pengajuan)) {
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            exit;
        }
        
        // Move uploaded file to target directory
        if (!move_uploaded_file($surat_pengajuan_tmp, $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }

        $isFileUploaded = true;
    }

    // Insert or update proposal data
    if ($status == 'tambah') {
        $query = "INSERT INTO proposal (id_organisasi, nomor_surat, tanggal_mulai, tanggal_selesai, jumlah_anggota, tanggal_pengajuan, nama_pembimbing, no_telefon_pembimbing, email_pembimbing, jabatan_pembimbing, surat_pengajuan, keterangan) 
                  VALUES ('$id_organisasi', '$nomor_surat', '$tanggal_mulai', '$tanggal_selesai', '$jumlah_anggota', '$tanggal_pengajuan', '$nama_pembimbing', '$no_telefon_pembimbing', '$email_pembimbing', '$jabatan_pembimbing', '$surat_pengajuan', '$keterangan')";
    } elseif ($status == 'edit') {
        $query = "UPDATE proposal 
                  SET id_organisasi='$id_organisasi', nomor_surat='$nomor_surat', tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai', jumlah_anggota='$jumlah_anggota', tanggal_pengajuan='$tanggal_pengajuan', nama_pembimbing='$nama_pembimbing', no_telefon_pembimbing='$no_telefon_pembimbing', email_pembimbing='$email_pembimbing', jabatan_pembimbing='$jabatan_pembimbing', keterangan='$keterangan'";

        // Append file update only if a new file is uploaded
        if ($isFileUploaded) {
            $query .= ", surat_pengajuan='$surat_pengajuan'";
        }

        $query .= " WHERE id_proposal='$id_proposal'";  
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['status']) && $_GET['status'] === 'hapus') {
        if ($permissions['delete'] == 1) {
            $id_proposal = $_GET['id_proposal'];
            
            // Debug output
            echo "ID Proposal: " . $id_proposal . "<br>";
            echo "Query DELETE: DELETE FROM proposal WHERE id_proposal = '$id_proposal'<br>";
            
            $query = "DELETE FROM proposal WHERE id_proposal = '$id_proposal'";
            
            if (mysqli_query($koneksi, $query)) {
                echo "Data berhasil dihapus.<br>";
                header("Location: index.php?page=proposal_magang_tampil");
                exit();
            } else {
                echo "Error: " . mysqli_error($koneksi) . "<br>";
            }
        } else {
            echo "You do not have permission to delete data.<br>";
        }
    }    
    }
?>