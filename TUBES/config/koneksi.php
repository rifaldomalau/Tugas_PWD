<?php
$server   = "localhost:3307"; 
$username = "root";           
$password = "";
$database = "db_pegawai";    

$koneksi = mysqli_connect($server, $username, $password, $database);

if (!$koneksi) {
    die("Gagal terkoneksi: " . mysqli_connect_error());
}
?>