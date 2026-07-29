# ✈️ Flight Voucher Assignment System

Aplikasi web modern untuk men-generate dan mengelola penugasan kursi voucher kru penerbangan secara otomatis dan bebas duplikasi. Dibangun menggunakan **Laravel 11** (Backend API) dan **React** (Inertia.js & Tailwind CSS).

---

## 🚀 Fitur Utama

- **Dynamic Aircraft Layout:** Pengaturan batas baris dan kursi berdasarkan tipe pesawat dari database.
- **Anti-Duplicate Seats:** Sistem pintar yang mencegah kursi ganda (baik dalam satu _generate_ maupun bentrok dengan data di database untuk penerbangan & tanggal yang sama).
- **Two-Step Action Workflow:** Validasi penerbangan terlebih dahulu sebelum tombol _generate voucher_ diaktifkan.
- **Modern Modal UI:** Tampilan pop-up hasil _generate_ kursi interaktif menggunakan Tailwind CSS.

---

## 🛠️ Persyaratan Sistem

Pastikan komputer Anda sudah terinstal:

- **PHP** (Versi 8.2 atau terbaru)
- **Composer**
- **Node.js & NPM**
- **SQLite** (Database bawaan Laravel, tanpa perlu setup MySQL tambahan)

---

## 📥 Cara Clone & Instalasi Proyek

Ikuti langkah-langkah berikut di terminal/command prompt Anda:

### 1. Clone Repository & Masuk Folder

```bash
git clone <url-repository-anda>
cd <nama-folder-proyek>
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
php artisan serve
npm run dev
```

Buka browser dan akses http://127.0.0.1:8000.
