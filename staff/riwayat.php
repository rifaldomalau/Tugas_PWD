<?php
session_start();
include('../config/koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>
<div class="d-flex">
    <?php $page = 'riwayat'; include('sidebar.php'); ?>
    
    <div class="bg-light p-4 content">
        <h2 class="mb-4">Riwayat Absensi Saya</h2>
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped">
                    <thead><tr><th>Tanggal</th><th>Jam Masuk</th><th>Lokasi</th></tr></thead>
                    <tbody>
                        <?php
                        $id = $_SESSION['user_id'];
                        $q = mysqli_query($koneksi, "SELECT * FROM absensi WHERE user_id='$id' ORDER BY tanggal DESC");
                        while($r = mysqli_fetch_assoc($q)){
                            echo "<tr>
                                <td>".date('d M Y', strtotime($r['tanggal']))."</td>
                                <td><span class='badge bg-info'>".$r['jam_masuk']."</span></td>
                                <td>".$r['lokasi_lat'].", ".$r['lokasi_long']."</td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>