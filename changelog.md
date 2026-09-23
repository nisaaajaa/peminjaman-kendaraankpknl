# 📜 Changelog (Riwayat Pembaruan)

Berikut adalah daftar perubahan yang telah dilakukan untuk menambal celah keamanan dan memperbaiki arsitektur aplikasi Peminjaman Kendaraan.

## 🚀 Fitur Baru (Added)
- **Validasi Identitas (NIP):** Formulir peminjaman kini mewajibkan input NIP (Nomor Induk Pegawai). NIP akan diverifikasi kecocokannya dengan Nama Pegawai menggunakan data di *database* (`EmployeeSeeder`).
- **Pelindung Anti-Spam (Cloudflare Turnstile):** Mengintegrasikan *widget* keamanan Cloudflare di dalam form peminjaman untuk mencegah serangan robot/DDoS.
- **Limitasi Pengiriman (Rate Limiting):** Menambahkan proteksi *Throttle* pada *routing* pengiriman formulir. Satu perangkat hanya bisa mengirim maksimal 5 formulir per menit.
- **AdminController:** Membuat jalur komunikasi data yang sesungguhnya antara halaman dasbor admin dengan tabel *database* Laravel.

## 🔧 Perbaikan (Changed/Fixed)
- **Penghapusan Javascript Palsu (`localStorage`):** Seluruh script `localStorage` di dalam `dashboard.blade.php` dan `form.blade.php` telah dibuang sepenuhnya.
- **Render Database Real-time:** Dasbor Admin (Tab Peminjaman & Kendaraan) kini 100% menarik data asli dari server/database SQLite/MySQL, bukan lagi dari *cache browser* lokal.

## ⚠️ Utang Teknis (Unaddressed / Known Issues)
Catatan temuan audit yang **sengaja belum diperbaiki** atas persetujuan (*Test Run Phase*):
1. **Login Hardcoded:** Halaman login (`/login`) masih berupa antarmuka statis yang bisa dilewati secara langsung. Kredensial *admin* sengaja di-*hardcode* untuk kemudahan pengujian internal. *(Akan diperbaiki saat tahap Production)*.
2. **Fitur Pengembalian, User, & Log Aktivitas:** Tab ini di dalam Dasbor Admin sengaja dinonaktifkan sementara (disembunyikan logika operasionalnya) karena prioritas difokuskan pada perombakan alur utama Peminjaman dan Manajemen Kendaraan.
