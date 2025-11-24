<?php
session_start();
// Cek Login & Role Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

// Data untuk Tabel Absensi Dashboard
// Menggabungkan tabel absensi dan users untuk dapat nama pegawai
$query = "SELECT absensi.*, users.nama_lengkap 
          FROM absensi 
          JOIN users ON absensi.user_id = users.id 
          ORDER BY absensi.tanggal DESC, absensi.jam_masuk DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - E-Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    
    <?php 
    // Variabel ini agar menu Dashboard di sidebar jadi biru (Active)
    $page = 'dashboard'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Absensi</h2>
            <span class="badge bg-primary fs-6">Halo, <?php echo $_SESSION['nama']; ?></span>
        </div>

        <div class="card shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Aktivitas Absensi Terbaru</h5>
                <a href="cetak-laporan.php" target="_blank" class="btn btn-sm btn-success">🖨️ Cetak PDF</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pegawai</th>
                                <th>Jam Masuk</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)) { 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                <td><?php echo $row['nama_lengkap']; ?></td>
                                <td><span class="badge bg-info"><?php echo $row['jam_masuk']; ?></span></td>
                                <td>
                                    <small><?php echo $row['lokasi_lat']; ?>,<br><?php echo $row['lokasi_long']; ?></small>
                                </td>
                                <td>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $row['lokasi_lat'].','.$row['lokasi_long']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Peta</a>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>Belum ada data absensi.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>