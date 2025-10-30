<?php
// 1. Sertakan config.php
require_once 'config/config.php';

// 2. Keamanan: Periksa apakah pengguna sudah login.
// Jika belum, alihkan ke halaman login.
if (!isLoggedIn()) {
    header('Location: login.php?redirect=my-account.php');
    exit();
}

// 3. Inisialisasi variabel dan tentukan tab aktif
$active_tab = $_GET['tab'] ?? 'orders';
$customer_id = $_SESSION['customer_id'];
$success_message = '';
$error_message = '';

// =================================================================
// 4. LOGIKA UNTUK MENANGANI FORM SUBMISSION (UPDATE PROFIL & KELUHAN)
// =================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A. Logika untuk Update Profil
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        $gender = $_POST['gender'] ?? '';

        try {
            $sql = "UPDATE customer SET nama = ?, no_telp = ?, alamat = ?, jenis_kelamin = ? WHERE id_customer = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $phone, $address, $gender, $customer_id]);
            
            // Update juga nama di session agar langsung berubah di header
            $_SESSION['user_name'] = $name;
            $success_message = 'Profil berhasil diperbarui!';
        } catch (PDOException $e) {
            $error_message = 'Gagal memperbarui profil.';
            error_log($e->getMessage());
        }
    }

    // B. Logika untuk Mengirim Keluhan Baru
    if (isset($_POST['submit_complaint'])) {
        $description = $_POST['description'] ?? '';
        if (!empty($description)) {
            try {
                $sql = "INSERT INTO keluhan (id_customer, deskripsi, status_penanganan) VALUES (?, ?, 'Baru')";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$customer_id, $description]);
                $success_message = 'Keluhan Anda berhasil dikirim!';
            } catch (PDOException $e) {
                $error_message = 'Gagal mengirim keluhan.';
                error_log($e->getMessage());
            }
        } else {
            $error_message = 'Deskripsi keluhan tidak boleh kosong.';
        }
    }
}


// =================================================================
// 5. MENGAMBIL DATA DARI DATABASE UNTUK DITAMPILKAN
// =================================================================
try {
    // A. Ambil data profil pengguna
    $stmt_user = $pdo->prepare("SELECT * FROM customer WHERE id_customer = ?");
    $stmt_user->execute([$customer_id]);
    $user = $stmt_user->fetch();

    // B. Ambil data pesanan (orders)
    $sql_orders = "SELECT p.id_pemesanan, perj.tgl_berangkat, perj.rute_awal, perj.kota_tujuan, perj.waktu_berangkat, 
                          p.status_pemesanan, p.total_bayar, GROUP_CONCAT(t.no_kursi SEPARATOR ', ') AS seats
                   FROM pemesanan p
                   JOIN perjalanan perj ON p.id_perjalanan = perj.id_perjalanan
                   LEFT JOIN tiket t ON p.id_pemesanan = t.id_pemesanan
                   WHERE p.id_customer = ? GROUP BY p.id_pemesanan ORDER BY perj.tgl_berangkat DESC";
    $stmt_orders = $pdo->prepare($sql_orders);
    $stmt_orders->execute([$customer_id]);
    $orders = $stmt_orders->fetchAll();

    // C. Ambil data keluhan (complaints)
    $stmt_complaints = $pdo->prepare("SELECT * FROM keluhan WHERE id_customer = ? ORDER BY id_keluhan DESC");
    $stmt_complaints->execute([$customer_id]);
    $complaints = $stmt_complaints->fetchAll();

} catch (PDOException $e) {
    // Jika ada error saat mengambil data, tampilkan pesan
    $page_error = "Terjadi kesalahan saat memuat data akun Anda.";
    error_log($e->getMessage());
}

// Sertakan header
include 'includes/header.php';
?>

