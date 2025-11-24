<?php
session_start();
include('../config/koneksi.php');

if (isset($_POST['login'])) {
    
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // 1. Cari user berdasarkan username
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);

        // 2. Cek apakah password cocok (Verify Hash)
        if (password_verify($password, $data['password'])) {

            // 3. CEK STATUS AKTIVASI (Syarat Project)
            if ($data['is_active'] == 0) {
                // Jika belum aktif, tendang balik
                header("Location: login.php?pesan=Akun belum aktif! Silakan cek email.");
                exit;
            }

            // 4. Jika lolos semua, simpan SESSION
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama']     = $data['nama_lengkap'];
            $_SESSION['role']     = $data['role'];
            $_SESSION['status']   = "login";

            // 5. Redirect sesuai Role
            if ($data['role'] == "admin") {
                header("Location: ../admin/index.php");
            } else if ($data['role'] == "staff") {
                header("Location: ../staff/index.php");
            }

        } else {
            header("Location: login.php?pesan=Password salah!");
        }
    } else {
        header("Location: login.php?pesan=Username tidak ditemukan!");
    }
} else {
    header("Location: login.php");
}
?>