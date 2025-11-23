<?php

$server="localhost";
$username="root";
$password="";
$database="store";

// php5
// mysql_connect($server, $username, $password) or die("gagal terkoneksi");
// mysql_select_db($database) or die ("gagal terhubung database");

// php7+
$koneksi = mysqli_connect($server, $username, $password, $database);

if (!$koneksi) {
    die("Gagal  terhubung database: " . mysqli_connect_error());
}

?>