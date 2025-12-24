# Sistem Antrian Rumah Sakit (Laravel)

Sistem pendaftaran dan antrian pasien rumah sakit sederhana.

## Fitur
- Pendaftaran Pasien Baru.
- Cetak Nomor Antrian (Otomatis berdasarkan Poli).
- Dashboard Antrian Real-time.
- Menggunakan **SQLite** (Database file) sehingga ringan dan mudah dijalankan.
- Styling menggunakan **Tailwind CSS** (CDN).

## Cara Menjalankan (Instalasi)

Ikuti langkah ini jika Anda baru saja men-download/clone project ini:

1. **Masuk ke folder project** via Terminal:
   ```bash
   cd folder-projek-ini
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   ```

3. **Duplikat File Environment**:
   Salin file `.env.example` menjadi `.env`.
   Windows (PowerShell):
   ```powershell
   copy .env.example .env
   ```
   Mac/Linux:
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database (.env)**:
   Buka file `.env` dan pastikan bagian ini diatur seperti berikut (untuk menggunakan SQLite):
   ```ini
   DB_CONNECTION=sqlite
   # DB_HOST, DB_PORT, dll bisa diberi komentar atau dibiarkan
   ```
   
   *Penting: Ubah juga session driver agar tidak error:*
   ```ini
   SESSION_DRIVER=file
   CACHE_STORE=file
   ```

5. **Generate App Key**:
   ```bash
   php artisan key:generate
   ```

6. **Buat Database & Isi Data Dummy**:
   Perintah ini akan membuat file database dan mengisi 5 data pasien contoh.
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Jalankan Server**:
   ```bash
   php artisan serve
   ```

8. **Buka Browser**:
   Akses: [http://localhost:8000](http://localhost:8000)

## Akun Demo (Opsional)
- **Admin Email**: admin@hospital.com
- **Password**: password
