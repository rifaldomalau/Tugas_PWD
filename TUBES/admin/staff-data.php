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

// ==========================================
// KONFIGURASI PAGINATION & PENCARIAN
// ==========================================
$batas = 5; // JUMLAH DATA PER HALAMAN
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

// Siapkan Filter Pencarian
$keyword = "";
$where_clause = "WHERE role='staff'"; // Default: Hanya ambil role staff

if(isset($_GET['cari'])){
    $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
    // Tambahkan filter pencarian ke kondisi WHERE
    $where_clause .= " AND (nama_lengkap LIKE '%$keyword%' OR username LIKE '%$keyword%' OR email LIKE '%$keyword%')";
}

// 1. HITUNG TOTAL DATA (Untuk menentukan jumlah halaman)
$data = mysqli_query($koneksi, "SELECT * FROM users $where_clause");
$jumlah_data = mysqli_num_rows($data);
$total_halaman = ceil($jumlah_data / $batas);

// 2. AMBIL DATA SESUAI LIMIT (Untuk ditampilkan di tabel)
$query = mysqli_query($koneksi, "SELECT * FROM users $where_clause ORDER BY created_at DESC LIMIT $halaman_awal, $batas");
$nomor = $halaman_awal + 1; // Agar nomor urut berlanjut di halaman berikutnya
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
                        <h5 class="mb-0">Daftar Pegawai (Total: <?php echo $jumlah_data; ?>)</h5>
                    </div>
                    <div class="col-md-6">
                        <form action="" method="GET">
                            <div class="input-group">
                                <input type="text" name="cari" class="form-control" placeholder="Cari nama/email..." value="<?php echo $keyword; ?>">
                                <button type="submit" class="btn btn-primary">🔍 Cari</button>
                                <?php if($keyword != ""){ ?>
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
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama & Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)){ 
                        ?>
                        <tr>
                            <td><?php echo $nomor++; ?></td>
                            <td>
                                <?php 
                                    $foto = $row['foto_profil'];
                                    if($foto == "" || !file_exists("../assets/img/$foto")){ $foto = "default.png"; }
                                ?>
                                <img src="../assets/img/<?php echo $foto; ?>" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
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
                            <td>
                                <a href="staff-data.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pegawai ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-4 text-muted'>Data tidak ditemukan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        
                        <li class="page-item <?php if($halaman <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php if($halaman > 1){ echo "?halaman=$previous"; if($keyword) echo "&cari=$keyword"; } ?>">Previous</a>
                        </li>

                        <?php for($x = 1; $x <= $total_halaman; $x++){ ?>
                            <li class="page-item <?php if($halaman == $x) echo 'active'; ?>">
                                <a class="page-link" href="?halaman=<?php echo $x; ?><?php if($keyword) echo "&cari=$keyword"; ?>">
                                    <?php echo $x; ?>
                                </a>
                            </li>
                        <?php } ?>

                        <li class="page-item <?php if($halaman >= $total_halaman) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php if($halaman < $total_halaman){ echo "?halaman=$next"; if($keyword) echo "&cari=$keyword"; } ?>">Next</a>
                        </li>

                    </ul>
                </nav>

            </div>
        </div>
    </div>

</div>

</body>
</html>