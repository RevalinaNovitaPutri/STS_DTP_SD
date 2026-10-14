<?php

include 'database.php';

// Proses penyimpanan data (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama_pelapor'])) {
    $nama_pelapor   = mysqli_real_escape_string($conn, $_POST['nama_pelapor']);
    $nama_fasilitas = mysqli_real_escape_string($conn, $_POST['nama_fasilitas']);
    $lokasi         = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $id_kategori    = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $deskripsi      = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $prioritas      = mysqli_real_escape_string($conn, $_POST['prioritas']);
    
    if (!empty($nama_pelapor) && !empty($nama_fasilitas)) {
        $query_insert = "INSERT INTO laporan_kerusakan (nama_pelapor, nama_fasilitas, lokasi, id_kategori, deskripsi, prioritas) 
                         VALUES ('$nama_pelapor', '$nama_fasilitas', '$lokasi', '$id_kategori', '$deskripsi', '$prioritas')";
        mysqli_query($conn, $query_insert);
        header("Location: index.php");
        exit();
    }
}

// Mengambil data dari database untuk tabel (Read)
$query_tampil = mysqli_query($conn, "SELECT * FROM laporan_kerusakan");
$total_data = mysqli_num_rows($query_tampil);

?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelaporan Kerusakan Fasilitas Sekolah</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-1a/pXj49ZQ1aHEmrJ+gMw1otqoVsYwlEnlD8mIfY2TV03r20Y0CN7uqx1tQogjPL" crossorigin="anonymous"></script>
    <style>
        :root {
            --maroon-primary: #800000;
            --maroon-dark: #4d0000;
            --maroon-light: #fff5f5;
        }
        body { 
            background-color: #f1f3f5; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        /* Header Unik ala Panel Korporat */
        .top-banner {
            background: linear-gradient(135deg, var(--maroon-primary), var(--maroon-dark));
            color: white;
            padding: 2rem 0;
            margin-bottom: -2.5rem;
            border-bottom: 4px solid #b30000;
        }
        .main-container {
            position: relative;
            z-index: 10;
        }
        .card-unique {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background: #ffffff;
            overflow: hidden;
        }
        .card-header-custom {
            background-color: var(--maroon-light);
            border-bottom: 2px solid #ffe3e3;
            color: var(--maroon-primary);
            font-weight: 700;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.6rem 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--maroon-primary);
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.15);
        }
        .btn-maroon {
            background-color: var(--maroon-primary);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.6rem;
            transition: all 0.2s ease-in-out;
        }
        .btn-maroon:hover {
            background-color: var(--maroon-dark);
            color: white;
            transform: translateY(-1px);
        }
        .table-hover tbody tr:hover {
            background-color: var(--maroon-light);
        }
    </style>
