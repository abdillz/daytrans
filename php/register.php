<?php
// 1. Sertakan config.php
// Ini akan memulai session dan memberi kita variabel $pdo untuk koneksi database.
require_once 'config/config.php';

// Jika pengguna sudah login, langsung arahkan ke index.php
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Inisialisasi variabel error
$error = '';

// 2. Proses form HANYA jika method-nya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // 3. Validasi Input
    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } else {
        // Validasi dasar lolos, sekarang cek ke database
        try {
            // 4. Cek apakah email sudah ada di tabel 'akun'
            $stmt = $pdo->prepare("SELECT id_akun FROM akun WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                // Email sudah ada
                $error = 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.';
            } else {
                // Email unik dan aman, kita bisa daftarkan pengguna
                
                // 5. Hash password (SANGAT PENTING!)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // 6. Mulai Transaksi Database
                // Kita perlu insert ke 2 tabel (akun & customer).
                // Transaksi memastikan jika salah satu gagal, semua akan dibatalkan.
                $pdo->beginTransaction();
                
                // 7. Insert ke tabel 'akun'
                $sql_akun = "INSERT INTO akun (email, password, peran) VALUES (?, ?, 'customer')";
                $stmt_akun = $pdo->prepare($sql_akun);
                $stmt_akun->execute([$email, $hashed_password]);
                
                // Ambil ID dari akun yang baru saja dibuat
                $new_akun_id = $pdo->lastInsertId();
                
                // 8. Insert ke tabel 'customer'
                // Kita asumsikan 'jenis_kelamin' dan 'alamat' boleh NULL
                $sql_customer = "INSERT INTO customer (id_akun, nama, no_telp) VALUES (?, ?, ?)";
                $stmt_customer = $pdo->prepare($sql_customer);
                $stmt_customer->execute([$new_akun_id, $name, $phone]);

                // Ambil ID dari customer yang baru saja dibuat
                $new_customer_id = $pdo->lastInsertId();

                // 9. Selesaikan Transaksi
                $pdo->commit();

                // 10. Registrasi Berhasil - Langsung loginkan pengguna
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $new_akun_id; // id_akun
                $_SESSION['customer_id'] = $new_customer_id; // id_customer
                $_SESSION['user_role'] = 'customer';
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_phone'] = $phone;
                
                // 11. Arahkan ke halaman utama
                header('Location: index.php');
                exit;
            }

        } catch (PDOException $e) {
            // Jika ada error selama transaksi, batalkan (rollback)
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = 'Terjadi kesalahan pada sistem. Registrasi gagal.';
            // Untuk debugging:
            // error_log("Register error: " . $e->getMessage());
        }
    }
}

// Sertakan header.php SETELAH semua logika PHP
include 'includes/header.php';
?>

<!-- Bagian HTML (Form Register) - Ini tidak perlu diubah -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Buat Akun Baru</h3>
                        <p class="text-muted">Daftar untuk mulai memesan tiket</p>
                    </div>

                    
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+62 812 3456 7890" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a href="#">Syarat & Ketentuan</a>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-person-plus"></i> Daftar
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-3">Atau daftar dengan</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-danger">
                                <i class="bi bi-google"></i> Google
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="bi bi-facebook"></i> Facebook
                            </button>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">
                            Sudah punya akun? <a href="login.php" class="text-decoration-none">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>