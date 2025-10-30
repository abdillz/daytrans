<?php
/**
 * File Konfigurasi dan Koneksi Database Utama
 * Untuk Aplikasi DayTrans
 */

// 1. Memulai Session
// Session digunakan untuk menyimpan data pengguna, seperti status login.
session_start();

// 2. Pengaturan Dasar
// Base URL untuk memudahkan pembuatan link di seluruh aplikasi.
define('BASE_URL', 'http://localhost/daytrans'); // Sesuaikan jika nama folder Anda berbeda

// ======================================================
// 3. PENGATURAN KONEKSI DATABASE
// ======================================================

// Sesuaikan detail ini dengan konfigurasi server database Anda.
$db_host = 'localhost';     // Biasanya 'localhost' atau '127.0.0.1'
$db_name = 'rsi';   // Nama database yang Anda buat
$db_user = 'root';          // Username database (default di XAMPP)
$db_pass = 'password@123';              // Password database (default di XAMPP kosong)
$charset = 'utf8mb4';       // Charset untuk mendukung karakter unicode

// Opsi untuk koneksi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Mengaktifkan mode error exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Mengatur mode fetch default ke array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Menonaktifkan emulasi prepared statements untuk keamanan
];

// Data Source Name (DSN)
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

try {
    // Membuat objek koneksi PDO
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (\PDOException $e) {
    // Jika koneksi gagal, hentikan script dan tampilkan pesan error yang mudah dipahami.
    // Pada mode produksi, sebaiknya error ini dicatat ke log, bukan ditampilkan ke pengguna.
    error_log("Koneksi ke database gagal: " . $e->getMessage());
    die("Tidak dapat terhubung ke database. Silakan coba lagi nanti.");
}

$cities = [
    'Jakarta',
    'Bandung',
    'Surabaya',
    'Yogyakarta',
    'Semarang',
    'Malang',
    'Solo',
    'Denpasar'
];
function formatRupiah($number) {
    if (!is_numeric($number)) {
        return 'Rp 0';
    }
    return 'Rp ' . number_format($number, 0, ',', '.');
}

/**
 * Memeriksa apakah pengguna sudah login.
 * @return bool True jika sudah login, false jika belum.
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Mendapatkan nama pengguna yang sedang login.
 * @return string Nama pengguna atau 'Guest' jika tidak ada yang login.
 */
function getUserName($pdo) {
    // Periksa dulu apakah pengguna sudah login dan ada customer_id di session
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['customer_id'])) {
        try {
            // Siapkan query untuk mengambil 'nama' dari tabel 'customer'
            $stmt = $pdo->prepare("SELECT nama FROM customer WHERE id_customer = ?");
            $stmt->execute([$_SESSION['customer_id']]);
            $user = $stmt->fetch();
            
            // Jika pengguna ditemukan, kembalikan namanya.
            // Jika tidak (misal, akun dihapus), kembalikan 'Guest'.
            if ($user) {
                return $user['nama'];
            }
        } catch (PDOException $e) {
            // Jika ada error database, catat dan kembalikan 'Guest'.
            error_log($e->getMessage());
            return 'Guest';
        }
    }
    
    // Jika tidak login, kembalikan 'Guest'
    return 'Guest';
}
// Anda bisa menambahkan lebih banyak fungsi di sini sesuai kebutuhan,
// misalnya fungsi untuk memeriksa peran pengguna (isAdmin, isCustomer, dll.)

?>
