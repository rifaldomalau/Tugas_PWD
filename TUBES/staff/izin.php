<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');
$id_user = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pengajuan Izin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    <?php $page = 'izin'; include('sidebar.php'); ?>

    <div class="bg-light p-4 content">
        <h2 class="mb-4">Pengajuan Izin / Sakit</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">Buat Pengajuan Baru</div>
                    <div class="card-body">
                        <form action="izin-proses.php" method="POST" enctype="multipart/form-data">
                            
                            <div class="mb-3">
                                <label>Tanggal Tidak Hadir</label>
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Jenis Izin</label>
                                <select name="jenis" class="form-select">
                                    <option value="Sakit">Sakit (Lampirkan Surat)</option>
                                    <option value="Izin">Izin Keperluan Lain</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Keterangan / Alasan</label>
                                <textarea name="keterangan" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Bukti Foto (Surat Dokter/Lainnya)</label>
                                <input type="file" name="bukti" class="form-control" accept=".jpg, .jpeg, .png">
                                <small class="text-muted">Opsional. Max 2MB.</small>
                            </div>

                            <button type="submit" name="ajukan" class="btn btn-success w-100">Kirim Pengajuan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white">Riwayat Pengajuan Saya</div>
                    <div class="card-body">
                        <table class="table table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Ket. & Bukti</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($koneksi, "SELECT * FROM pengajuan_izin WHERE user_id='$id_user' ORDER BY created_at DESC");
                                while($row = mysqli_fetch_assoc($query)){
                                ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo $row['jenis']; ?></td>
                                    <td>
                                        <?php echo $row['keterangan']; ?>
                                        <?php if($row['bukti_foto']){ ?>
                                            <br><a href="../assets/img/<?php echo $row['bukti_foto']; ?>" target="_blank" class="badge bg-info text-decoration-none">Lihat Bukti</a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if($row['status'] == 'Pending'){ ?>
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        <?php } elseif($row['status'] == 'Disetujui'){ ?>
                                            <span class="badge bg-success">Disetujui</span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">Ditolak</span>
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
    </div>
</div>
</body>
</html>