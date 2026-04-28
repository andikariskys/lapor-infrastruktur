# Lapor Infrastruktur - Flutter Frontend

Aplikasi mobile/multi-platform untuk pelaporan masalah infrastruktur.

## Prasyarat

Sebelum memulai, pastikan Anda telah menginstal:
- [Flutter SDK](https://docs.flutter.dev/get-started/install) (versi terbaru direkomendasikan)
- [Dart SDK](https://dart.dev/get-dart)
- Android Studio / VS Code dengan ekstensi Flutter & Dart
- Emulator Android / iOS atau perangkat fisik untuk pengujian

## Langkah-langkah Instalasi

1.  **Masuk ke direktori flutter-frontend:**
    ```bash
    cd flutter-frontend
    ```

2.  **Unduh dependensi proyek:**
    ```bash
    flutter pub get
    ```

3.  **Pastikan tidak ada masalah konfigurasi:**
    ```bash
    flutter doctor
    ```

## Menjalankan Aplikasi

1.  **Cek daftar perangkat yang tersedia:**
    ```bash
    flutter devices
    ```

2.  **Jalankan aplikasi dalam mode debug:**
    ```bash
    flutter run
    ```

    *Catatan: Jika Anda memiliki beberapa perangkat yang terhubung, gunakan `-d <device_id>`, contoh: `flutter run -d chrome`.*

## Membangun Aplikasi (Build)

Untuk menghasilkan file APK (Android) atau format lainnya:

- **Android APK:**
  ```bash
  flutter build apk --release
  ```

- **Android App Bundle:**
  ```bash
  flutter build appbundle --release
  ```

- **iOS:**
  ```bash
  flutter build ios --release
  ```

- **Web:**
  ```bash
  flutter build web --release
  ```

Hasil build dapat ditemukan di folder `build/app/outputs/flutter-apk/` untuk Android.
