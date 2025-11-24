<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

// PROSES PERSETUJUAN (Approve/Reject)
if(isset($_GET['aksi']) && isset($_GET['id'])){
    $aksi = $_GET['aksi']; // 'terima' atau 'tolak'
    $id_izin = $_GET['id'];

    // Ambil data izin dulu untuk dipindahkan ke absensi
    $data_izin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pengajuan_izin WHERE id='$id_izin'"));
    $user_id = $data_izin['user_id'];
    $tanggal = $data_izin['tanggal'];
    $alasan  = $data_izin['jenis'] . ": " . $data_izin['keterangan'];

    if($aksi == 'terima'){
        // 1. Update status jadi Disetujui
        mysqli_query($koneksi, "UPDATE pengajuan_izin SET status='Disetujui' WHERE id='$id_izin'");
        
        // 2. OTOMATIS INPUT KE TABEL ABSENSI (Supaya muncul di Laporan PDF)
        // Kita set jam_masuk jadi 00:00:00 sebagai tanda dia tidak hadir fisik tapi berizin
        mysqli_query($koneksi, "INSERT INTO absensi (user_id, tanggal, jam_masuk, lokasi_lat, lokasi_long) 
                                VALUES ('$user_id', '$tanggal', '00:00:00', '-', '$alasan')");

        echo "<script>alert('Pengajuan DISETUJUI! Data otomatis masuk ke absensi.'); window.location='persetujuan.php';</script>";
    } 
    elseif($aksi == 'tolak'){
        mysqli_query($koneksi, "UPDATE pengajuan_izin SET status='Ditolak' WHERE id='$id_izin'");
        echo "<script>alert('Pengajuan DITOLAK.'); window.location='persetujuan.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Persetujuan Izin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    <?php $page = 'persetujuan'; include('sidebar.php'); ?>

    <div class="bg-light p-4 content">
        <h2 class="mb-4">Persetujuan Izin Pegawai</h2>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Pegawai</th>
                            <th>Tanggal Izin</th>
                            <th>Alasan & Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Tampilkan hanya yang Pending atau baru saja diproses hari ini
                        $query = "SELECT pengajuan_izin.*, users.nama_lengkap 
                                  FROM pengajuan_izin 
                                  JOIN users ON pengajuan_izin.user_id = users.id 
                                  ORDER BY CASE WHEN status = 'Pending' THEN 1 ELSE 2 END, created_at DESC";
                        
                        $result = mysqli_query($koneksi, $query);

                        while($row = mysqli_fetch_assoc($result)){
                        ?>
                        <tr class="<?php echo ($row['status']=='Pending') ? 'table-warning' : ''; ?>">
                            <td><?php echo $row['nama_lengkap']; ?></td>
                            <td><?php echo date('d M Y', strtotime($row['tanggal'])); ?></td>
                            <td>
                                <strong><?php echo $row['jenis']; ?></strong><br>
                                <small><?php echo $row['keterangan']; ?></small>
                                <?php if($row['bukti_foto']){ ?>
                                    <br><a href="../assets/img/<?php echo $row['bukti_foto']; ?>" target="_blank" class="btn btn-sm btn-outline-info mt-1">Lihat Bukti</a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'Pending'){ ?>
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                <?php } elseif($row['status'] == 'Disetujui'){ ?>
                                    <span class="badge bg-success">Disetujui</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($row['status'] == 'Pending'){ ?>
                                    <a href="persetujuan.php?aksi=terima&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui izin ini?')">✅ Terima</a>
                                    <a href="persetujuan.php?aksi=tolak&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak izin ini?')">❌ Tolak</a>
                                <?php } else { ?>
                                    <small class="text-muted">Sudah diproses</small>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>