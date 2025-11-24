<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
include('../config/koneksi.php');

// PROSES TAMBAH TUGAS (Create)
if(isset($_POST['simpan_tugas'])){
    $user_id = $_POST['user_id']; 
    $judul   = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $deadline = $_POST['deadline'];

    // LOGIKA: Jika admin memilih "Semua Staff" (value="all")
    if($user_id == 'all'){
        $ambil_staff = mysqli_query($koneksi, "SELECT id FROM users WHERE role='staff' AND is_active=1");
        while($staff = mysqli_fetch_assoc($ambil_staff)){
            $id_staff = $staff['id'];
            mysqli_query($koneksi, "INSERT INTO tugas (user_id, judul, deskripsi, deadline) VALUES ('$id_staff', '$judul', '$deskripsi', '$deadline')");
        }
        echo "<script>alert('Berhasil! Tugas dikirim ke SEMUA STAFF.'); window.location='tugas.php';</script>";
    } 
    // LOGIKA: Jika admin memilih 1 orang saja
    else {
        $simpan = mysqli_query($koneksi, "INSERT INTO tugas (user_id, judul, deskripsi, deadline) VALUES ('$user_id', '$judul', '$deskripsi', '$deadline')");
        if($simpan){
            echo "<script>alert('Tugas berhasil diberikan!'); window.location='tugas.php';</script>";
        }
    }
}

// PROSES HAPUS TUGAS (Delete)
if(isset($_GET['hapus'])){
    $id_tugas = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tugas WHERE id='$id_tugas'");
    echo "<script>alert('Tugas dihapus!'); window.location='tugas.php';</script>";
}

// Ambil daftar Staff Aktif untuk dropdown
$data_staff = mysqli_query($koneksi, "SELECT * FROM users WHERE role='staff' AND is_active=1");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Tugas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> .content { width: 100%; } </style>
</head>
<body>

<div class="d-flex">
    
    <?php 
    // Variabel ini agar menu Kelola Tugas di sidebar jadi biru (Active)
    $page = 'tugas'; 
    include('sidebar.php'); 
    ?>

    <div class="bg-light p-4 content">
        <div class="d-flex justify-content-between mb-4">
            <h3>Manajemen Tugas Pegawai</h3>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">Buat Tugas Baru</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label>Pilih Pegawai</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- Pilih Staff --</option>
                                    <option value="all" class="fw-bold text-primary">📢 KIRIM KE SEMUA STAFF</option>
                                    <option disabled>----------------</option>
                                    
                                    <?php while($staff = mysqli_fetch_assoc($data_staff)){ ?>
                                        <option value="<?php echo $staff['id']; ?>">
                                            <?php echo $staff['nama_lengkap']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Judul Tugas</label>
                                <input type="text" name="judul" class="form-control" required placeholder="Contoh: Rapat Evaluasi">
                            </div>
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Detail tugas..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Deadline</label>
                                <input type="date" name="deadline" class="form-control" required>
                            </div>
                            <button type="submit" name="simpan_tugas" class="btn btn-success w-100">Kirim Tugas</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white">Daftar Semua Tugas</div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Pegawai</th>
                                    <th>Tugas</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Join tabel tugas & users agar muncul nama pegawai
                                $query = "SELECT tugas.*, users.nama_lengkap 
                                          FROM tugas 
                                          JOIN users ON tugas.user_id = users.id 
                                          ORDER BY tugas.id DESC";
                                $result = mysqli_query($koneksi, $query);

                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)){
                                ?>
                                <tr>
                                    <td><?php echo $row['nama_lengkap']; ?></td>
                                    <td>
                                        <strong><?php echo $row['judul']; ?></strong><br>
                                        <small class="text-muted">Deadline: <?php echo date('d/m/Y', strtotime($row['deadline'])); ?></small>
                                    </td>
                                    <td>
                                        <?php if($row['status'] == 'Selesai'){ ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="tugas-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="tugas.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>Belum ada tugas.</td></tr>";
                                }
                                ?>
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