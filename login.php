<?php
session_start();
include 'database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Cek ke database tabel admin
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // Verifikasi password (jika menggunakan teks biasa: $password == $admin['password'])
        if ($admin && $password == $admin['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $admin['username'];
            header("Location: riwayat.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Aplikasi Laporan Fasilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-5" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-danger">Login Petugas</h3>
                            <p class="text-muted small">Masuk untuk mengelola status laporan kerusakan.</p>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 mb-3">Login</button>
                            <div class="text-center">
                                <a href="index.php" class="text-decoration-none small text-muted">← Kembali ke Form Laporan</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>