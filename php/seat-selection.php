<?php
require_once 'config/config.php';

// Get parameters
$schedule_id = $_GET['schedule_id'] ?? '';
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$date = $_GET['date'] ?? '';
$passengers = $_GET['passengers'] ?? 1;

// Validate
if (empty($schedule_id) || empty($from) || empty($to) || empty($date)) {
    header('Location: index.php');
    exit;
}

// Get schedule data
$schedules = getMockSchedules($from, $to, $date);
$schedule = null;
foreach ($schedules as $s) {
    if ($s['id'] === $schedule_id) {
        $schedule = $s;
        break;
    }
}

if (!$schedule) {
    header('Location: search.php');
    exit;
}

// Mock occupied seats
$occupiedSeats = ['A2', 'A4', 'B1', 'B3', 'C2', 'D4'];

include 'includes/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="search.php?from=<?php echo urlencode($from); ?>&to=<?php echo urlencode($to); ?>&date=<?php echo $date; ?>&passengers=<?php echo $passengers; ?>">Hasil Pencarian</a></li>
            <li class="breadcrumb-item active">Pilih Kursi</li>
        </ol>
    </nav>

    <h2 class="mb-4">Pilih Kursi dan Isi Data Penumpang</h2>

    <div class="row">
        <!-- Seat Map -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-grid-3x3"></i> Denah Bus - <?php echo $schedule['busType']; ?></h5>
                </div>
                <div class="card-body">
                    <!-- Legend -->
                    <div class="d-flex gap-4 mb-4 flex-wrap">
                        <div class="d-flex align-items-center">
                            <div class="seat-legend available"></div>
                            <small class="ms-2">Tersedia</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="seat-legend selected"></div>
                            <small class="ms-2">Dipilih</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="seat-legend occupied"></div>
                            <small class="ms-2">Terisi</small>
                        </div>
                    </div>

                    <!-- Bus Layout -->
                    <div class="bus-layout">
                        <!-- Driver -->
                        <div class="text-center mb-3">
                            <div class="driver-seat">
                                <i class="bi bi-steering-wheel"></i> Supir
                            </div>
                        </div>

                        <!-- Seats Grid -->
                        <div class="seats-grid">
                            <?php
                            $rows = ['A', 'B', 'C', 'D', 'E'];
                            $cols = 4;
                            
                            foreach ($rows as $row) {
                                echo '<div class="seat-row">';
                                for ($col = 1; $col <= $cols; $col++) {
                                    $seatNumber = $row . $col;
                                    $isOccupied = in_array($seatNumber, $occupiedSeats);
                                    
                                    if ($col == 2) {
                                        echo '<div class="aisle"></div>';
                                    }
                                    
                                    echo '<div class="seat ' . ($isOccupied ? 'occupied' : 'available') . '" data-seat="' . $seatNumber . '">';
                                    echo '<i class="bi bi-square"></i>';
                                    echo '<small class="seat-number">' . $seatNumber . '</small>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="mt-3 text-muted">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            Pilih <?php echo $passengers; ?> kursi untuk melanjutkan pemesanan
                        </small>
                    </div>
                </div>
            </div>

            <!-- Passenger Data Form -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Data Penumpang</h5>
                </div>
                <div class="card-body">
                    <form id="passengerForm" action="payment.php" method="POST">
                        <input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>">
                        <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                        <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                        <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">
                        <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="">
                        
                        <div id="passengerForms"></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ringkasan Pemesanan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Rute</small>
                        <p class="mb-0 fw-bold"><?php echo $from; ?> → <?php echo $to; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tanggal</small>
                        <p class="mb-0"><?php echo date('d M Y', strtotime($date)); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Waktu Keberangkatan</small>
                        <p class="mb-0"><?php echo $schedule['departureTime']; ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Tipe Bus</small>
                        <p class="mb-0"><?php echo $schedule['busType']; ?></p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">Kursi Dipilih</small>
                        <p class="mb-0" id="selectedSeatsDisplay">-</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Jumlah Penumpang</small>
                        <p class="mb-0"><span id="selectedCount">0</span> / <?php echo $passengers; ?> orang</p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Harga per kursi</span>
                            <span><?php echo formatRupiah($schedule['price']); ?></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span class="text-primary" id="totalPrice"><?php echo formatRupiah(0); ?></span>
                        </div>
                    </div>

                    <button type="submit" form="passengerForm" id="continueBtn" class="btn btn-primary w-100" disabled>
                        <i class="bi bi-arrow-right-circle"></i> Lanjut ke Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .seat-legend {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: 2px solid #ddd;
    }
    
    .seat-legend.available {
        background-color: #fff;
        border-color: #2563eb;
    }
    
    .seat-legend.selected {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    
    .seat-legend.occupied {
        background-color: #e5e7eb;
        border-color: #9ca3af;
    }
    
    .driver-seat {
        display: inline-block;
        padding: 10px 20px;
        background-color: #f3f4f6;
        border-radius: 8px;
        border: 2px solid #d1d5db;
    }
    
    .bus-layout {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        border: 3px solid #dee2e6;
    }
    
    .seats-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .seat-row {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    
    .seat {
        width: 50px;
        height: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid;
    }
    
    .seat.available {
        background-color: #fff;
        border-color: #2563eb;
        color: #2563eb;
    }
    
    .seat.available:hover {
        background-color: #dbeafe;
        transform: scale(1.05);
    }
    
    .seat.selected {
        background-color: #2563eb;
        border-color: #1e40af;
        color: #fff;
        transform: scale(1.05);
    }
    
    .seat.occupied {
        background-color: #e5e7eb;
        border-color: #9ca3af;
        color: #6b7280;
        cursor: not-allowed;
    }
    
    .seat i {
        font-size: 1.2rem;
    }
    
    .seat-number {
        font-size: 0.7rem;
        font-weight: bold;
    }
    
    .aisle {
        width: 30px;
    }
</style>

<script>
    const maxPassengers = <?php echo $passengers; ?>;
    const pricePerSeat = <?php echo $schedule['price']; ?>;
    const selectedSeats = [];

    // Seat selection
    document.querySelectorAll('.seat.available').forEach(seat => {
        seat.addEventListener('click', function() {
            const seatNumber = this.dataset.seat;
            
            if (this.classList.contains('selected')) {
                // Deselect
                this.classList.remove('selected');
                const index = selectedSeats.indexOf(seatNumber);
                if (index > -1) selectedSeats.splice(index, 1);
            } else {
                // Select
                if (selectedSeats.length < maxPassengers) {
                    this.classList.add('selected');
                    selectedSeats.push(seatNumber);
                } else {
                    alert('Anda sudah memilih ' + maxPassengers + ' kursi');
                }
            }
            
            updateSummary();
        });
    });

    function updateSummary() {
        const count = selectedSeats.length;
        const total = count * pricePerSeat;
        
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('selectedSeatsDisplay').textContent = count > 0 ? selectedSeats.join(', ') : '-';
        document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('selectedSeatsInput').value = selectedSeats.join(',');
        
        // Generate passenger forms
        const formsContainer = document.getElementById('passengerForms');
        formsContainer.innerHTML = '';
        
        if (count > 0) {
            selectedSeats.forEach((seat, index) => {
                const formHtml = `
                    <div class="mb-4 p-3 border rounded">
                        <h6 class="mb-3">Penumpang ${index + 1} - Kursi ${seat}</h6>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" name="passenger_name_${index}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" name="passenger_phone_${index}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="passenger_email_${index}" required>
                            </div>
                        </div>
                    </div>
                `;
                formsContainer.innerHTML += formHtml;
            });
        }
        
        // Enable/disable continue button
        document.getElementById('continueBtn').disabled = count !== maxPassengers;
    }
</script>

<?php include 'includes/footer.php'; ?>
