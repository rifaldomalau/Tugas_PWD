<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'staff') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

$id = $_SESSION['user_id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    
    <?php 
    $page = 'profil'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        <h2 class="mb-4">Edit Profil Saya</h2>

        <div class="card shadow">
            <div class="card-body">
                
                <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses') { ?>
                    <div class="alert alert-success">Profil berhasil diperbarui!</div>
                <?php } elseif(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
                    <div class="alert alert-danger">Gagal update profil. Cek tipe file gambar.</div>
                <?php } ?>

                <form action="profil-proses.php" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-3 text-center">
                            <?php 
                                $foto = $data['foto_profil'];
                                if($foto == "" || !file_exists("../assets/img/$foto")){ $foto = "default.png"; }
                            ?>
                            <img src="../assets/img/<?php echo $foto; ?>" class="rounded-circle img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ganti Foto Profil</label>
                                <input type="file" name="foto" class="form-control" accept=".jpg, .jpeg, .png">
                                <small class="text-muted">Format: JPG, JPEG, PNG (Max 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo $data['nama_lengkap']; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $data['username']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control bg-light" value="<?php echo $data['email']; ?>" readonly>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" name="update" class="btn btn-primary btn-lg">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
</body>
</html>