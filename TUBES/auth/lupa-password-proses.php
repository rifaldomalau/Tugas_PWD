<?php
include('../config/koneksi.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

if (isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    // Cek apakah email ada?
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_assoc($cek);
        $nama = $data['nama_lengkap'];
        
        // Buat Token Unik
        $token = bin2hex(random_bytes(32));

        // Simpan token ke database
        mysqli_query($koneksi, "UPDATE users SET reset_token='$token' WHERE email='$email'");

        // --- KIRIM EMAIL ---
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            // ======================================
            $mail->Username   = 'tubespwd@gmail.com';
            $mail->Password   = 'lmih ogym dezc svnu';
            // ======================================
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('no-reply@estaff.com', 'Admin E-Staff');
            $mail->addAddress($email, $nama);

            $mail->isHTML(true);
            $mail->Subject = 'Permintaan Reset Password';
            
            $link = "http://localhost:8000/auth/reset-password.php?token=" . $token;

            $mail->Body    = "
                <h3>Halo $nama,</h3>
                <p>Kami menerima permintaan untuk mereset password akun Anda.</p>
                <p>Silakan klik link di bawah ini untuk membuat password baru:</p>
                <p><a href='$link' style='background:red; color:white; padding:10px; text-decoration:none; border-radius:5px;'>RESET PASSWORD SEKARANG</a></p>
                <br><small>Jika Anda tidak meminta ini, abaikan saja.</small>
            ";

            $mail->send();
            echo "<script>alert('Link reset telah dikirim ke email Anda!'); window.location='login.php';</script>";

        } catch (Exception $e) {
            echo "Gagal kirim email. Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "<script>alert('Email tidak ditemukan di sistem!'); window.location='lupa-password.php';</script>";
    }
}
?>