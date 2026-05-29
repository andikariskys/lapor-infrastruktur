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
fastapi dev
```
Windows:

```bash
fastapi dev
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

## Fitur & Pembaruan Terbaru

Berikut adalah serangkaian fitur dan pembaruan arsitektural yang telah diimplementasikan pada proyek backend ini:

### 1. Konsolidasi Uploads & Subdirektori Otomatis
* Penyajian berkas statis (static files mount) dipindahkan dari `/uploads` menjadi **/api/uploads**.
* Inisialisasi folder **`uploads/`** secara otomatis melahirkan 3 subfolder terstruktur:
  - `uploads/profiles/` — Menyimpan foto profil warga dan petugas.
  - `uploads/reports/` — Menyimpan foto bukti laporan kerusakan jalan & foto penyelesaian/perbaikan jalan petugas.
  - `uploads/institutions/` — Menyimpan logo lembaga/instansi pemerintah.

### 2. Resolusi URL Dinamis & Backward Compatibility
* Penyimpanan database telah dioptimalkan untuk **hanya menyimpan nama file mentah** (misal: `abc-123.png`), bukan path lengkap, guna fleksibilitas pemindahan server.
* Menggunakan Pydantic `@field_validator` murni pada schema respons (`UserRead`, `ReportRead`, `InstitutionRead`) untuk meresolusi nama file mentah menjadi URL lengkap secara dinamis saat data diambil.
* Sistem memiliki kompatibilitas mundur (*backward compatibility*): Data lama yang masih menyimpan prefix `/uploads/xxx` otomatis di-resolve secara dinamis menuju path baru `/api/uploads/xxx`.

### 3. Kompresi Gambar Otomatis (Upload)
* Menggunakan pustaka **Pillow** untuk melakukan pemrosesan dan optimasi gambar profil dan logo instansi saat diunggah secara otomatis:
  - **Resize Proporsional:** Gambar berukuran besar akan diperkecil secara proporsional agar resolusi terpanjangnya maksimal **800px**.
  - **Optimasi Ukuran:** Mengonversi format warna ke RGB dan mengompresi kualitas gambar (default `quality=75` untuk JPEG & optimasi untuk PNG) guna menghemat media penyimpanan server.
  - **Dikecualikan:** Bukti foto laporan warga (`reports.py`) & bukti foto perbaikan petugas **tidak dikompresi** guna mempertahankan bukti otentik dengan resolusi asli.

### 4. Dukungan Penilaian (Rating & Ulasan Warga)
* Schema respons `ReportRead` kini otomatis menyematkan data rating dan ulasan (`feedbacks: List[FeedbackRead] = []`) yang ditulis oleh warga pembuat laporan saat detail laporan diambil.

### 5. Dukungan Unggah Foto Bukti Perbaikan Petugas
* Endpoint progres kerja `POST /reports/{report_id}/progress` kini mendukung unggahan berkas gambar perbaikan (`image: Annotated[Optional[UploadFile], File()] = None`) dan tersimpan otomatis ke dalam kolom baru `resolution_photo` di tabel laporan.

### 6. Balasan Petugas ke Pelapor (`officer_reply`)
* Ditambahkan kolom baru **`officer_reply`** (TEXT) di tabel `reports` untuk menyimpan teks balasan/catatan dari petugas yang ditujukan kepada warga pelapor.
* Saat petugas mengupdate progres pekerjaan melalui endpoint `POST /reports/{report_id}/progress`, isi `note` dari petugas otomatis disimpan ke kolom `officer_reply` di tabel `reports`, sehingga pelapor dapat melihat balasan tersebut di detail laporannya.
* Kolom `note` pada tabel `assignments` tetap digunakan sebagai catatan internal penugasan dari admin ke petugas saat assign melalui endpoint `POST /reports/{report_id}/assign`.

---

> [!TIP]
> **Pembaruan Skema Database (MySQL):**
> Backend ini sudah dilengkapi **skema migrasi otomatis** di `database.py` yang akan menambahkan kolom baru saat aplikasi di-start. Namun, jika Anda ingin menambahkannya secara manual di database produksi Anda, jalankan perintah SQL berikut:
> ```sql
> ALTER TABLE reports ADD COLUMN resolution_photo VARCHAR(255) NULL;
> ALTER TABLE reports ADD COLUMN officer_reply TEXT NULL;
> ```

