<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

$id_saya = $_SESSION['user_id'];

if(isset($_GET['selesai'])){
    $id_tugas = $_GET['selesai'];
    mysqli_query($koneksi, "UPDATE tugas SET status='Selesai' WHERE id='$id_tugas' AND user_id='$id_saya'");
    echo "<script>alert('Kerja bagus! Tugas selesai.'); window.location='tugas.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tugas Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    
    <?php 
    $page = 'tugas'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        <h2 class="mb-4">Daftar Tugas Harian Saya</h2>

        <div class="row">
            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE user_id='$id_saya' ORDER BY status ASC, deadline ASC");
            
            if(mysqli_num_rows($query) == 0){
                echo "<div class='col-12'><div class='alert alert-info'>Belum ada tugas baru untuk Anda.</div></div>";
            }

            while($row = mysqli_fetch_assoc($query)){
            ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow h-100 <?php echo ($row['status'] == 'Selesai') ? 'border-success' : 'border-warning'; ?>">
                    <div class="card-header d-flex justify-content-between align-items-center <?php echo ($row['status'] == 'Selesai') ? 'bg-success text-white' : 'bg-warning'; ?>">
                        <h5 class="mb-0 fw-bold"><?php echo $row['judul']; ?></h5>
                        <small class="badge bg-light text-dark"><?php echo date('d M Y', strtotime($row['deadline'])); ?></small>
                    </div>
                    <div class="card-body">
                        <p class="card-text fs-5"><?php echo $row['deskripsi']; ?></p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Status: </span>
                            
                            <?php if($row['status'] == 'Belum Selesai'){ ?>
                                <a href="tugas.php?selesai=<?php echo $row['id']; ?>" class="btn btn-primary" onclick="return confirm('Yakin tugas ini sudah beres?')">
                                    ✅ Tandai Selesai
                                </a>
                            <?php } else { ?>
                                <button class="btn btn-success" disabled>TUNTAS 🎉</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

</div>
</body>
</html>