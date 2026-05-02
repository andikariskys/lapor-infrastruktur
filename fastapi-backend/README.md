# Backend FastAPI

Backend sederhana menggunakan FastAPI untuk proyek Lapor Infrastruktur.

## Persyaratan

- Python 3.12+
- FastAPI

## Instalasi

1. Buat virtual environment:
   ```bash
   python -m venv .venv
   ```

2. Aktifkan virtual environment:
   - Linux/macOS: `source .venv/bin/activate`
   - Windows: `.venv\Scripts\activate`

3. Instal dependensi:
   ```bash
   pip install -r requirements.txt
   ```

## Konfigurasi Environment

Aplikasi ini **wajib** memiliki file `.env` untuk berjalan. Salin template yang tersedia:

```bash
cp .env.example .env
```

Buka file `.env` dan lengkapi variabel berikut:
- `DATABASE_URL`: Koneksi ke MySQL.
- `SECRET_KEY`: Kunci rahasia untuk JWT.
- `ALGORITHM`: Algoritma JWT (default: HS256).
- `ACCESS_TOKEN_EXPIRE_MINUTES`: Durasi login.

## Menjalankan Aplikasi

Sangat disarankan menjalankan backend di port **8001** agar tidak bentrok dengan frontend Laravel (port 8000):

```bash
uvicorn main:app --reload --port 8001
```

API akan tersedia di `http://127.0.0.1:8001`.
Dokumentasi Swagger API dapat diakses di `http://127.0.0.1:8001/docs`.

### Menghentikan Server

Untuk menghentikan server, tekan `CTRL+C` pada terminal.

### Menonaktifkan Virtual Environment

Untuk keluar dari virtual environment, jalankan perintah:

```bash
deactivate
```
