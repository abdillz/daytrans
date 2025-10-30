<?php
require_once 'config/config.php';
include 'includes/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2>Bantuan</h2>
        <p class="text-muted">Bagaimana kami bisa membantu Anda?</p>
    </div>

    <div class="row">
        <!-- FAQ -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-question-circle"></i> FAQ</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Bagaimana cara memesan tiket?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <ol>
                                        <li>Pilih kota keberangkatan dan tujuan</li>
                                        <li>Tentukan tanggal dan jumlah penumpang</li>
                                        <li>Klik "Cari Perjalanan"</li>
                                        <li>Pilih jadwal yang sesuai</li>
                                        <li>Pilih kursi dan isi data penumpang</li>
                                        <li>Pilih metode pembayaran</li>
                                        <li>Selesaikan pembayaran</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Bagaimana cara membatalkan pesanan?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pembatalan dapat dilakukan maksimal 4 jam sebelum keberangkatan melalui menu "Akun Saya". Biaya pembatalan akan dikenakan sesuai ketentuan yang berlaku.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Apakah bisa reschedule?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, reschedule dapat dilakukan dengan menghubungi customer service kami minimal 6 jam sebelum keberangkatan. Biaya reschedule akan disesuaikan dengan perbedaan harga tiket.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Metode pembayaran apa saja yang tersedia?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Kami menerima berbagai metode pembayaran:
                                    <ul>
                                        <li>Virtual Account (BCA, Mandiri, BNI, BRI)</li>
                                        <li>E-Wallet (GoPay, OVO, DANA, ShopeePay)</li>
                                        <li>Kartu Kredit/Debit</li>
                                        <li>Bayar di Terminal</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Apakah bisa membawa bagasi?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, setiap penumpang berhak membawa bagasi dengan berat maksimal 20 kg. Bagasi berlebih akan dikenakan biaya tambahan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Guides -->
        <div class="col-lg-6">
            <!-- Contact Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-headset"></i> Hubungi Kami</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-telephone text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Customer Service</small>
                                <strong>+62 21 1234 5678</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-whatsapp text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">WhatsApp</small>
                                <strong>+62 812 3456 7890</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-envelope text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Email</small>
                                <strong>info@daytrans.co.id</strong>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="bi bi-clock text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Jam Operasional</small>
                                <strong>24 Jam / 7 Hari</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Guides -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Panduan Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                            Cara Pemesanan Tiket
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                            Cara Pembayaran
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                            Cara Check-in
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                            Kebijakan Pembatalan
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-right-circle text-primary me-2"></i>
                            Syarat & Ketentuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Tutorials -->
    <div class="mt-5">
        <h4 class="mb-4 text-center">Tutorial Video</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="ratio ratio-16x9 bg-light">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-play-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6>Cara Pesan Tiket Online</h6>
                        <small class="text-muted">5 menit</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="ratio ratio-16x9 bg-light">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-play-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6>Cara Check-in dengan E-Ticket</h6>
                        <small class="text-muted">3 menit</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="ratio ratio-16x9 bg-light">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-play-circle text-primary" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6>Cara Reschedule Tiket</h6>
                        <small class="text-muted">4 menit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
