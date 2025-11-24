<?php
session_start();
// Cek Login & Role Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
echo "<h1>Halo Admin " . $_SESSION['nama'] . "! Ini Dashboard Admin.</h1>";
echo "<a href='../auth/logout.php'>Logout</a>";
?>