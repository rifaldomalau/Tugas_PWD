<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

// Fitur Hapus Pegawai
if(isset($_GET['hapus'])){
    $id_staff = $_GET['hapus'];
    $hapus = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id_staff'");
    if($hapus){
        echo "<script>alert('Pegawai berhasil dihapus!'); window.location='staff-data.php';</script>";
    }
}

// LOGIKA PENCARIAN
$keyword = ""; // Variabel kosong untuk menampung kata kunci
if(isset($_GET['cari'])){
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
    // Cari berdasarkan Nama, Username, atau Email
    // PENTING: Tetap tambahkan role='staff' agar admin tidak ikut muncul
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE role='staff' AND (nama_lengkap LIKE '%$keyword%' OR username LIKE '%$keyword%' OR email LIKE '%$keyword%') ORDER BY created_at DESC");
} else {
    // Jika tidak mencari, tampilkan semua staff
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE role='staff' ORDER BY created_at DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pegawai - E-Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    
    <?php 
    $page = 'staff'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        <h2 class="mb-4">Manajemen Data Pegawai</h2>

        <div class="card shadow">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Daftar Pegawai Terdaftar</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="GET">
                            <div class="input-group">
                                <input type="text" name="cari" class="form-control" placeholder="Cari nama, username, atau email..." value="<?php echo $keyword; ?>" required>
                                <button type="submit" class="btn btn-primary">🔍 Cari</button>
                                <?php if(isset($_GET['cari'])){ ?>
                                    <a href="staff-data.php" class="btn btn-secondary">Reset</a>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Foto</th>
                            <th>Nama & Username</th>
                            <th>Email</th>
                            <th>Status Akun</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)){ 
                        ?>
                        <tr>
                            <td>
                                <?php 
                                    $foto = $row['foto_profil'];
                                    if($foto == "" || !file_exists("../assets/img/$foto")){ $foto = "default.png"; }
                                ?>
                                <img src="../assets/img/<?php echo $foto; ?>" class="rounded-circle border" width="50" height="50" style="object-fit: cover;">
                            </td>
                            <td>
                                <strong><?php echo $row['nama_lengkap']; ?></strong><br>
                                <small class="text-muted">@<?php echo $row['username']; ?></small>
                            </td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <?php if($row['is_active'] == 1){ ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Belum Aktivasi</span>
                                <?php } ?>
                            </td>
                            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="staff-data.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pegawai ini? Semua data tugas & absensinya akan hilang permanen!')">Pecat</a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            // Pesan jika pencarian tidak ditemukan
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>
                                    <h5>Data tidak ditemukan.</h5>
                                    <p>Coba kata kunci lain.</p>
                                  </td></tr>";
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