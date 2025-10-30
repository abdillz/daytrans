<?php
require_once 'config/config.php';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get booking data
$schedule_id = $_POST['schedule_id'] ?? '';
$from = $_POST['from'] ?? '';
$to = $_POST['to'] ?? '';
$date = $_POST['date'] ?? '';
$passengers = $_POST['passengers'] ?? 1;
$selected_seats = explode(',', $_POST['selected_seats'] ?? '');

// Get schedule
$schedules = getMockSchedules($from, $to, $date);
$schedule = null;
foreach ($schedules as $s) {
    if ($s['id'] === $schedule_id) {
        $schedule = $s;
        break;
    }
}

if (!$schedule || empty($selected_seats)) {
    header('Location: index.php');
    exit;
}

// Calculate total
$total = count($selected_seats) * $schedule['price'];

// Store booking data in session
$_SESSION['booking_data'] = [
    'schedule' => $schedule,
    'from' => $from,
    'to' => $to,
    'date' => $date,
    'seats' => $selected_seats,
    'total' => $total,
    'passengers' => []
];

// Get passenger data
for ($i = 0; $i < count($selected_seats); $i++) {
    $_SESSION['booking_data']['passengers'][] = [
        'name' => $_POST["passenger_name_$i"] ?? '',
        'phone' => $_POST["passenger_phone_$i"] ?? '',
        'email' => $_POST["passenger_email_$i"] ?? ''
    ];
}

include 'includes/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="search.php">Hasil Pencarian</a></li>
            <li class="breadcrumb-item"><a href="seat-selection.php">Pilih Kursi</a></li>
            <li class="breadcrumb-item active">Pembayaran</li>
        </ol>
    </nav>

    <h2 class="mb-4">Pembayaran</h2>

    <div class="row">
        <!-- Payment Methods -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Pilih Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form id="paymentForm" action="confirmation.php" method="POST">
                        <!-- Virtual Account -->
                        <div class="mb-4">
                            <h6 class="mb-3">Virtual Account</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bca" value="BCA Virtual Account" required>
                                        <label class="form-check-label d-flex align-items-center" for="bca">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>BCA</strong>
                                            </div>
                                            <span>BCA Virtual Account</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="mandiri" value="Mandiri Virtual Account">
                                        <label class="form-check-label d-flex align-items-center" for="mandiri">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>Mandiri</strong>
                                            </div>
                                            <span>Mandiri Virtual Account</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bni" value="BNI Virtual Account">
                                        <label class="form-check-label d-flex align-items-center" for="bni">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>BNI</strong>
                                            </div>
                                            <span>BNI Virtual Account</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bri" value="BRI Virtual Account">
                                        <label class="form-check-label d-flex align-items-center" for="bri">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>BRI</strong>
                                            </div>
                                            <span>BRI Virtual Account</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- E-Wallet -->
                        <div class="mb-4">
                            <h6 class="mb-3">E-Wallet</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="gopay" value="GoPay">
                                        <label class="form-check-label d-flex align-items-center" for="gopay">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>GO</strong>
                                            </div>
                                            <span>GoPay</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="ovo" value="OVO">
                                        <label class="form-check-label d-flex align-items-center" for="ovo">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>OVO</strong>
                                            </div>
                                            <span>OVO</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="dana" value="DANA">
                                        <label class="form-check-label d-flex align-items-center" for="dana">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>DANA</strong>
                                            </div>
                                            <span>DANA</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="payment_method" id="shopeepay" value="ShopeePay">
                                        <label class="form-check-label d-flex align-items-center" for="shopeepay">
                                            <div class="payment-logo bg-light p-2 rounded me-2">
                                                <strong>SP</strong>
                                            </div>
                                            <span>ShopeePay</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Credit/Debit Card -->
                        <div class="mb-4">
                            <h6 class="mb-3">Kartu Kredit/Debit</h6>
                            <div class="form-check payment-option">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="Credit/Debit Card">
                                <label class="form-check-label" for="card">
                                    <i class="bi bi-credit-card-2-front"></i> Kartu Kredit/Debit
                                </label>
                            </div>
                        </div>

                        <hr>

                        <!-- COD -->
                        <div class="mb-4">
                            <h6 class="mb-3">Bayar di Tempat</h6>
                            <div class="form-check payment-option">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="Cash on Departure">
                                <label class="form-check-label" for="cod">
                                    <i class="bi bi-cash-coin"></i> Bayar di Terminal
                                </label>
                            </div>
                            <small class="text-muted ms-4">
                                Bayar langsung di terminal sebelum keberangkatan (minimal 1 jam sebelum)
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Rute</small>
                        <p class="mb-0 fw-bold"><?php echo $from; ?> → <?php echo $to; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tanggal & Waktu</small>
                        <p class="mb-0"><?php echo date('d M Y', strtotime($date)); ?>, <?php echo $schedule['departureTime']; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tipe Bus</small>
                        <p class="mb-0"><?php echo $schedule['busType']; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Kursi</small>
                        <p class="mb-0"><?php echo implode(', ', $selected_seats); ?></p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Harga (<?php echo count($selected_seats); ?> kursi)</span>
                            <span><?php echo formatRupiah($total); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Diskon</span>
                            <span>-</span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total Pembayaran</span>
                            <span class="text-primary fs-5"><?php echo formatRupiah($total); ?></span>
                        </div>
                    </div>

                    <button type="submit" form="paymentForm" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-check-circle"></i> Bayar Sekarang
                    </button>

                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i> Pembayaran aman & terenkripsi
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-option {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .payment-option:hover {
        border-color: #2563eb;
        background-color: #f8f9fa;
    }
    
    .payment-option input[type="radio"]:checked ~ label {
        color: #2563eb;
        font-weight: 500;
    }
    
    .payment-option:has(input[type="radio"]:checked) {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
    
    .payment-logo {
        width: 50px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
</style>

<?php include 'includes/footer.php'; ?>