</head>
<body>
    
    <!-- BANNER HEADER ATAS (WARNA MERAH MAROON KENTAL) -->
    <header class="top-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span class="badge bg-white text-dark px-2 py-1 rounded small mb-2 fw-bold text-uppercase">Portal Internal</span>
                    <h1 class="h3 fw-bold mb-1">Manajemen Kerusakan Fasilitas Sekolah</h1>
                    <p class="text-white-50 small mb-0">Sistem terintegrasi untuk pelaporan dan pemantauan perbaikan sarana prasarana.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block bg-dark bg-opacity-25 px-3 py-2 rounded-3 border border-light border-opacity-10">
                        <span class="small d-block text-white-50">Total Data Masuk</span>
                        <span class="fs-5 fw-bold text-white"><?php echo $total_data; ?> Laporan</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- KONTEN UTAMA DENGAN EFEK KARTU MELAYANG -->
    <main class="container main-container mb-5 pb-4">
        <div class="row g-4">
            
            <!-- KOLOM KIRI: FORM INPUT (COMPACT & MODERN) -->
            <div class="col-lg-4">
                <section id="form-laporan" aria-labelledby="judul-form" class="card card-unique">
                    <div class="card-header card-header-custom py-3 px-4 d-flex align-items-center">
                        <i class="bi bi-pencil-square me-2 fs-5"></i>
                        <h2 id="judul-form" class="h6 mb-0 text-uppercase">Form Input Laporan</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="" method="post">
                            <fieldset class="border-0 p-0 m-0">
                                
                                <div class="mb-3">
                                    <label for="nama_pelapor" class="form-label small fw-bold text-secondary">Nama Pelapor</label>
                                    <input type="text" id="nama_pelapor" name="nama_pelapor" maxlength="100" class="form-control form-control-sm" placeholder="cth: Budi Santoso" required>
                                </div>

                                <div class="mb-3">
                                    <label for="nama_fasilitas" class="form-label small fw-bold text-secondary">Nama Fasilitas</label>
                                    <input type="text" id="nama_fasilitas" name="nama_fasilitas" maxlength="100" class="form-control form-control-sm" placeholder="cth: Proyektor / AC" required>
                                </div>

                                <div class="mb-3">
                                    <label for="lokasi" class="form-label small fw-bold text-secondary">Lokasi Kerusakan</label>
                                    <input type="text" id="lokasi" name="lokasi" maxlength="100" class="form-control form-control-sm" placeholder="cth: Ruang Kelas X-2" required>
                                </div>

                                <div class="mb-3">
                                    <label for="id_kategori" class="form-label small fw-bold text-secondary">Kategori Fasilitas</label>
                                    <select id="id_kategori" name="id_kategori" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="1">Elektronik</option>
                                        <option value="2">Furnitur</option>
                                        <option value="3">Jaringan</option>
                                        <option value="4">Peralatan Kelas</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label small fw-bold text-secondary">Deskripsi Kendala</label>
                                    <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control form-control-sm" placeholder="Jelaskan detail kerusakannya..." required></textarea>
                                </div>

                                <div class="mb-4">
                                    <label for="prioritas" class="form-label small fw-bold text-secondary">Tingkat Prioritas</label>
                                    <select id="prioritas" name="prioritas" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Prioritas --</option>
                                        <option value="Rendah">Rendah</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Tinggi">Tinggi</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-maroon w-100 shadow-sm">
                                    <i class="bi bi-send-fill me-1"></i> Kirim Laporan
                                </button>
                            </fieldset>
                        </form>
                    </div>
                </section>
            </div>

            <!-- KOLOM KANAN: TABEL REKAPITULASI (LUAS & BERSIH) -->
            <div class="col-lg-8">
                <section id="daftar-laporan" aria-labelledby="judul-daftar" class="card card-unique h-100">
                    <div class="card-header card-header-custom py-3 px-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-table me-2 fs-5"></i>
                            <h2 id="judul-daftar" class="h6 mb-0 text-uppercase">Daftar Rekapitulasi Laporan Masuk</h2>
                        </div>
                        <span class="badge bg-secondary px-2 py-1 small">Live Database</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-secondary small text-uppercase">
                                    <tr>
                                        <th scope="col" class="py-3">No.</th>
                                        <th scope="col" class="py-3">Fasilitas</th>
                                        <th scope="col" class="py-3">Lokasi</th>
                                        <th scope="col" class="py-3">Pelapor</th>
                                        <th scope="col" class="py-3">Prioritas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($query_tampil && mysqli_num_rows($query_tampil) > 0) {
                                        $no = 1;
                                        while ($row = mysqli_fetch_assoc($query_tampil)) {
                                            // Warna badge prioritas yang kontras & elegan
                                            $badge_class = 'bg-secondary';
                                            if (isset($row['prioritas']) && $row['prioritas'] == 'Tinggi') $badge_class = 'bg-danger';
                                            elseif (isset($row['prioritas']) && $row['prioritas'] == 'Sedang') $badge_class = 'bg-warning text-dark';
                                            elseif (isset($row['prioritas']) && $row['prioritas'] == 'Rendah') $badge_class = 'bg-info text-dark';

                                            echo "<tr>";
                                            echo "<td class='fw-bold text-muted'>" . $no++ . "</td>";
                                            echo "<td class='fw-bold text-dark'>" . htmlspecialchars($row['nama_fasilitas'] ?? '-') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['lokasi'] ?? '-') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nama_pelapor'] ?? '-') . "</td>";
                                            echo "<td><span class='badge " . $badge_class . " px-2 py-1'>" . htmlspecialchars($row['prioritas'] ?? '-') . "</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                                <td colspan='5' class='text-center text-muted py-5 small'>
                                                    <i class='bi bi-inbox fs-2 d-block mb-2 opacity-50'></i>
                                                    Belum ada data laporan kerusakan tersimpan di database.
                                                </td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 pt-3 border-top text-muted small text-end">
                            <span>Sistem Pelaporan Internal &bull; Sesuai Standar Akademik</span>
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-3 text-muted bg-white border-top small">
        <p class="mb-0">&copy; 2026 Sistem Pelaporan Kerusakan Fasilitas Sekolah.</p>
    </footer>
</body>
</html>