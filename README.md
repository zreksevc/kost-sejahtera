# 🏠 Kost Sejahtera

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)
![License](https://img.shields.io/badge/license-MIT-green)

**Kost Sejahtera** adalah aplikasi **Sistem Manajemen Kost berbasis Laravel 12** untuk membantu pemilik kost mengelola kamar, penghuni, pembayaran, dan laporan keuangan secara terpusat.

---

# 📸 Preview Aplikasi

[alt text](image.png)



---

# 📚 Daftar Isi

* [Fitur](#-fitur-lengkap-sistem)
* [Persyaratan Sistem](#-persyaratan-sistem)
* [Instalasi](#-langkah-instalasi)
* [Login Admin](#-akses-login-admin)
* [Struktur Project](#-struktur-file-penting)
* [Troubleshooting](#-troubleshooting)
* [Tech Stack](#-tech-stack-ringkasan)
* [License](#-license)

---

# 🎯 Fitur Lengkap Sistem

| Modul           | Fitur                                                           |
| --------------- | --------------------------------------------------------------- |
| 🌐 Landing Page | Profil kost, katalog kamar tersedia, fasilitas, kontak WhatsApp |
| 📊 Dashboard    | Statistik real-time, grafik keuangan, okupansi kamar            |
| 🚪 Kamar        | CRUD kamar + upload foto                                        |
| 👥 Penghuni     | CRUD penghuni + riwayat sewa                                    |
| 📋 Sewa         | Booking kamar + perpanjang sewa                                 |
| 💰 Pembayaran   | Riwayat transaksi + invoice PDF                                 |
| 📉 Pengeluaran  | Catat biaya operasional                                         |
| 📈 Laporan      | Grafik keuangan + export data                                   |

---

# ✅ Persyaratan Sistem

| Software             | Versi Minimum |
| -------------------- | ------------- |
| PHP                  | 8.2+          |
| Composer             | 2.x           |
| MySQL / MariaDB      | 8.0+          |
| Node.js *(opsional)* | 18+           |
| Laravel              | 12.x          |

---

# 🚀 Langkah Instalasi

## 1️⃣ Install PHP & Web Server

Disarankan menggunakan **Laragon** di Windows:

https://laragon.org/download/

---

## 2️⃣ Install Composer

Download installer:

https://getcomposer.org/download/

Verifikasi:

```bash
composer --version
```

---

## 3️⃣ Buat Project Laravel

```bash
composer create-project laravel/laravel kost-sejahtera "^12.0"
cd kost-sejahtera
```

Salin semua file project ke folder ini.

---

## 4️⃣ Install Dependency

```bash
composer install

composer require barryvdh/laravel-dompdf
composer require laravel/sanctum
```

---

## 5️⃣ Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit database di `.env`:

```
DB_DATABASE=kost_sejahtera
DB_USERNAME=root
DB_PASSWORD=
```

---

## 6️⃣ Buat Database

Via phpMyAdmin atau terminal:

```sql
CREATE DATABASE kost_sejahtera;
```

---

## 7️⃣ Jalankan Migrasi

```bash
php artisan migrate --seed
```

---

## 8️⃣ Storage Link

```bash
php artisan storage:link
```

---

## 9️⃣ Publish DomPDF

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 🔟 Jalankan Server

```bash
php artisan serve
```

Buka:

```
http://localhost:8000
```

---

# 🔐 Akses Login Admin

| Field    | Value                                                     |
| -------- | --------------------------------------------------------- |
| Email    | [admin@kostsejahtera.com](mailto:admin@kostsejahtera.com) |
| Password | admin123                                                  |

Login URL:

```
http://localhost:8000/login
```

---

# 📁 Struktur File Penting

```
app/
 ├── Http/Controllers
 │   ├── AuthController.php
 │   ├── DashboardController.php
 │   ├── RoomController.php
 │   ├── TenantController.php
 │   ├── RentalController.php
 │   ├── PaymentController.php
 │   ├── ExpenseController.php
 │   └── ReportController.php

database/
 ├── migrations
 └── seeders

resources/views/
 ├── layouts
 ├── auth
 ├── public
 └── admin
```

---

# ⚠️ Troubleshooting

### Error Key Laravel

```bash
php artisan key:generate
```

---

### Error Database

Periksa `.env`

```
DB_USERNAME
DB_PASSWORD
```

---

### DomPDF tidak ditemukan

```bash
composer require barryvdh/laravel-dompdf
```

---

### Storage tidak muncul

```bash
php artisan storage:link
```

---

# 🧰 Tech Stack Ringkasan

```
Backend    : Laravel 12
Frontend   : Bootstrap 5
Database   : MySQL
Auth       : Laravel Auth + Sanctum
PDF Export : DomPDF
Charts     : Chart.js
Icons      : Bootstrap Icons
Fonts      : Google Fonts
```

---

# 📄 License

Project ini menggunakan lisensi **MIT License**.

---

# ⭐ Dukungan

Jika project ini membantu Anda, jangan lupa **beri ⭐ di repository GitHub**.
