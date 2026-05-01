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

Aplikasi ini memerlukan dua server yang berjalan secara bersamaan:

1.  **Server Backend (PHP):**
    Buka terminal pertama dan jalankan:
    ```bash
    php artisan serve
    ```
    *(Aplikasi akan berjalan di http://localhost:8000)*

2.  **Server Asset (Vite):**
    Buka terminal kedua dan jalankan:
    ```bash
    npm run dev
    ```
    *(Penting untuk memproses CSS Tailwind dan Javascript secara real-time)*

Aplikasi sekarang dapat diakses melalui browser di [http://localhost:8000](http://localhost:8000).
