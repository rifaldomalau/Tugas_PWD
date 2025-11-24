<style>
    .sidebar { min-height: 100vh; width: 280px; flex-shrink: 0; }
    .nav-link { color: white; margin-bottom: 5px; }
    .nav-link:hover { background-color: #495057; color: #ffc107; border-radius: 5px; }
    /* Class Active Dinamis */
    .nav-link.active { background-color: #0d6efd; color: white; border-radius: 5px; font-weight: bold; }
</style>

<div class="bg-dark text-white p-3 sidebar d-flex flex-column">
    <h3 class="mb-4 text-center fw-bold">E-Staff Admin</h3>
    <hr>
    <ul class="nav flex-column mb-auto">
        
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($page == 'dashboard'){ echo 'active'; } ?>">
                📊 Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="tugas.php" class="nav-link <?php if($page == 'tugas'){ echo 'active'; } ?>">
                📋 Kelola Tugas
            </a>
        </li>

        <li class="nav-item">
            <a href="staff-data.php" class="nav-link <?php if($page == 'staff'){ echo 'active'; } ?>">
                👥 Data Pegawai
            </a>
        </li>

        <li class="nav-item">
            <a href="persetujuan.php" class="nav-link <?php if($page == 'persetujuan'){ echo 'active'; } ?>">
                ✅ Persetujuan Izin
            </a>
        </li>

        <li class="nav-item">
            <a href="cetak-laporan.php" target="_blank" class="nav-link">
                🖨️ Cetak Laporan
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