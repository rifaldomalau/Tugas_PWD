<?php
session_start();
// Cek Login & Role Staff
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">E-Staff Panel</a>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">Halo, <?php echo $_SESSION['nama']; ?></span>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        Form Absensi Harian
                    </div>
                    <div class="card-body text-center">
                        <h1 id="jam-digital" class="display-4 fw-bold">--:--:--</h1>
                        <p class="text-muted"><?php echo date('l, d F Y'); ?></p>
                        <hr>

                        <?php if ($sudah_absen > 0) { ?>
                            <div class="alert alert-success">
                                <h5>Anda sudah absen hari ini!</h5>
                                <small>Selamat bekerja.</small>
                            </div>
                        <?php } else { ?>
                            
                            <form action="absen-proses.php" method="POST">
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">

                                <div id="lokasi-info" class="mb-3 text-danger">
                                    <small>Sedang mendeteksi lokasi...</small>
                                </div>

                                <button type="submit" name="absen_masuk" id="btn-absen" class="btn btn-primary btn-lg w-100" disabled>
                                    ABSEN MASUK SEKARANG
                                </button>
                            </form>

                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        Menu Staff
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="profil.php" class="btn btn-outline-dark">Edit Profil & Foto</a>
                            <a href="tugas.php" class="btn btn-outline-dark">Lihat Tugas Harian</a>
                            <a href="riwayat.php" class="btn btn-outline-dark">Riwayat Absensi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script> 
</body>
</html>