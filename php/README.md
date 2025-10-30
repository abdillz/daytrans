# DayTrans - Platform Pemesanan Tiket Bus (PHP Version)

Platform web resmi untuk PO Bus DayTrans yang dibangun dengan **PHP Terstruktur** dan **Bootstrap 5**.

## 📋 Deskripsi

Aplikasi web pemesanan tiket bus online yang lengkap dengan fitur-fitur:
- Pencarian jadwal bus
- Pemilihan kursi interaktif
- Multiple metode pembayaran
- E-Ticket dengan QR Code
- Dashboard akun pengguna
- Sistem bantuan dan keluhan

## 🗂️ Struktur File

```
php/
├── config/
│   └── config.php              # Konfigurasi & Mock Data
├── includes/
│   ├── header.php              # Header & Navigation
│   └── footer.php              # Footer
├── index.php                   # Homepage
├── search.php                  # Hasil Pencarian
├── seat-selection.php          # Pemilihan Kursi
├── payment.php                 # Pembayaran
├── confirmation.php            # Konfirmasi & E-Ticket
├── my-account.php              # Dashboard Akun
├── help.php                    # Halaman Bantuan
├── about.php                   # Tentang Kami
├── login.php                   # Login
├── logout.php                  # Logout
├── register.php                # Registrasi
└── README.md                   # Dokumentasi
```

## 🚀 Cara Instalasi

### 1. Requirements
- PHP 7.4 atau lebih tinggi
- Web Server (Apache/Nginx)
- XAMPP/WAMP/LARAGON (untuk development lokal)

### 2. Instalasi Lokal

1. **Download & Extract**
   - Download folder `/php` ini
   - Extract ke direktori htdocs (XAMPP) atau www (WAMP/LARAGON)

2. **Akses Aplikasi**
   ```
   http://localhost/php/index.php
   ```

3. **Struktur URL**
   - Homepage: `http://localhost/php/index.php`
   - Pencarian: `http://localhost/php/search.php`
   - Login: `http://localhost/php/login.php`
   - Akun: `http://localhost/php/my-account.php`

## 🎨 Framework & Library

- **PHP**: Versi 7.4+
- **Bootstrap**: v5.3.0 (via CDN)
- **Bootstrap Icons**: v1.11.0 (via CDN)

## 📝 Fitur Utama

### 1. **Homepage** (`index.php`)
- Hero section dengan tagline
- Widget pencarian tiket
- Fitur unggulan
- Rute populer

### 2. **Pencarian** (`search.php`)
- Hasil pencarian jadwal bus
- Filter (tipe bus, waktu, harga)
- Sorting hasil
- Detail jadwal lengkap

### 3. **Pemilihan Kursi** (`seat-selection.php`)
- Denah kursi bus interaktif
- Visual kursi tersedia/terisi
- Form data penumpang
- Ringkasan pemesanan

### 4. **Pembayaran** (`payment.php`)
- Multiple metode pembayaran:
  - Virtual Account (BCA, Mandiri, BNI, BRI)
  - E-Wallet (GoPay, OVO, DANA, ShopeePay)
  - Kartu Kredit/Debit
  - Bayar di Terminal
- Ringkasan pesanan

### 5. **Konfirmasi** (`confirmation.php`)
- E-Ticket digital
- QR Code
- Detail perjalanan lengkap
- Download PDF / Print
- Kirim email

### 6. **Dashboard Akun** (`my-account.php`)
- Pesanan Saya (Aktif, Selesai, Dibatalkan)
- Profil pengguna
- Bantuan/Keluhan
- Pengaturan akun

### 7. **Halaman Lainnya**
- **Bantuan** (`help.php`): FAQ, Contact, Tutorial
- **Tentang Kami** (`about.php`): Profil perusahaan, visi-misi, armada
- **Login/Register** (`login.php`, `register.php`): Autentikasi

## 🔧 Mock Data

Aplikasi ini menggunakan **mock data** (data dummy) untuk simulasi:

### Daftar Kota
```php
Jakarta, Bandung, Surabaya, Yogyakarta, Semarang, Malang, Solo, Denpasar
```

### Jadwal Bus
- 5 jadwal per rute
- Tipe bus: Executive, VIP, Sleeper
- Harga: Rp 150.000 - Rp 250.000

### Mock Orders
- 3 pesanan dummy untuk dashboard

### Session Management
- Login menggunakan PHP Session
- Auto-login untuk demo

## 🎯 Cara Penggunaan

### Flow Pemesanan Tiket:

1. **Homepage** → Pilih kota asal, tujuan, tanggal, jumlah penumpang
2. **Pencarian** → Pilih jadwal yang sesuai
3. **Pemilihan Kursi** → Pilih kursi & isi data penumpang
4. **Pembayaran** → Pilih metode pembayaran
5. **Konfirmasi** → Dapatkan E-Ticket

### Login Demo:
```
Email: apapun@example.com
Password: apapun (minimal 1 karakter)
```
*Mock authentication - akan langsung login*

## 🔐 Catatan Keamanan

⚠️ **PENTING**: Aplikasi ini adalah **prototype/mockup** untuk keperluan desain dan demo.

**Untuk Production:**
1. Implementasi database (MySQL/PostgreSQL)
2. Password hashing (bcrypt/argon2)
3. Input validation & sanitization
4. CSRF protection
5. SQL injection prevention
6. XSS protection
7. Secure session handling
8. HTTPS/SSL
9. Payment gateway integration
10. Email service integration

## 📱 Responsive Design

Aplikasi ini **fully responsive** dan optimized untuk:
- Desktop (1920px+)
- Tablet (768px - 1024px)
- Mobile (320px - 767px)

## 🎨 Customization

### Mengubah Warna Brand:
Edit file `includes/header.php` bagian `<style>`:
```css
:root {
    --primary-color: #2563eb;      /* Warna utama */
    --primary-dark: #1e40af;       /* Warna hover */
    --primary-light: #3b82f6;      /* Warna light */
}
```

### Menambah Rute Baru:
Edit file `config/config.php`:
```php
$cities = [
    'Jakarta',
    'Bandung',
    // ... tambahkan kota baru di sini
];
```

## 🐛 Troubleshooting

### Session tidak berfungsi:
```php
// Pastikan di config.php baris pertama:
session_start();
```

### CSS tidak muncul:
```
Periksa koneksi internet (Bootstrap via CDN)
```

### Error 404:
```
Pastikan file ada di folder yang benar
Cek case-sensitive pada nama file (Linux)
```

## 📞 Support

Untuk bantuan lebih lanjut, hubungi:
- Email: developer@daytrans.co.id
- WhatsApp: +62 812 3456 7890

## 📄 License

Copyright © 2025 DayTrans. All rights reserved.

---

**Dibuat dengan ❤️ untuk PO Bus DayTrans**
