<?php
// Masukkan file koneksi
include('../config/koneksi.php');

// Masukkan Library PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Panggil file library yang tadi kamu simpan di folder assets
require '../assets/PHPMailer/Exception.php';
require '../assets/PHPMailer/PHPMailer.php';
require '../assets/PHPMailer/SMTP.php';

// Cek tombol daftar
if (isset($_POST['register'])) {

    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role     = $_POST['role']; 

    // Cek Duplikat
    $cek_data = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email' OR username = '$username'");
    if (mysqli_num_rows($cek_data) > 0) {
        echo "<script>alert('Username/Email sudah terdaftar!'); window.location='register.php';</script>";
        exit;
    }

    // Enkripsi & Token
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $activation_code = bin2hex(random_bytes(16)); 

    // Simpan ke Database
    $query = "INSERT INTO users (username, email, password, nama_lengkap, role, is_active, activation_code) 
              VALUES ('$username', '$email', '$password_hash', '$nama', '$role', 0, '$activation_code')";

    if (mysqli_query($koneksi, $query)) {
        
        // --- PROSES KIRIM EMAIL (PHPMailer) ---
        $mail = new PHPMailer(true);

        try {
            // Setting Server SMTP Google
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // =======================================================
            // EMAIL & APP PASSWORD (JANGAN DIUSIK)
            // =======================================================
            $mail->Username   = 'tubespwd@gmail.com'; 
            $mail->Password   = 'lmih ogym dezc svnu'; // App Password (bukan pass login biasa)
            // =======================================================

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            // Penerima Email
            $mail->setFrom('no-reply@estaff.com', 'Sistem E-Staff');
            $mail->addAddress($email, $nama); // Kirim ke email pendaftar

            // Isi Email
            $mail->isHTML(true);
            $mail->Subject = 'Aktivasi Akun Pegawai E-Staff';
            
            // Link Aktivasi (Sesuaikan port 8000)
            $link = "http://localhost:8000/auth/aktivasi.php?code=" . $activation_code;
            
            $mail->Body    = "
                <h3>Halo $nama,</h3>
                <p>Terima kasih telah mendaftar. Silakan klik link di bawah ini untuk mengaktifkan akun Anda agar bisa Login:</p>
                <p><a href='$link'><b>KLIK DISINI UNTUK AKTIVASI</b></a></p>
                <br><small>Abaikan jika Anda tidak merasa mendaftar.</small>
            ";

            $mail->send();
            
            echo "<script>alert('Registrasi Berhasil! Silakan CEK EMAIL Anda untuk aktivasi.'); window.location='login.php';</script>";

        } catch (Exception $e) {
            echo "Gagal kirim email. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "Error Database: " . mysqli_error($koneksi);
    }
}
?>