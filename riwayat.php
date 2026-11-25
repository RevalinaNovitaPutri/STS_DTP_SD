<?php
// Mulai session untuk flash message
session_start();
include 'database.php';

// Proses Simpan Feedback (Create Feedback)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_feedback'])) {
    $id_laporan = $_POST['id_laporan'];
    $rating     = $_POST['rating'];
    $komentar   = trim($_POST['komentar']);

    if (!empty($id_laporan) && !empty($rating)) {
        $stmt = $pdo->prepare("INSERT INTO feedback (id_laporan, rating, komentar, tanggal_feedback) VALUES (?, ?, ?, NOW())");
        if ($stmt->execute([$id_laporan, $rating, $komentar])) {
            $_SESSION['pesan_sukses'] = "Terima kasih, ulasan/feedback berhasil dikirim!";
        }
        header("Location: riwayat.php");
        exit();
    }
}

// Proses Update Status (Update)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id_laporan  = $_GET['id'];
    $status_baru = $_GET['status'];

    $allowed_status = ['Menunggu', 'Diproses', 'Selesai'];
    if (in_array($status_baru, $allowed_status)) {
        $stmt = $pdo->prepare("UPDATE laporan_kerusakan SET status = ? WHERE id_laporan = ?");
        if ($stmt->execute([$status_baru, $id_laporan])) {
            $_SESSION['pesan_sukses'] = "Status laporan berhasil diubah menjadi " . $status_baru . "!";
        }
    }
    header("Location: riwayat.php");
    exit();
}
 
// Ambil data dengan SQL JOIN (Menghubungkan 3 tabel: laporan, kategori, feedback)
$stmt_tampil = $pdo->query("
    SELECT laporan_kerusakan.*, kategori_fasilitas.nama_kategori, feedback.rating, feedback.komentar 
    FROM laporan_kerusakan 
    INNER JOIN kategori_fasilitas ON laporan_kerusakan.id_kategori = kategori_fasilitas.id_kategori
    LEFT JOIN feedback ON laporan_kerusakan.id_laporan = feedback.id_laporan
");
$laporan_list = $stmt_tampil->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan Kerusakan Fasilitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
            <div class="container d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold text-danger" href="index.php">Aplikasi Laporan Fasilitas</a>
                
                <!-- Letakkan di sini -->
                <div class="d-flex gap-2">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm fw-semibold">➕ Buat Laporan Baru</a>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm fw-semibold">Logout</a>
                </div>
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

        <section id="daftar-laporan" class="bg-white p-4 border rounded shadow-sm">
            <h2 class="h5 mb-3 fw-bold">Daftar Laporan Kerusakan Sekolah</h2>
            <p class="text-muted small">Seluruh data laporan yang tercatat dalam sistem menggunakan relasi Multi-Table SQL JOIN.</p>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small">
                            <th scope="col">No.</th>
                            <th scope="col">Fasilitas</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Pelapor</th>
                            <th scope="col">Prioritas</th>
                            <th scope="col">Status</th>
                            <th scope="col">Feedback / Ulasan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php
                        if (count($laporan_list) > 0) {
                            $no = 1;
                            foreach ($laporan_list as $row) {
                                $status = !empty($row['status']) ? $row['status'] : 'Menunggu';
                                
                                $badge_color = 'bg-secondary';
                                if ($status == 'Menunggu') $badge_color = 'bg-warning text-dark';
                                elseif ($status == 'Diproses') $badge_color = 'bg-info text-dark';
                                elseif ($status == 'Selesai') $badge_color = 'bg-success';

                                $id_data = $row['id_laporan'];

                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_fasilitas'] ?? '-') . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_kategori'] ?? '-') . "</td>";
                                echo "<td>" . htmlspecialchars($row['lokasi'] ?? '-') . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_pelapor'] ?? '-') . "</td>";
                                echo "<td>" . htmlspecialchars($row['prioritas'] ?? '-') . "</td>";
                                echo "<td><span class='badge " . $badge_color . "'>" . htmlspecialchars($status) . "</span></td>";
                                
                                // Kolom Feedback
                                echo "<td>";
                                if (!empty($row['rating'])) {
                                    echo "⭐ " . $row['rating'] . "/5 - " . htmlspecialchars($row['komentar']);
                                } else {
                                    if ($status == 'Selesai') {
                                        echo "<button class='btn btn-sm btn-outline-primary py-0 px-2' data-bs-toggle='modal' data-bs-target='#modalFeedback" . $id_data . "'>Beri Ulasan</button>";
                                    } else {
                                        echo "<span class='text-muted fst-italic'>Menunggu selesai</span>";
                                    }
                                }
                                echo "</td>";

                                // Tombol Aksi Update Status
                                echo "<td>";
                                if ($status != 'Diproses') {
                                    echo "<a href='riwayat.php?aksi=update_status&id=" . $id_data . "&status=Diproses' class='btn btn-sm btn-outline-info me-1 mb-1'>Proses</a>";
                                }
                                if ($status != 'Selesai') {
                                    echo "<a href='riwayat.php?aksi=update_status&id=" . $id_data . "&status=Selesai' class='btn btn-sm btn-outline-success mb-1'>Selesai</a>";
                                }
                                echo "</td>";

                                echo "</tr>";

                                // Modal Form Feedback
                                if ($status == 'Selesai' && empty($row['rating'])) {
                                    echo '
                                    <div class="modal fade" id="modalFeedback' . $id_data . '" tabindex="-1" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <form action="" method="post">
                                            <div class="modal-header">
                                              <h5 class="modal-title fs-6 fw-bold">Beri Ulasan - ' . htmlspecialchars($row['nama_fasilitas']) . '</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                              <input type="hidden" name="tambah_feedback" value="1">
                                              <input type="hidden" name="id_laporan" value="' . $id_data . '">
                                              
                                              <div class="mb-3">
                                                  <label class="form-label">Rating Kepuasan (1-5)</label>
                                                  <select name="rating" class="form-select" required>
                                                      <option value="">-- Pilih Bintang --</option>
                                                      <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                                      <option value="4">⭐⭐⭐⭐ (4 - Puas)</option>
                                                      <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                                      <option value="2">⭐⭐ (2 - Kurang)</option>
                                                      <option value="1">⭐ (1 - Buruk)</option>
                                                  </select>
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label">Komentar / Pesan</label>
                                                  <textarea name="komentar" class="form-control" rows="3" placeholder="Tulis tanggapan perbaikan..." required></textarea>
                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                              <button type="submit" class="btn btn-primary btn-sm">Kirim Ulasan</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>';
                                }
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='9' class='text-center text-muted py-4'>Belum ada data laporan yang tersedia.</td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="text-center py-3 text-muted border-top bg-white">
        <p class="mb-0 small">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>