<style>
    .sidebar { min-height: 100vh; width: 280px; flex-shrink: 0; }
    .nav-link { color: white; margin-bottom: 5px; }
    .nav-link:hover { background-color: #495057; color: #ffc107; border-radius: 5px; }
    .nav-link.active { background-color: #0d6efd; color: white; border-radius: 5px; font-weight: bold; }
</style>

<div class="bg-dark text-white p-3 sidebar d-flex flex-column">
    <h3 class="mb-4 text-center fw-bold">E-Staff Panel</h3>
    <div class="text-center mb-4">
        
        <!-- <small class="text-muted">Halo,</small><br> -->
        <strong><?php echo 'halo, ' . $_SESSION['nama']; ?></strong>
    </div>
    <hr>
    <ul class="nav flex-column mb-auto">
        
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($page == 'dashboard'){ echo 'active'; } ?>">
                ⏱️ Absensi Harian
            </a>
        </li>

        <li class="nav-item">
            <a href="tugas.php" class="nav-link <?php if($page == 'tugas'){ echo 'active'; } ?>">
                📋 Tugas Saya
            </a>
        </li>

        <li class="nav-item">
            <a href="profil.php" class="nav-link <?php if($page == 'profil'){ echo 'active'; } ?>">
                👤 Profil & Foto
            </a>
        </li>

        <li class="nav-item">
            <a href="riwayat.php" class="nav-link <?php if($page == 'riwayat'){ echo 'active'; } ?>">
                📅 Riwayat Absen
            </a>
        </li>

    </ul>
    <hr>
    <div class="dropdown">
        <a href="../auth/logout.php" class="d-flex align-items-center text-white text-decoration-none p-2 bg-danger rounded justify-content-center">
            <strong>🚪 LOGOUT</strong>
        </a>
    </div>
</div>