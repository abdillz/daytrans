<?php
require_once 'config/config.php';

// Check if payment submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['booking_data'])) {
    header('Location: index.php');
    exit;
}

$payment_method = $_POST['payment_method'] ?? '';
$booking = $_SESSION['booking_data'];

// Generate ticket ID
$ticket_id = 'TKT' . strtoupper(substr(md5(time()), 0, 8));

// QR Code data (in real app, this would generate actual QR code)
$qr_data = json_encode([
    'ticket_id' => $ticket_id,
    'from' => $booking['from'],
    'to' => $booking['to'],
    'date' => $booking['date'],
    'seats' => implode(',', $booking['seats'])
]);

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Success Message -->
    <div class="text-center mb-5">
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
        </div>
        <h2 class="mb-3">Pembayaran Berhasil!</h2>
        <p class="text-muted">Terima kasih telah memesan tiket dengan DayTrans</p>
    </div>

    <div class="row">
        <!-- E-Ticket -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> E-Ticket</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Ticket Header -->
                    <div class="text-center mb-4 pb-4 border-bottom">
                        <h3 class="text-primary mb-0">
                            <i class="bi bi-bus-front"></i> DayTrans
                        </h3>
                        <small class="text-muted">Electronic Ticket</small>
                    </div>

                    <!-- Ticket Info -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Nomor Tiket</small>
                            <h5 class="mb-0"><?php echo $ticket_id; ?></h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Status</small>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>

                    <!-- Journey Details -->
                    <div class="bg-light p-4 rounded mb-4">
                        <div class="row">
                            <div class="col-md-5">
                                <small class="text-muted d-block mb-1">Keberangkatan</small>
                                <h4 class="mb-0"><?php echo $booking['from']; ?></h4>
                                <p class="text-muted mb-0">
                                    <?php echo date('d M Y', strtotime($booking['date'])); ?>
                                    <br>
                                    <?php echo $booking['schedule']['departureTime']; ?>
                                </p>
                            </div>
                            <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                                <div>
                                    <small class="text-muted d-block"><?php echo $booking['schedule']['duration']; ?></small>
                                    <i class="bi bi-arrow-right text-primary fs-3"></i>
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <small class="text-muted d-block mb-1">Kedatangan</small>
                                <h4 class="mb-0"><?php echo $booking['to']; ?></h4>
                                <p class="text-muted mb-0">
                                    <?php echo date('d M Y', strtotime($booking['date'])); ?>
                                    <br>
                                    <?php echo $booking['schedule']['arrivalTime']; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Bus & Seat Info -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Tipe Bus</small>
                            <p class="mb-0 fw-bold"><?php echo $booking['schedule']['busType']; ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Nomor Kursi</small>
                            <p class="mb-0 fw-bold"><?php echo implode(', ', $booking['seats']); ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Jumlah Penumpang</small>
                            <p class="mb-0 fw-bold"><?php echo count($booking['seats']); ?> Orang</p>
                        </div>
                    </div>

                    <!-- Passenger Details -->
                    <div class="mb-4">
                        <h6 class="mb-3">Data Penumpang</h6>
                        <?php foreach ($booking['passengers'] as $index => $passenger): ?>
                        <div class="border rounded p-3 mb-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Nama</small>
                                    <p class="mb-0"><?php echo htmlspecialchars($passenger['name']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Telepon</small>
                                    <p class="mb-0"><?php echo htmlspecialchars($passenger['phone']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Email</small>
                                    <p class="mb-0"><?php echo htmlspecialchars($passenger['email']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-light p-3 rounded mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Metode Pembayaran</small>
                                <p class="mb-0 fw-bold"><?php echo htmlspecialchars($payment_method); ?></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted d-block">Total Pembayaran</small>
                                <h5 class="text-primary mb-0"><?php echo formatRupiah($booking['total']); ?></h5>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="text-center py-4 border-top">
                        <small class="text-muted d-block mb-3">Scan QR Code saat check-in</small>
                        <div class="d-inline-block p-3 bg-white border rounded">
                            <!-- In real app, generate actual QR code image -->
                            <div style="width: 200px; height: 200px; background: linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0), linear-gradient(45deg, #f0f0f0 25%, transparent 25%, transparent 75%, #f0f0f0 75%, #f0f0f0); background-size: 20px 20px; background-position: 0 0, 10px 10px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-qr-code" style="font-size: 3rem; color: #000;"></i>
                            </div>
                        </div>
                        <p class="text-muted mt-2 mb-0">
                            <small>Kode: <?php echo $ticket_id; ?></small>
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-3 mt-4">
                        <div class="col-md-4">
                            <button onclick="window.print()" class="btn btn-outline-primary w-100">
                                <i class="bi bi-printer"></i> Cetak Tiket
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button onclick="downloadPDF()" class="btn btn-outline-primary w-100">
                                <i class="bi bi-download"></i> Download PDF
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button onclick="sendEmail()" class="btn btn-outline-primary w-100">
                                <i class="bi bi-envelope"></i> Kirim Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="bi bi-info-circle"></i> Informasi Penting</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Tiba di terminal minimal 30 menit sebelum keberangkatan
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Bawa KTP/identitas untuk verifikasi
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Simpan e-ticket Anda dengan baik
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Hubungi customer service untuk bantuan
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body text-center">
                    <i class="bi bi-headset text-primary mb-3" style="font-size: 2.5rem;"></i>
                    <h6>Butuh Bantuan?</h6>
                    <p class="text-muted small mb-3">
                        Tim customer service kami siap membantu Anda 24/7
                    </p>
                    <a href="help.php" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-chat-dots"></i> Hubungi CS
                    </a>
                    <p class="mb-0 small">
                        <i class="bi bi-telephone"></i> +62 21 1234 5678
                    </p>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="my-account.php" class="btn btn-primary">
                    <i class="bi bi-person-circle"></i> Lihat Pesanan Saya
                </a>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, nav, footer, .col-lg-4 {
            display: none !important;
        }
        .col-lg-8 {
            width: 100% !important;
        }
    }
</style>

<script>
    function downloadPDF() {
        alert('Fitur download PDF akan segera tersedia.\nUntuk saat ini, gunakan fitur Print dan save as PDF.');
        window.print();
    }

    function sendEmail() {
        alert('E-ticket telah dikirim ke email yang terdaftar:\n<?php echo htmlspecialchars($booking['passengers'][0]['email'] ?? 'email@example.com'); ?>');
    }
</script>

<?php include 'includes/footer.php'; ?>
