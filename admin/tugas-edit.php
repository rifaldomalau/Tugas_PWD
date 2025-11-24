<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

// Ambil ID Tugas dari URL
$id_tugas = $_GET['id'];

// Ambil Data Tugas yang mau diedit
$query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id='$id_tugas'");
$data = mysqli_fetch_assoc($query);

// PROSES SIMPAN EDIT
if(isset($_POST['update_tugas'])){
    $judul     = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $deadline  = $_POST['deadline'];
    $status    = $_POST['status'];
    // Admin juga bisa memindahkan tugas ke orang lain
    $user_id   = $_POST['user_id']; 

    $update = mysqli_query($koneksi, "UPDATE tugas SET 
        user_id='$user_id',
        judul='$judul', 
        deskripsi='$deskripsi', 
        deadline='$deadline',
        status='$status'
        WHERE id='$id_tugas'");
    
    if($update){
        echo "<script>alert('Tugas berhasil diupdate!'); window.location='tugas.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        Edit Data Tugas
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            
                            <div class="mb-3">
                                <label>Tugas Untuk Siapa?</label>
                                <select name="user_id" class="form-select" required>
                                    <?php 
                                    $staff_query = mysqli_query($koneksi, "SELECT * FROM users WHERE role='staff' AND is_active=1");
                                    while($staff = mysqli_fetch_assoc($staff_query)){
                                        $selected = ($staff['id'] == $data['user_id']) ? 'selected' : '';
                                        echo "<option value='".$staff['id']."' $selected>".$staff['nama_lengkap']."</option>";
                                    } 
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Judul Tugas</label>
                                <input type="text" name="judul" class="form-control" value="<?php echo $data['judul']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3"><?php echo $data['deskripsi']; ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Deadline</label>
                                <input type="date" name="deadline" class="form-control" value="<?php echo $data['deadline']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Belum Selesai" <?php if($data['status']=='Belum Selesai') echo 'selected'; ?>>Belum Selesai</option>
                                    <option value="Selesai" <?php if($data['status']=='Selesai') echo 'selected'; ?>>Selesai</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="tugas.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" name="update_tugas" class="btn btn-primary">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>