<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

$hari_ini = date('Y-m-d');

// ==========================================
// 1. LOGIKA WIDGET RINGKASAN (ANGKA-ANGKA)
// ==========================================

// A. Total Pegawai (Hanya Staff & Aktif)
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM users WHERE role='staff' AND is_active=1");
$total_pegawai = mysqli_fetch_assoc($q_total)['jml'];

// B. Hadir Hari Ini (Jam masuk BUKAN 00:00:00)
$q_hadir = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$hari_ini' AND jam_masuk != '00:00:00'");
$hadir_hari_ini = mysqli_fetch_assoc($q_hadir)['jml'];

// C. Izin/Sakit Hari Ini (Jam masuk ADALAH 00:00:00)
$q_izin = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM absensi WHERE tanggal='$hari_ini' AND jam_masuk = '00:00:00'");
$izin_hari_ini = mysqli_fetch_assoc($q_izin)['jml'];

// D. Belum Absen
$belum_absen = $total_pegawai - ($hadir_hari_ini + $izin_hari_ini);
if($belum_absen < 0) $belum_absen = 0;


// ==========================================
// 2. LOGIKA DATA UNTUK GRAFIK (CHART)
// ==========================================

// --- Bar Chart: Tren 7 Hari Terakhir ---
$labels_harian = [];
$data_harian = [];

// Ambil data kehadiran fisik (tidak termasuk izin)
$query_bar = mysqli_query($koneksi, "SELECT tanggal, COUNT(*) as total 
                                     FROM absensi 
                                     WHERE jam_masuk != '00:00:00' 
                                     GROUP BY tanggal 
                                     ORDER BY tanggal DESC 
                                     LIMIT 7");

while($row = mysqli_fetch_assoc($query_bar)){
    $labels_harian[] = date('d M', strtotime($row['tanggal']));
    $data_harian[] = $row['total'];
}
// Balik urutan array (agar grafik dari kiri(lama) ke kanan(baru))
$labels_harian = array_reverse($labels_harian);
$data_harian = array_reverse($data_harian);

// --- Pie Chart: Tepat Waktu vs Terlambat ---
$batas_jam = '08:00:00';

$q_tepat = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$hari_ini' AND jam_masuk <= '$batas_jam' AND jam_masuk != '00:00:00'");
$jml_tepat = mysqli_num_rows($q_tepat);

$q_telat = mysqli_query($koneksi, "SELECT * FROM absensi WHERE tanggal='$hari_ini' AND jam_masuk > '$batas_jam'");
$jml_telat = mysqli_num_rows($q_telat);


// ==========================================
// 3. TABEL AKTIVITAS TERBARU
// ==========================================
$query_tabel = "SELECT absensi.*, users.nama_lengkap 
                FROM absensi 
                JOIN users ON absensi.user_id = users.id 
                ORDER BY absensi.tanggal DESC, absensi.jam_masuk DESC 
                LIMIT 10";
$result_tabel = mysqli_query($koneksi, $query_tabel);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - E-Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="d-flex">
    
    <?php 
    $page = 'dashboard'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Dashboard Overview</h2>
            <div class="text-end">
                <span class="d-block fw-bold"><?php echo date('l, d F Y'); ?></span>
                <span class="badge bg-primary">Halo, <?php echo $_SESSION['nama']; ?></span>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-primary h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small fw-bold">Total Pegawai</div>
                        <div class="display-6 fw-bold text-primary"><?php echo $total_pegawai; ?></div>
                        <small class="text-muted">Orang terdaftar</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-success h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small fw-bold">Hadir Hari Ini</div>
                        <div class="display-6 fw-bold text-success"><?php echo $hadir_hari_ini; ?></div>
                        <small class="text-success">Absen masuk</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-warning h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small fw-bold">Izin / Sakit</div>
                        <div class="display-6 fw-bold text-warning"><?php echo $izin_hari_ini; ?></div>
                        <small class="text-warning">Disetujui admin</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-danger h-100">
                    <div class="card-body">
                        <div class="text-uppercase text-muted small fw-bold">Belum Absen</div>
                        <div class="display-6 fw-bold text-danger"><?php echo $belum_absen; ?></div>
                        <small class="text-danger">Belum hadir</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card shadow h-100">
                    <div class="card-header bg-white fw-bold">Tren Kehadiran (7 Hari Terakhir)</div>
                    <div class="card-body">
                        <div style="height: 350px; position: relative;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white fw-bold">Statistik Hari Ini</div>
                    <div class="card-body">
                        <div style="height: 350px; position: relative;">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                        <div class="mt-3 text-center small text-muted">
                            Batas jam masuk: 08:00 WIB
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Log Absensi Terbaru</h5>
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
                                <th>Lokasi / Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if(mysqli_num_rows($result_tabel) > 0){
                                while($row = mysqli_fetch_assoc($result_tabel)) { 
                                    // Logika warna badge jam
                                    $jam = $row['jam_masuk'];
                                    if($jam == '00:00:00'){
                                        $badge = 'bg-warning text-dark'; // Izin
                                    } elseif ($jam > $batas_jam) {
                                        $badge = 'bg-danger'; // Telat
                                    } else {
                                        $badge = 'bg-success'; // Tepat
                                    }
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                <td><?php echo $row['nama_lengkap']; ?></td>
                                <td>
                                    <span class="badge <?php echo $badge; ?>">
                                        <?php echo ($jam == '00:00:00') ? 'IZIN/SAKIT' : $jam; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($jam == '00:00:00'){ ?>
                                        <small class="text-muted fst-italic"><?php echo $row['lokasi_long']; ?></small>
                                    <?php } else { ?>
                                        <small><?php echo substr($row['lokasi_lat'], 0, 15); ?>...</small>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if($jam != '00:00:00'){ ?>
                                        <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $row['lokasi_lat'].','.$row['lokasi_long']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">Peta</a>
                                    <?php } else { ?>
                                        <span class="text-muted">-</span>
                                    <?php } ?>
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

<script>
    const chartData = {
        // Data Bar Chart
        labelsBar: <?php echo json_encode($labels_harian); ?>,
        dataBar:   <?php echo json_encode($data_harian); ?>,
        
        // Data Doughnut Chart
        totalTepat: <?php echo $jml_tepat; ?>,
        totalTelat: <?php echo $jml_telat; ?>,
        totalIzin:  <?php echo $izin_hari_ini; ?>
    };
</script>

<script src="js/dashboard.js"></script>

</body>
</html>