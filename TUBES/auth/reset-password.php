<?php
include('../config/koneksi.php');

if (!isset($_GET['token'])) {
    die("Token tidak valid.");
}

$token = mysqli_real_escape_string($koneksi, $_GET['token']);

// Cek Token Valid
$cek = mysqli_query($koneksi, "SELECT * FROM users WHERE reset_token='$token'");

if (mysqli_num_rows($cek) == 0) {
    die("Link reset tidak valid atau sudah kadaluarsa.");
}

// PROSES UBAH PASSWORD
if(isset($_POST['ganti'])){
    $pass_baru = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Hash Password Baru
    $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);

    // Update Password & Hapus Token (Supaya link tidak bisa dipakai 2x)
    $update = mysqli_query($koneksi, "UPDATE users SET password='$hash_baru', reset_token=NULL WHERE reset_token='$token'");

    if($update){
        echo "<script>alert('Password berhasil diubah! Silakan Login.'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Buat Password Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4>Password Baru</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Masukkan Password Baru</label>
                                <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="ganti" class="btn btn-success">Simpan Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>