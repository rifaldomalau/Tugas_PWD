<!DOCTYPE html>
<html lang="id">
<head>
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-warning text-center">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-center">Masukkan email Anda. Kami akan mengirimkan link untuk mereset password.</p>
                        
                        <form action="lupa-password-proses.php" method="POST">
                            <div class="mb-3">
                                <label>Email Terdaftar</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" name="reset" class="btn btn-warning">Kirim Link Reset</button>
                            </div>
                        </form>
                        
                        <div class="mt-3 text-center">
                            <a href="login.php">Batal, kembali login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>