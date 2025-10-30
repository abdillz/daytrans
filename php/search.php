<?php
// 1. Sertakan config.php untuk koneksi database dan fungsi lainnya.
require_once 'config/config.php';

// 2. Ambil parameter pencarian dari URL.
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';
$passengers = $_GET['passengers'] ?? 1;

// 3. Validasi input dasar.
if (empty($from) || empty($to) || empty($date)) {
    // Jika parameter tidak lengkap, kembalikan ke halaman utama.
    header('Location: index.php');
    exit;
}
if ($from === $to) {
    // Jika kota asal dan tujuan sama, beri pesan error via session.
    $_SESSION['error_message'] = 'Kota keberangkatan dan tujuan tidak boleh sama.';
    header('Location: index.php');
    exit;
}

// Simpan parameter pencarian ke session untuk digunakan nanti.
$_SESSION['search_params'] = compact('from', 'to', 'date', 'passengers');

$schedules = []; // Inisialisasi array untuk menampung hasil.
$page_error = ''; // Inisialisasi variabel untuk pesan error.

// 4. Lakukan pencarian ke database.
try {
    // Query untuk mengambil data perjalanan dan menggabungkannya dengan data armada.
    $sql = "SELECT 
                perj.id_perjalanan, 
                perj.waktu_berangkat,
                perj.durasi_menit, -- Asumsi Anda sudah menambahkan kolom ini
                perj.harga,
                arm.jenis_kendaraan,
                arm.kapasitas_kursi
                -- CATATAN: Harga tidak ada di tabel perjalanan Anda, jadi kita gunakan placeholder.
            FROM 
                perjalanan AS perj
            JOIN 
                armada AS arm ON perj.id_armada = arm.id_armada
            WHERE 
                perj.rute_awal = ? 
                AND perj.kota_tujuan = ? 
                AND perj.tgl_berangkat = ?
            ORDER BY 
                perj.waktu_berangkat ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$from, $to, $date]);
    $schedules = $stmt->fetchAll();

} catch (PDOException $e) {
    $page_error = "Terjadi kesalahan saat mencari jadwal. Silakan coba lagi.";
    error_log("Search error: " . $e->getMessage()); // Catat error untuk developer.
}

// 5. Sertakan header HTML.
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-3">Hasil Pencarian Tiket</h5>
                    <div class="d-flex flex-wrap gap-3">
                        <div><small class="text-muted d-block">Dari</small><strong><?php echo htmlspecialchars($from); ?></strong></div>
                        <div><i class="bi bi-arrow-right text-primary"></i></div>
                        <div><small class="text-muted d-block">Ke</small><strong><?php echo htmlspecialchars($to); ?></strong></div>
                        <div class="ms-3"><small class="text-muted d-block">Tanggal</small><strong><?php echo date('d M Y', strtotime($date)); ?></strong></div>
                        <div><small class="text-muted d-block">Penumpang</small><strong><?php echo $passengers; ?> Orang</strong></div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="index.php" class="btn btn-outline-primary"><i class="bi bi-search"></i> Ubah Pencarian</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-funnel"></i> Filter</h6></div>
                <div class="card-body">
                    </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="text-muted mb-0">Ditemukan <strong><?php echo count($schedules); ?> jadwal</strong></p>
            </div>
            
            <?php if (!empty($page_error)): ?>
                <div class="alert alert-danger"><?php echo $page_error; ?></div>
            <?php elseif (empty($schedules)): ?>
                <div class="card"><div class="card-body text-center py-5">
                    <i class="bi bi-exclamation-circle text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Tidak Ada Jadwal Tersedia</h4>
                    <p class="text-muted">Maaf, tidak ada jadwal yang tersedia untuk rute ini pada tanggal yang dipilih.</p>
                </div></div>
            <?php else: ?>
                <?php foreach ($schedules as $schedule): ?>
                <div class="card mb-3 shadow-sm hover-shadow">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted d-block">Keberangkatan</small>
                                        <h4 class="mb-0"><?php echo date('H:i', strtotime($schedule['waktu_berangkat'])); ?></h4>
                                        <small class="text-muted"><?php echo htmlspecialchars($from); ?></small>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <small class="text-muted d-block">
                                            <?php 
                                                // Menghitung durasi dalam format jam dan menit
                                                if (!empty($schedule['durasi_menit'])) {
                                                    $hours = floor($schedule['durasi_menit'] / 60);
                                                    $minutes = $schedule['durasi_menit'] % 60;
                                                    echo "{$hours} jam {$minutes} mnt";
                                                }
                                            ?>
                                        </small>
                                        <div class="my-2"><i class="bi bi-arrow-right text-primary"></i></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($schedule['jenis_kendaraan']); ?></small>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <small class="text-muted d-block">Kedatangan</small>
                                        <h4 class="mb-0">
                                            <?php
                                                // Menghitung waktu tiba
                                                if (!empty($schedule['durasi_menit'])) {
                                                    $arrivalTime = strtotime($schedule['waktu_berangkat'] . " +{$schedule['durasi_menit']} minutes");
                                                    echo date('H:i', $arrivalTime);
                                                } else {
                                                    echo '--:--';
                                                }
                                            ?>
                                        </h4>
                                        <small class="text-muted"><?php echo htmlspecialchars($to); ?></small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-success">
                                        <i class="bi bi-people"></i> Kapasitas: <?php echo $schedule['kapasitas_kursi']; ?> kursi
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <small class="text-muted d-block">Harga per orang</small>
                                <h3 class="text-primary mb-3"><?php echo formatRupiah($schedule['harga']); ?></h3>
                                <a href="seat-selection.php?id_perjalanan=<?php echo $schedule['id_perjalanan']; ?>" class="btn btn-primary w-100">
                                    <i class="bi bi-arrow-right-circle"></i> Pilih
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>