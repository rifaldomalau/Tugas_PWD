<?php
include('../config/koneksi.php');

if (isset($_GET['code'])) {
    $code = mysqli_real_escape_string($koneksi, $_GET['code']);

    // Cek apakah kode ada di database
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE activation_code = '$code'");

    if (mysqli_num_rows($cek) > 0) {
        // Aktifkan User
        $update = mysqli_query($koneksi, "UPDATE users SET is_active = 1, activation_code = NULL WHERE activation_code = '$code'");
        
        if ($update) {
            echo "<script>
                    alert('Selamat! Akun Anda sudah AKTIF. Silakan Login.');
                    window.location='login.php';
                  </script>";
        } else {
            echo "Gagal mengaktifkan akun.";
        }
    } else {
        echo "<h3 style='color:red; text-align:center; margin-top:50px;'>Link Aktivasi Tidak Valid atau Sudah Kadaluarsa!</h3>";
    }
} else {
    echo "Tidak ada kode aktivasi.";
}
?>