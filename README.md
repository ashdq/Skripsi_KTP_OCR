# 🏛️ Sistem Administrasi Kelurahan dengan OCR

<div align="center">

![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)
![OCR](https://img.shields.io/badge/OCR-Tesseract-success?style=flat-square)
![Status](https://img.shields.io/badge/Status-Development-yellow?style=flat-square)

> **Sistem informasi terdesentralisasi untuk mengelola data dan surat keterangan di tingkat kelurahan dengan teknologi OCR untuk scanning KTP dan KK secara otomatis**

[🚀 Mulai](#-quick-start) • [📖 Dokumentasi](#-dokumentasi) • [🎯 Fitur](#-fitur-utama) • [🛠️ Tech Stack](#-tech-stack)

</div>

---

## 📑 Daftar Isi

- [Tentang Project](#-tentang-project)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Struktur Project](#-struktur-project)
- [API Routes](#-api-routes)
- [Troubleshooting](#-troubleshooting)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## 🎯 Tentang Project

**Sistem Administrasi Kelurahan** adalah aplikasi web modern yang dirancang untuk memudahkan pengelolaan data penduduk dan pengajuan surat-surat keterangan di tingkat kelurahan. Keunggulan utama sistem ini adalah implementasi **teknologi OCR (Optical Character Recognition)** yang memungkinkan scanning dan ekstraksi data otomatis dari dokumen identitas warga.

### 🔍 Teknologi Utama: OCR

Sistem menggunakan **Tesseract OCR Engine** untuk mengekstrak data dari:
- **KTP (Kartu Tanda Penduduk)** - Nomor KTP, Nama, TTL, Alamat
- **KK (Kartu Keluarga)** - Nomor KK, Data kepala keluarga, Jumlah anggota

Dengan fitur OCR ini, warga hanya perlu mengunggah foto dokumen yang akan secara otomatis dipindai dan data-datanya diisi ke dalam sistem.

---

## ✨ Fitur Utama

### 📋 Untuk Warga

| Fitur | Deskripsi | Status |
|-------|-----------|--------|
| 🏠 **Beranda** | Dashboard dengan statistik pengajuan dan aktivitas terkini | ✅ |
| 📸 **Scan KTP/KK** | Upload foto KTP/KK untuk ekstraksi data otomatis via OCR | 🔄 |
| 📝 **Pengurusan Surat** | Form pengajuan surat dengan pre-filled data dari OCR | 🔄 |
| 📥 **Unduh Surat** | Download surat yang telah selesai diproses | ✅ |
| 📊 **Riwayat** | Lihat histori pengajuan dan status tracking | ✅ |
| 🔔 **Notifikasi** | Real-time notification untuk update status | 🔄 |

### 👔 Untuk Petugas

| Fitur | Deskripsi | Status |
|-------|-----------|--------|
| 🏠 **Beranda** | Dashboard dengan statistik pengajuan dan analytics | ✅ |
| 📋 **Daftar Pengajuan** | Manajemen semua pengajuan dengan filter & search | ✅ |
| ✍️ **Verifikasi & Tanda Tangan** | Mengecek data OCR dan tanda tangan dokumen | ✅ |
| 👥 **Manajemen Warga** | CRUD data warga termasuk data hasil OCR | 🔄 |
| 📊 **Laporan** | Laporan periodik dan analisis data warga | 🔄 |
| 🔐 **Audit Trail** | Log semua aktivitas untuk keperluan audit | 🔄 |

### 🤖 Fitur OCR

| Fitur | Detail |
|-------|--------|
| 🖼️ **Multi-Format Support** | JPG, PNG, PDF, TIFF |
| 🔄 **Batch Processing** | Proses multiple dokumen sekaligus |
| 🎯 **High Accuracy** | Deteksi informasi dengan akurasi tinggi |
| 🔍 **Data Validation** | Validasi otomatis hasil OCR |
| 📋 **Template Matching** | Format KTP/KK terstruktur & konsisten |
| ⚡ **Real-time Processing** | Hasil scanning langsung tersedia |

---

## 🛠️ Tech Stack

### 🖥️ Backend

```
├─ PHP 8.2+
├─ Laravel 12
├─ MySQL / SQLite
├─ Tesseract OCR
├─ Composer
└─ RESTful API
```

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **PHP** | 8.2+ | Server-side programming |
| **Laravel** | 12.0+ | Web framework & routing |
| **Tesseract OCR** | 5.0+ | Optical character recognition |
| **MySQL** | 8.0+ | Database relational |
| **Composer** | 2.0+ | PHP dependency manager |

### 🎨 Frontend

```
├─ HTML5 / CSS3
├─ Blade Template
├─ Vite 7
├─ TailwindCSS 4
├─ Font Awesome 6
└─ JavaScript ES6+
```

| Teknologi | Versi | Fungsi |
|-----------|-------|--------|
| **Vite** | 7.0+ | Asset bundler & build tool |
| **TailwindCSS** | 4.0+ | Utility-first CSS framework |
| **Blade** | 12.0+ | Laravel templating engine |
| **Font Awesome** | 6.4+ | Icon library |
| **npm** | 10.0+ | Node package manager |

### 📦 OCR & Libraries

- 📌 **Tesseract PHP** - PHP wrapper untuk Tesseract OCR
- 🖼️ **OpenCV** (optional) - Computer vision untuk preprocessing
- 📄 **FPDF** - PDF generation untuk surat
- 📨 **Laravel Mail** - Email notifications
- 🔐 **Laravel Authenticator** - Multi-role authentication

---

## 💾 Persyaratan Sistem

### Minimum Requirements
```
OS        : Windows/Linux/MacOS
PHP       : 8.2 atau lebih tinggi
MySQL     : 8.0 atau lebih tinggi
RAM       : 2 GB minimum
Storage   : 500 MB
```

### Recommended Requirements
```
OS        : Linux (Ubuntu 20.04+)
PHP       : 8.3
MySQL     : 8.0.35+
RAM       : 4 GB
Storage   : 2 GB
Disk Type : SSD
```

### Software yang Harus Diinstall

- ✅ **PHP 8.2+** dengan extensions: curl, json, gd, mbstring, zip
- ✅ **Composer** - PHP dependency manager
- ✅ **MySQL Server** - Database server
- ✅ **Tesseract OCR** - OCR engine
- ✅ **Node.js & npm** - Frontend build tools
- ✅ **Git** - Version control

---

## 🚀 Quick Start

### 1️⃣ Clone Repository

```bash
git clone https://github.com/yourusername/sia_kelurahan.git
cd sia_kelurahan
```

### 2️⃣ Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Install Tesseract OCR
# Windows: Download dari https://github.com/UB-Mannheim/tesseract/wiki
# Ubuntu/Debian:
sudo apt-get install tesseract-ocr tesseract-ocr-ind

# macOS:
brew install tesseract
```

### 3️⃣ Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file dan sesuaikan konfigurasi database
nano .env
```

### 4️⃣ Database Setup

```bash
# Jalankan migrations
php artisan migrate

# Seed database (opsional)
php artisan db:seed

# Generate testing data
php artisan db:seed --class=TestDataSeeder
```

### 5️⃣ Build Assets

```bash
# Development build (watch mode)
npm run dev

# Production build
npm run build
```

### 6️⃣ Jalankan Server

```bash
# Terminal 1: Laravel Development Server
php artisan serve

# Server akan jalan di: http://localhost:8000
```

✅ **Done!** Aplikasi siap diakses di `http://localhost:8000`

---

## ⚙️ Konfigurasi

### 📋 File Konfigurasi Penting

#### `.env` - Environment Variables
```env
APP_NAME="Sistem Administrasi Kelurahan"
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sia_kelurahan
DB_USERNAME=root
DB_PASSWORD=

# OCR Configuration
OCR_ENGINE=tesseract
OCR_LANGUAGE=ind
OCR_TEMP_PATH=/tmp/ocr
OCR_TIMEOUT=30

# Mail Configuration (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxxxx
MAIL_PASSWORD=xxxxx
```

#### `config/ocr.php` - Konfigurasi OCR
```php
return [
    'engine' => env('OCR_ENGINE', 'tesseract'),
    'language' => env('OCR_LANGUAGE', 'ind'),
    'timeout' => env('OCR_TIMEOUT', 30),
    'temp_path' => env('OCR_TEMP_PATH', '/tmp/ocr'),
    'tesseract' => [
        'path' => '/usr/bin/tesseract', // Path ke tesseract binary
        'preset' => '--psm 6', // Tesseract config
    ],
];
```

---

## 📖 Panduan Penggunaan

### 👥 Untuk Warga

#### 🔐 Login Pertama Kali
1. Buka aplikasi di `http://localhost:8000`
2. Klik **"Masuk"** dan pilih **"Login Warga"**
3. Masukkan **NIK** dan **Password**
4. Klik **"Masuk"**

#### 📸 Cara Menggunakan Fitur OCR

**Step 1: Upload Dokumen**
```
Beranda → Scan KTP/KK → Pilih File
```

**Step 2: Proses OCR**
- Upload foto KTP atau KK
- Sistem akan secara otomatis memproses dengan OCR
- Tunggu beberapa detik hingga data terekstraksi

**Step 3: Verifikasi Data**
- Lihat hasil ekstraksi data dari OCR
- Periksa akurasi data yang terdeteksi
- Edit manual jika ada data yang salah

**Step 4: Simpan Data**
- Konfirmasi data yang sudah benar
- Klik **"Simpan"** untuk menyimpan ke database
- Data siap digunakan untuk pengajuan surat

#### 📝 Mengajukan Surat

1. Dari sidebar, klik **"Pengurusan Surat"**
2. Pilih **Jenis Surat** dari dropdown:
   - Administrasi Kependudukan
   - Umum
3. Form akan **pre-filled** dengan data dari OCR
4. Edit jika ada yang perlu disesuaikan
5. Upload dokumen pendukung jika diperlukan
6. Klik **"Kirim Pengajuan"**
7. Tunggu verifikasi dari petugas

#### 📥 Mengunduh Surat
1. Klik **"Unduh Surat"** di sidebar
2. Lihat daftar surat yang **sudah selesai**
3. Klik tombol **"Unduh"** pada surat pilihan
4. File PDF akan otomatis tersimpan

### 👔 Untuk Petugas

#### 📋 Mengakses Daftar Pengajuan
1. Login dengan akun petugas
2. Dari sidebar, klik **"Daftar Pengajuan"**
3. Lihat tabel semua pengajuan dari warga

#### ✅ Memverifikasi Pengajuan

**Step 1: Buka Detail Pengajuan**
- Klik tombol **"Lihat Detail"** pada pengajuan

**Step 2: Cek Data & OCR**
```
┌─ Data dari OCR
│  ├─ NIK: [terdeteksi otomatis]
│  ├─ Nama: [terdeteksi otomatis]
│  ├─ Alamat: [terdeteksi otomatis]
│  └─ [Edit jika ada kesalahan]
└─ Dokumen Pendukung
   ├─ Lihat upload dari warga
   └─ Verifikasi keaslian dokumen
```

**Step 3: Acc atau Reject**
- ✅ Klik **"Setujui"** untuk approve
- ❌ Klik **"Tolak"** jika ada yang tidak sesuai
- Tambahkan catatan jika diperlukan

#### ✍️ Tanda Tangan Dokumen
1. Klik **"Tanda Tangan"** di sidebar
2. Lihat daftar surat yang menunggu tandatangan
3. Review dokumen satu per satu
4. Klik **"Tanda Tangan"** untuk menandatangani
5. Masukkan PIN digital (jika ada) untuk konfirmasi

---

## 📁 Struktur Project

```
sia_kelurahan/
│
├── 📦 app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Warga/
│   │   │   │   ├── WargaController.php
│   │   │   │   ├── OcrController.php           ← OCR Logic
│   │   │   │   └── PengurusanController.php
│   │   │   ├── Petugas/
│   │   │   │   ├── PetugasController.php
│   │   │   │   ├── VerifikasiController.php
│   │   │   │   └── TandaTanganController.php
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Warga.php
│   │   ├── Pengajuan.php
│   │   ├── OcrResult.php                      ← OCR Results
│   │   ├── Surat.php
│   │   └── Verifikasi.php
│   │
│   └── Services/
│       ├── OcrService.php                     ← OCR Service
│       ├── TesseractService.php               ← Tesseract Wrapper
│       └── DataExtractionService.php
│
├── 📋 resources/
│   ├── views/
│   │   ├── layout/
│   │   │   ├── header.blade.php
│   │   │   ├── sidebar.blade.php
│   │   │   ├── petugas-sidebar.blade.php
│   │   │   └── footer.blade.php
│   │   │
│   │   ├── login.blade.php
│   │   │
│   │   ├── warga/
│   │   │   ├── home.blade.php
│   │   │   ├── scan-ktp-kk.blade.php          ← "OCR Halaman"
│   │   │   ├── pengurusan.blade.php
│   │   │   └── unduh.blade.php
│   │   │
│   │   └── petugas/
│   │       ├── home.blade.php
│   │       ├── daftar-pengajuan.blade.php
│   │       ├── verifikasi.blade.php           ← Verifikasi OCR
│   │       └── tanda-tangan.blade.php
│   │
│   ├── css/
│   │   └── app.css                            # Styling komprehensif
│   │
│   └── js/
│       ├── app.js
│       └── ocr-handler.js                     ← OCR Frontend
│
├── 🛣️ routes/
│   ├── web.php                                # Web routes
│   ├── api.php                                # API routes untuk OCR
│   └── console.php
│
├── 💾 database/
│   ├── migrations/
│   │   ├── ...
│   │   └── create_ocr_results_table.php       ← Migration untuk OCR
│   │
│   ├── factories/
│   └── seeders/
│
├── 📦 vendor/                                 # Composer dependencies
├── ⚙️ config/
│   ├── app.php
│   ├── database.php
│   ├── ocr.php                                ← OCR Config
│   └── ...
│
├── 🗂️ storage/
│   ├── app/
│   │   ├── public/
│   │   └── uploads/
│   │       ├── ktp/                           ← Upload KTP
│   │       ├── kk/                            ← Upload KK
│   │       └── ocr-temp/                      ← Temp OCR files
│   │
│   ├── logs/
│   └── framework/
│
├── package.json
├── vite.config.js
├── composer.json
├── .env
├── .env.example
│
└── 📄 README.md                               ← File ini
```

---

## 🛣️ API Routes

### Authentication
```
POST   /api/auth/login               # Login warga/petugas
POST   /api/auth/logout              # Logout
POST   /api/auth/refresh             # Refresh token
GET    /api/auth/me                  # Get current user
```

### OCR Endpoints
```
POST   /api/ocr/scan-ktp             # Scan KTP
POST   /api/ocr/scan-kk              # Scan KK
POST   /api/ocr/verify               # Verifikasi hasil OCR
GET    /api/ocr/result/{id}          # Get OCR result
GET    /api/ocr/results              # List OCR results
POST   /api/ocr/batch-process        # Batch processing
```

### Warga Routes
```
GET    /api/warga/dashboard          # Dashboard data
POST   /api/warga/pengajuan          # Create pengajuan
GET    /api/warga/pengajuan          # List pengajuan
GET    /api/warga/pengajuan/{id}     # Get detail pengajuan
PUT    /api/warga/pengajuan/{id}     # Update pengajuan
GET    /api/warga/surat              # List surat tersedia
GET    /api/warga/surat/download/{id} # Download surat
```

### Petugas Routes
```
GET    /api/petugas/dashboard        # Dashboard data
GET    /api/petugas/pengajuan        # List semua pengajuan
GET    /api/petugas/pengajuan/{id}   # Detail pengajuan
PUT    /api/petugas/pengajuan/{id}/verifikasi  # Verifikasi
POST   /api/petugas/tanda-tangan      # Tanda tangan
GET    /api/petugas/warga             # Manajemen warga
```

---

## 🐛 Troubleshooting

### ❌ Error: "Tesseract not found"

**Solusi:**
```bash
# Pada Windows - Install dari:
# https://github.com/UB-Mannheim/tesseract/wiki

# Pada Ubuntu/Debian:
sudo apt-get install tesseract-ocr tesseract-ocr-ind

# Set path di .env:
TESSERACT_PATH=/usr/bin/tesseract
```

### ❌ Error: "VITE Manifest Not Found"

**Solusi:**
```bash
# Rebuild assets
npm run build

# Atau clear cache
npm run dev
```

### ❌ Error: "Database Connection Refused"

**Solusi:**
```bash
# Cek koneksi MySQL
mysql -h localhost -u root -p

# Buat database
CREATE DATABASE sia_kelurahan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Update .env
DB_DATABASE=sia_kelurahan
DB_USERNAME=root
DB_PASSWORD=

# Jalankan migration
php artisan migrate
```

### ⚠️ OCR Accuracy Rendah

**Solusi:**
1. **Pastikan gambar KTP/KK berkualitas tinggi**
   - Resolusi minimal 300 DPI
   - Pencahayaan cukup
   - Tidak ada blur atau kerusakan

2. **Preprocess gambar**
```bash
# Install ImageMagick untuk preprocessing
sudo apt-get install imagemagick

# Enable di config/ocr.php
'enable_preprocessing' => true,
```

3. **Tambah bahasa Indonesia**
```bash
# Download data bahasa Indonesia untuk Tesseract
sudo apt-get install tesseract-ocr-ind

# Update .env:
OCR_LANGUAGE=ind+eng
```

### 🔴 Upload File Gagal

**Solusi:**
```bash
# Check directory permissions
sudo chmod -R 755 storage/app/uploads

# Create directories if not exist
mkdir -p storage/app/uploads/{ktp,kk,ocr-temp}
```

---

## 👥 Kontribusi

Kami menyambut kontribusi dari komunitas! Silakan ikuti langkah-langkah berikut:

### 1. Fork Repository
```bash
git clone https://github.com/yourusername/sia_kelurahan.git
cd sia_kelurahan
```

### 2. Buat Branch Feature
```bash
git checkout -b feature/AmazingFeature
```

### 3. Commit Changes
```bash
git add .
git commit -m 'Add some AmazingFeature'
```

### 4. Push ke Branch
```bash
git push origin feature/AmazingFeature
```

### 5. Buat Pull Request
- Jelaskan perubahan yang dilakukan
- Tautkan ke issue yang relevan
- Pastikan semua test lolos

---

## 📄 Lisensi

Project ini dilisensikan di bawah **MIT License**.

---

## 👨‍🎓 Informasi Penulis

<div align="center">

### Sistem Administrasi Kelurahan dengan OCR

**Tugas Akhir (Skripsi) - Politeknik Negeri Malang (Polinema)**

📚 Program Studi: Teknologi Informasi/Teknik Informatika  
📅 Tahun: 2026  
🏢 Institusi: Polinema

**Dosen Pembimbing:**
- [Nama Dosen 1]
- [Nama Dosen 2]

**Penulis:**
- Nama Anda

---

### 🙏 Terima Kasih Kepada

- Pembimbing akademik atas bimbingannya
- Komunitas Laravel Indonesia
- Tesseract OCR Project
- Semua yang telah berkontribusi

</div>

---

## 📞 Support & Documentation

- 📖 **Full Documentation:** [Link Dokumentasi](https://docs.example.com)
- 🐛 **Report Issues:** [GitHub Issues](https://github.com/yourusername/sia_kelurahan/issues)
- 💬 **Diskusi:** [GitHub Discussions](https://github.com/yourusername/sia_kelurahan/discussions)
- 📧 **Email:** [your.email@example.com]

---

## 🌟 Showcase

### Demo Screenshot

```
Login Page
├── 📱 Responsive design
├── 🔐 Multi-role authentication
└── ✨ Modern interface

Warga Dashboard
├── 📊 Statistik pengajuan
├── 📸 OCR scan interface
├── 📝 Form pengurusan
└── 📥 Download surat

Petugas Dashboard
├── 📋 Daftar pengajuan
├── ✅ Verifikasi & approval
├── ✍️ Tanda tangan digital
└── 📊 Analytics & reports
```

---

<div align="center">

## 🚀 Terima Kasih Telah Berkunjung!

⭐ Jika project ini bermanfaat, jangan lupa beri bintang di GitHub!

**Made with ❤️ using Laravel, Vite & Tesseract OCR**

[![GitHub followers](https://img.shields.io/github/followers/yourusername?style=social)](https://github.com/yourusername)
[![Twitter Follow](https://img.shields.io/twitter/follow/yourhandle?style=social)](https://twitter.com/yourhandle)

Last Updated: April 2, 2026

</div>
