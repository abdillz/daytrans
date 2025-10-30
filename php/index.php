<?php
require_once 'config/config.php';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-primary text-white py-5" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Perjalanan Nyaman Bersama DayTrans</h1>
                <p class="lead mb-4">
                    Pesan tiket bus online dengan mudah, cepat, dan aman. Nikmati perjalanan Anda dengan layanan terbaik.
                </p>
                <div class="d-flex gap-3">
                    <a href="#search-widget" class="btn btn-light btn-lg">
                        <i class="bi bi-search"></i> Pesan Sekarang
                    </a>
                    <a href="about.php" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-info-circle"></i> Tentang Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="bi bi-bus-front" style="font-size: 15rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Widget -->
<section id="search-widget" class="py-5">
    <div class="container">
        <div class="card shadow-lg" style="margin-top: -50px; position: relative; z-index: 10;">
            <div class="card-body p-4">
                <h3 class="mb-4">Cari Tiket Bus</h3>
                <form action="search.php" method="GET">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">Dari</label>
                            <select name="from" class="form-select" required>
                                <option value="">Pilih kota asal</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">Ke</label>
                            <select name="to" class="form-select" required>
                                <option value="">Pilih kota tujuan</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label">Penumpang</label>
                            <select name="passengers" class="form-select" required>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?> Orang</option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-search"></i> Cari Perjalanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih DayTrans?</h2>
            <p class="text-muted">Layanan terbaik untuk perjalanan Anda</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Aman & Terpercaya</h5>
                        <p class="card-text text-muted">
                            Armada terawat dengan standar keamanan terbaik dan driver berpengalaman.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-clock-history text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Tepat Waktu</h5>
                        <p class="card-text text-muted">
                            Komitmen kami untuk selalu on-time dan memberikan pelayanan terbaik.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="bi bi-credit-card text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Pembayaran Mudah</h5>
                        <p class="card-text text-muted">
                            Berbagai metode pembayaran tersedia untuk kemudahan Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Routes -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Rute Populer</h2>
            <p class="text-muted">Pilihan rute favorit penumpang kami</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Jakarta - Bandung</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-clock"></i> 3-4 jam
                        </p>
                        <p class="text-primary fw-bold mb-3">Mulai dari Rp 75.000</p>
                        <a href="search.php?from=Jakarta&to=Bandung&date=<?php echo date('Y-m-d', strtotime('+1 day')); ?>&passengers=1" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Surabaya - Malang</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-clock"></i> 2-3 jam
                        </p>
                        <p class="text-primary fw-bold mb-3">Mulai dari Rp 50.000</p>
                        <a href="search.php?from=Surabaya&to=Malang&date=<?php echo date('Y-m-d', strtotime('+1 day')); ?>&passengers=1" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Yogyakarta - Solo</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-clock"></i> 1-2 jam
                        </p>
                        <p class="text-primary fw-bold mb-3">Mulai dari Rp 35.000</p>
                        <a href="search.php?from=Yogyakarta&to=Solo&date=<?php echo date('Y-m-d', strtotime('+1 day')); ?>&passengers=1" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Bandung - Yogyakarta</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-clock"></i> 8-9 jam
                        </p>
                        <p class="text-primary fw-bold mb-3">Mulai dari Rp 150.000</p>
                        <a href="search.php?from=Bandung&to=Yogyakarta&date=<?php echo date('Y-m-d', strtotime('+1 day')); ?>&passengers=1" class="btn btn-outline-primary btn-sm w-100">
                            Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Siap Memulai Perjalanan?</h2>
        <p class="lead mb-4">Pesan tiket Anda sekarang dan nikmati perjalanan yang nyaman</p>
        <a href="#search-widget" class="btn btn-light btn-lg">
            <i class="bi bi-ticket-perforated"></i> Pesan Tiket Sekarang
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
