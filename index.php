<?php
// Mulai session untuk flash message
session_start();
include 'database.php';

// Proses Simpan Laporan Baru (Create)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_laporan'])) {
    $nama_pelapor   = trim($_POST['nama_pelapor']);
    $nama_fasilitas = trim($_POST['nama_fasilitas']);
    $lokasi         = trim($_POST['lokasi']);
    $id_kategori    = $_POST['id_kategori'];
    $deskripsi      = trim($_POST['deskripsi']);
    $prioritas      = $_POST['prioritas'];
    
    if (!empty($nama_pelapor) && !empty($nama_fasilitas)) {
        $stmt = $pdo->prepare("INSERT INTO laporan_kerusakan (nama_pelapor, nama_fasilitas, lokasi, id_kategori, deskripsi, prioritas, status, tanggal_laporan) VALUES (?, ?, ?, ?, ?, ?, 'Menunggu', NOW())");
        
        if ($stmt->execute([$nama_pelapor, $nama_fasilitas, $lokasi, $id_kategori, $deskripsi, $prioritas])) {
            $_SESSION['pesan_sukses'] = "Selamat, laporan berhasil terkirim!";
        }
        
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pelaporan Kerusakan Fasilitas Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold text-danger" href="index.php">Aplikasi Laporan Fasilitas</a>
                <!-- Tombol Navigasi ke Halaman Daftar Laporan -->
                <a href="riwayat.php" class="btn btn-outline-danger btn-sm fw-semibold">📋 Lihat Daftar Laporan</a>
            </div>
        </nav>
    </header>
    
    <main class="container mb-5">
        <!-- Notifikasi Pesan Sukses -->
        <?php if (isset($_SESSION['pesan_sukses'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> <?= $_SESSION['pesan_sukses']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['pesan_sukses']); ?>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <section id="form-laporan" class="bg-white p-4 border rounded shadow-sm">
                    <h2 class="h5 mb-3 fw-bold">Form Pelaporan Kerusakan</h2>
                    <p class="text-muted small">Lengkapi data di bawah ini untuk melaporkan fasilitas sekolah yang rusak.</p>

                    <form action="" method="post">
                        <fieldset class="border-0 p-0 m-0">
                            <input type="hidden" name="tambah_laporan" value="1">
                            
                            <div class="mb-3">
                                <label for="nama_pelapor" class="form-label">Nama Pelapor</label>
                                <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                                <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="lokasi" class="form-label">Lokasi Kerusakan</label>
                                <input type="text" id="lokasi" name="lokasi" maxlength="100" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="id_kategori" class="form-label">Kategori</label>
                                <select id="id_kategori" name="id_kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="1">Elektronik</option>
                                    <option value="2">Furnitur</option>
                                    <option value="3">Jaringan</option>
                                    <option value="4">Peralatan Kelas</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="prioritas" class="form-label">Prioritas</label>
                                <select id="prioritas" name="prioritas" class="form-select" required>
                                    <option value="">-- Pilih Prioritas --</option>
                                    <option value="Rendah">Rendah</option>
                                    <option value="Sedang">Sedang</option>
                                    <option value="Tinggi">Tinggi</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-danger w-100">Kirim Laporan</button>
                        </fieldset>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <footer class="text-center py-3 text-muted border-top bg-white">
        <p class="mb-0 small">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>