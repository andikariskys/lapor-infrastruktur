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

## Menjalankan Aplikasi

Jalankan server pengembangan default:

```bash
fastapi dev
```

Atau jalankan server pengembangan menggunakan uvicorn:

```bash
uvicorn main:app --reload
```

API akan tersedia di `http://127.0.0.1:8000`.

### Menghentikan Server

Untuk menghentikan server, tekan `CTRL+C` pada terminal.

### Menonaktifkan Virtual Environment

Untuk keluar dari virtual environment, jalankan perintah:

```bash
deactivate
```
