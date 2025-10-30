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
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input dasar
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi.';
    } else {
        // Input sudah diisi, sekarang kita cek ke database
        try {
            // 3. Query untuk mengambil data akun DAN customer sekaligus (JOIN)
            // Kita asumsikan yang login di sini adalah 'customer'
            $sql = "SELECT 
                        a.id_akun, a.email, a.password, a.peran, 
                        c.id_customer, c.nama, c.no_telp
                    FROM 
                        akun a
                    LEFT JOIN 
                        customer c ON a.id_akun = c.id_akun
                    WHERE 
                        a.email = ? AND a.peran = 'customer'";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // 4. Verifikasi pengguna dan password
            // Cek apakah user ditemukan DAN password-nya cocok
            if ($user && password_verify($password, $user['password'])) {
                
                // 5. Autentikasi BERHASIL! Simpan data ke SESSION
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id_akun']; // Ini id_akun
                $_SESSION['customer_id'] = $user['id_customer']; // Ini id_customer
                $_SESSION['user_role'] = $user['peran'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['no_telp'];
                
                // 6. Arahkan ke halaman yang dituju sebelumnya, atau ke index.php
                $redirect = $_GET['redirect'] ?? 'index.php';
                header('Location: ' . $redirect);
                exit;
                
            } else {
                // Autentikasi GAGAL (email tidak ada atau password salah)
                $error = 'Email atau password yang Anda masukkan salah.';
            }

        } catch (PDOException $e) {
            // Tangani jika ada error koneksi/query database
            $error = 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.';
            // Untuk debugging, Anda bisa mencatat errornya:
            // error_log("Login error: " . $e->getMessage());
        }
    }
}

// Sertakan header.php SETELAH semua logika PHP di atas
include 'includes/header.php';
?>

<!-- Bagian HTML (Form Login) - Ini tidak perlu diubah -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Masuk ke Akun Anda</h3>
                        <p class="text-muted">Silakan masuk untuk melanjutkan</p>
                    </div>

                    
                    <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="remember">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk
                        </button>

                        <div class="text-center">
                            <a href="#" class="text-decoration-none">Lupa password?</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-3">Atau masuk dengan</p>
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
                            Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>