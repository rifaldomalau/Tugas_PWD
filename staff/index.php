<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

$user_id = $_SESSION['user_id'];
$hari_ini = date('Y-m-d');

// Cek apakah hari ini sudah absen?
$cek_absen = mysqli_query($koneksi, "SELECT * FROM absensi WHERE user_id = '$user_id' AND tanggal = '$hari_ini'");
$sudah_absen = mysqli_num_rows($cek_absen);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    
    <?php 
    $page = 'dashboard'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        <h2 class="mb-4">Dashboard Pegawai</h2>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        Form Absensi Harian
                    </div>
                    <div class="card-body text-center py-5">
                        <h1 id="jam-digital" class="display-3 fw-bold mb-3">--:--:--</h1>
                        <p class="text-muted fs-5"><?php echo date('l, d F Y'); ?></p>
                        <hr>

                        <?php if ($sudah_absen > 0) { ?>
                            <div class="alert alert-success p-4">
                                <h4>✅ Anda sudah absen hari ini!</h4>
                                <p class="mb-0">Terima kasih, selamat bekerja.</p>
                            </div>
                        <?php } else { ?>
                            
                            <form action="absen-proses.php" method="POST">
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">

                                <div id="lokasi-info" class="mb-3 text-danger fw-bold">
                                    <div class="spinner-border spinner-border-sm text-danger" role="status"></div>
                                    Sedang mendeteksi lokasi...
                                </div>

                                <button type="submit" name="absen_masuk" id="btn-absen" class="btn btn-primary btn-lg w-75 py-3" disabled>
                                    📍 KLIK UNTUK ABSEN MASUK
                                </button>
                            </form>

                        <?php } ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow bg-white mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title text-muted">Status Akun</h5>
                        <h3>Aktif ✅</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    setInterval(() => {
        const now = new Date();
        document.getElementById('jam-digital').innerText = now.toLocaleTimeString();
    }, 1000);

    const lokasiInfo = document.getElementById('lokasi-info');
    const btnAbsen = document.getElementById('btn-absen');
    const inputLat = document.getElementById('latitude');
    const inputLong = document.getElementById('longitude');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        lokasiInfo.innerHTML = "Geolocation tidak didukung browser ini.";
    }

    function showPosition(position) {
        inputLat.value = position.coords.latitude;
        inputLong.value = position.coords.longitude;
        lokasiInfo.innerHTML = `<span class="text-success">📍 Lokasi Terkunci: ${position.coords.latitude}, ${position.coords.longitude}</span>`;
        if(btnAbsen) btnAbsen.disabled = false;
    }

    function showError(error) {
        lokasiInfo.innerHTML = "Gagal mendeteksi lokasi. Pastikan GPS aktif/diizinkan.";
    }
</script>

</body>
</html>