<div class="container my-5">
    <h2 class="mb-4">Akun Saya</h2>

    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4 pb-4 border-bottom">
                        <div class="mb-3">
                            <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mb-1"><?php echo htmlspecialchars(getUserName($pdo)); ?></h5>
                        <small class="text-muted"><?php echo htmlspecialchars($_SESSION['user_email']); ?></small>
                    </div>

                    <nav class="nav flex-column">
                        <a class="nav-link <?php echo $active_tab === 'orders' ? 'active' : ''; ?>" href="my-account.php?tab=orders">
                            <i class="bi bi-ticket-perforated"></i> Pesanan Saya
                        </a>
                        <a class="nav-link <?php echo $active_tab === 'profile' ? 'active' : ''; ?>" href="my-account.php?tab=profile">
                            <i class="bi bi-person"></i> Profil
                        </a>
                        <a class="nav-link <?php echo $active_tab === 'help' ? 'active' : ''; ?>" href="my-account.php?tab=help">
                            <i class="bi bi-chat-dots"></i> Bantuan/Keluhan
                        </a>
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if ($active_tab === 'orders'): ?>
                <div class="card">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> Pesanan Saya</h5></div>
                    <div class="card-body">
                        <?php if (empty($orders)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 text-muted">Belum Ada Pesanan</h5>
                                <a href="index.php#search-widget" class="btn btn-primary mt-3"><i class="bi bi-search"></i> Pesan Tiket Sekarang</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <div class="card mb-3 border">
                                <div class="card-body">
                                    <h6 class="mb-0">Nomor Pesanan: <?php echo htmlspecialchars($order['id_pemesanan']); ?></h6>
                                    </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($active_tab === 'profile'): ?>
                <div class="card">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-person"></i> Profil Saya</h5></div>
                    <div class="card-body">
                        <form method="POST" action="my-account.php?tab=profile">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="row mb-3">
                                <div class="col-md-6"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['nama']); ?>"></div>
                                <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" disabled></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6"><label class="form-label">No. Telepon</label><input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['no_telp']); ?>"></div>
                                <div class="col-md-6"><label class="form-label">Jenis Kelamin</label>
                                    <select name="gender" class="form-select">
                                        <option value="L" <?php echo ($user['jenis_kelamin'] ?? '') === 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="P" <?php echo ($user['jenis_kelamin'] ?? '') === 'P' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea></div>
                            <div class="text-end"><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Perubahan</button></div>
                        </form>
                    </div>
                </div>

            <?php elseif ($active_tab === 'help'): ?>
                <div class="card">
                    <div class="card-header bg-white"><h5 class="mb-0"><i class="bi bi-chat-dots"></i> Bantuan & Keluhan</h5></div>
                    <div class="card-body">
                        <div class="card bg-light mb-4"><div class="card-body">
                            <h6 class="mb-3">Ajukan Keluhan Baru</h6>
                            <form method="POST" action="my-account.php?tab=help">
                                <input type="hidden" name="submit_complaint" value="1">
                                <div class="mb-3"><label class="form-label">Deskripsi Keluhan</label><textarea name="description" class="form-control" rows="4" placeholder="Jelaskan keluhan Anda..." required></textarea></div>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Kirim Keluhan</button>
                            </form>
                        </div></div>
                        <h6 class="mb-3">Riwayat Keluhan</h6>
                        <?php if (empty($complaints)): ?>
                            <p class="text-muted">Anda belum memiliki riwayat keluhan.</p>
                        <?php else: ?>
                            <?php foreach ($complaints as $complaint): ?>
                            <div class="card mb-3"><div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">Keluhan #<?php echo htmlspecialchars($complaint['id_keluhan']); ?></h6>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($complaint['status_penanganan']); ?></span>
                                </div>
                                <p class="mb-0"><?php echo htmlspecialchars($complaint['deskripsi']); ?></p>
                            </div></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
 .nav-link { color: #374151; padding: 0.75rem 1rem; border-radius: 0.375rem; margin-bottom: 0.25rem; transition: all 0.3s; }
 .nav-link:hover { background-color: #f3f4f6; color: #2563eb; }
 .nav-link.active { background-color: #ffffffff; color: white; }
 .nav-link i { width: 20px; margin-right: 0.5rem; }
</style>

<?php include 'includes/footer.php'; ?>