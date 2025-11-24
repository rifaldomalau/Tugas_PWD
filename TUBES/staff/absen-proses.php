<?php
session_start();
include('../config/koneksi.php');

if (isset($_POST['absen_masuk'])) {
    
    $user_id = $_SESSION['user_id'];
    $lat = $_POST['latitude'];
    $long = $_POST['longitude'];
    $tanggal = date('Y-m-d');
    $jam_masuk = date('H:i:s');

    // Validasi sederhana: Pastikan koordinat tidak kosong
    if (empty($lat) || empty($long)) {
        echo "<script>alert('Lokasi tidak terdeteksi! Pastikan GPS aktif.'); window.location='index.php';</script>";
        exit;
    }

    // Simpan ke Database
    $query = "INSERT INTO absensi (user_id, tanggal, jam_masuk, lokasi_lat, lokasi_long) 
              VALUES ('$user_id', '$tanggal', '$jam_masuk', '$lat', '$long')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Absen Masuk Berhasil!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }

} else {
    header("Location: index.php");
}
?>