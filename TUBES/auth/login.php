<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg">
                    <div class="card-header bg-white text-center py-4">
                        <h3 class="mb-0 text-primary fw-bold">Login E-Staff</h3>
                        <small class="text-muted">Masuk untuk mulai bekerja</small>
                    </div>
                    <div class="card-body p-4">
                        
                        <?php if(isset($_GET['pesan'])){ ?>
                            <div class="alert alert-danger text-center">
                                <?php echo htmlspecialchars($_GET['pesan']); ?>
                            </div>
                        <?php } ?>

                        <form action="login-proses.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="login" class="btn btn-primary btn-lg">Masuk Sistem</button>
                            </div>
                            <div class="text-center mt-3 mb-3">
                                <a href="lupa-password.php" class="text-decoration-none">Lupa Password?</a>
                            </div>
                        </form>

                    </div>
                    <div class="card-footer text-center py-3">
                        Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar Pegawai</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>