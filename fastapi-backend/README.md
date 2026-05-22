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

### Database Settings
- `DB_TYPE`: Dialek database (contoh: `mysql+pymysql`).
- `DB_HOST`: Alamat host database.
- `DB_USERNAME`: Username database.
- `DB_PASSWORD`: Password database.
- `DB_NAME`: Nama database.
- `DATABASE_URL`: (Opsional) Jika diisi, variabel di atas akan diabaikan.

### Security Settings
- `SECRET_KEY`: Kunci rahasia untuk JWT.
- `ALGORITHM`: Algoritma JWT (default: HS256).
- `ACCESS_TOKEN_EXPIRE_MINUTES`: Durasi login.

## Menjalankan Aplikasi

Linux/MacOS:

```bash
python3 run.py
```
Windows:

```bash
python run.py
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
