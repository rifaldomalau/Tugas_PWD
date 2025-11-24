<?php
session_start();
include('../config/koneksi.php');

if(isset($_POST['ajukan'])){
    $user_id = $_SESSION['user_id'];
    $tanggal = $_POST['tanggal'];
    $jenis   = $_POST['jenis'];
    $ket     = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    
    // Upload Bukti (Mirip upload profil)
    $nama_file = $_FILES['bukti']['name'];
    $tmp_name  = $_FILES['bukti']['tmp_name'];
    $nama_file_baru = "";

    if($nama_file != ""){
        $nama_file_baru = time() . '_' . $nama_file;
        move_uploaded_file($tmp_name, '../assets/img/' . $nama_file_baru);
    }

    $query = "INSERT INTO pengajuan_izin (user_id, tanggal, jenis, keterangan, bukti_foto) 
              VALUES ('$user_id', '$tanggal', '$jenis', '$ket', '$nama_file_baru')";
    
    if(mysqli_query($koneksi, $query)){
        echo "<script>alert('Pengajuan berhasil dikirim! Tunggu persetujuan Admin.'); window.location='izin.php';</script>";
    }
}
?>