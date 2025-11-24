<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Daftar Akun Pegawai</h4>
                    </div>
                    <div class="card-body">
                        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses') { ?>
                            <div class="alert alert-success">Registrasi berhasil! Cek email untuk aktivasi.</div>
                        <?php } ?>

                        <form action="register-proses.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required placeholder="Contoh: Budi Santoso">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required placeholder="Username untuk login">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" name="email" class="form-control" required placeholder="email@contoh.com">
                                <small class="text-muted">Link aktivasi akan dikirim ke sini.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <input type="hidden" name="role" value="staff">

                            <div class="d-grid gap-2">
                                <button type="submit" name="register" class="btn btn-primary">Daftar Sekarang</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Sudah punya akun? <a href="login.php">Login disini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>