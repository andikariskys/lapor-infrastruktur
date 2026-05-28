# Lapor Infrastruktur - Laravel Frontend

Bagian frontend dari aplikasi Lapor Infrastruktur yang dibangun menggunakan Laravel 12 dan Tailwind CSS 4.

## Persyaratan Sistem

Pastikan perangkat Anda telah memenuhi persyaratan berikut sebelum memulai:

*   **PHP:** >= 8.2
*   **Composer:** Versi terbaru
*   **Database:** MySQL / MariaDB
*   **Ekstensi PHP:** `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `mysqli`

## Instalasi Database (MySQL)

Jika Anda belum memiliki MySQL di perangkat Anda, silakan ikuti panduan berikut:

*   **Windows:** Disarankan menginstal [XAMPP](https://www.apachefriends.org/) atau [Laragon](https://laragon.org/download/). Keduanya sudah menyertakan PHP, MySQL, dan Apache dalam satu paket.
*   **Linux (Ubuntu/Debian):**
    ```bash
    sudo apt update
    sudo apt install mysql-server
    ```
*   **macOS:** Bisa menggunakan [DBngin](https://dbngin.com/), [MAMP](https://www.mamp.info/), atau via Homebrew:
    ```bash
    brew install mysql
    ```

## Langkah Instalasi Proyek

Ikuti langkah-langkah berikut untuk menyiapkan proyek secara lokal:

1.  **Masuk ke direktori proyek:**
    ```bash
    cd laravel-frontend
    ```

2.  **Instal dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Instal dependensi Frontend (NPM):**
    Karena Laravel 12 menggunakan **Vite**, Anda perlu menginstal dependensi Node.js:
    ```bash
    npm install
    ```

4.  **Konfigurasi Environment:**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

5.  **Konfigurasi Database & API di `.env`:**
    Buka file `.env` dan sesuaikan bagian berikut:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_DATABASE=laporinfra
    
    # Konfigurasi API FastAPI
    API_URL=http://localhost:8000/api
    ```

6.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

7.  **Jalankan Migrasi Database:**
    ```bash
    php artisan migrate
    ```

## Menjalankan Aplikasi

Aplikasi ini menggunakan arsitektur **Decoupled**, sehingga Anda perlu menjalankan **tiga** server secara bersamaan:

### 1. Server Backend (FastAPI)
Buka terminal baru, lalu ikuti panduan pada file [`../fastapi-backend/README.md`](../fastapi-backend/README.md) di bagian **Menjalankan Aplikasi** untuk menjalankan server backend FastAPI.

*(Aplikasi backend akan berjalan di http://localhost:8001)*

### 2. Server Frontend (Laravel)
Buka terminal kedua, masuk ke folder frontend, dan jalankan:
```bash
cd laravel-frontend
php artisan serve
```
*(Aplikasi frontend akan berjalan di http://localhost:8000)*

### 3. Server Asset (Vite)
Buka terminal ketiga di folder frontend dan jalankan:
```bash
npm run dev
```
*(Penting untuk memproses CSS Tailwind 4 dan Javascript secara real-time)*

---

## Catatan Penting
- Pastikan file `.env` di **laravel-frontend** memiliki nilai `API_URL=http://localhost:8001/api`.
- Jika Anda mengubah port FastAPI, pastikan `API_URL` di Laravel juga disesuaikan.
- Gunakan Database yang sama atau pastikan migrasi sudah dijalankan di kedua sisi jika diperlukan.

---

## Fitur & Pembaruan Terbaru

Berikut adalah pembaruan fitur dan arsitektur yang berhasil diterapkan pada proyek frontend Laravel Admin Panel ini:

### 1. Konsolidasi `API_URL` & Penghapusan `BACKEND_URL`
* Konfigurasi `.env` sekarang disederhanakan dengan **hanya memerlukan 1 konfigurasi key saja yaitu `API_URL`** (contoh: `API_URL=http://localhost:8001/api`).
* Kunci `BACKEND_URL` tidak wajib diisi lagi karena file `config/app.php` akan secara otomatis dan dinamis menurunkan URL aset backend dari nilai `API_URL` (dengan memotong subpath `/api`).

### 2. Migrasi HTTP PATCH Multipart Murni
* Memperbaiki bug pada pengunggahan gambar profil mandiri di `ProfileController.php` dan pembaruan profil user oleh admin di `UserController.php`.
* Sebelumnya Laravel menggunakan metode *HTML Method Spoofing* (`POST` dengan field `_method => PATCH`) saat mengirimkan form multipart yang berisi gambar. Sistem kini telah dikonfigurasi untuk mengirimkan request **`PATCH` murni** secara langsung ke API FastAPI guna menjamin kecocokan arsitektur multipart endpoint di FastAPI backend.

### 3. Halaman Detail Laporan Terintegrasi (Ratings & Progress Petugas)
* Halaman detail laporan (`laporan-detail.blade.php`) kini terintegrasi secara visual untuk menampilkan:
  - **Bukti Penyelesaian Petugas:** Menampilkan foto bukti hasil perbaikan fisik petugas dari lapangan beserta catatan progres penyelesaian tugas.
  - **Lightbox Modal Interaktif:** Admin cukup mengklik foto bukti perbaikan petugas untuk membukanya secara penuh (lightbox modal) dengan transisi blur-backdrop yang modern.
  - **Seksi Rating Kepuasan Pelapor:** Ulasan teks warga beserta visualisasi bintang kepuasan (1 s.d 5 bintang) pelapor pembuat laporan secara real-time.

---

> [!IMPORTANT]
> **Pembaruan Skema Database (MySQL):**
> Ada penambahan kolom baru `resolution_photo` pada tabel `reports`. Backend FastAPI memiliki fitur migrasi otomatis saat di-start, tetapi jika Anda perlu melakukan migrasi database secara manual di MySQL produksi, jalankan perintah SQL berikut:
> ```sql
> ALTER TABLE reports ADD COLUMN resolution_photo VARCHAR(255) NULL;
> ```


