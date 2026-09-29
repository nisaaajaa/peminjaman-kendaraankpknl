# PANDUAN DEPLOYMENT SISTEM TERPADU KPKNL METRO

Dokumen ini berisi petunjuk teknis pelaksanaan deployment Sistem Terpadu KPKNL Metro menggunakan arsitektur Serverless (Vercel) dan Backend-as-a-Service (Firebase). Harap ikuti instruksi di bawah ini secara berurutan.

## A. PERSIAPAN DATABASE (FIREBASE)

1. Buka situs Firebase Console (https://console.firebase.google.com/) dan login menggunakan akun Google terkait.
2. Klik **Create a Project**. Masukkan nama proyek (contoh: `KPKNL-Metro`), lanjutkan proses hingga proyek selesai dibuat.
3. Pada panel navigasi kiri, pilih **Build** -> **Authentication**.
   - Klik **Get Started**.
   - Pada tab **Sign-in method**, aktifkan **Email/Password**, lalu klik Save.
   - Pada tab **Users**, klik **Add User**. Daftarkan akun administrator (contoh Email: `admin@kpknl.local`, Password: `kpknlmetro`). Klik Add User.
4. Pada panel navigasi kiri, pilih **Build** -> **Firestore Database**.
   - Klik **Create Database**. Pilih lokasi server (disarankan: `asia-southeast2` / Jakarta), klik Next.
   - Pada tab **Rules**, ubah baris `allow read, write: if false;` menjadi `allow read, write: if true;`. (Catatan: Pengaturan ini hanya untuk masa pengembangan, penyesuaian keamanan wajib dilakukan sebelum rilis final). Klik **Publish**.
5. Pada panel navigasi kiri, pilih **Build** -> **Storage**.
   - Klik **Get Started**, tentukan lokasi server, klik Done.
   - Pada tab **Rules**, sesuaikan *rules* menjadi `allow read, write: if true;`. Klik **Publish**.
6. Sinkronisasi Konfigurasi Firebase:
   - Kembali ke menu Project Overview (ikon rumah di kiri atas).
   - Klik ikon **Web** (`</>`) untuk mendaftarkan aplikasi web. Beri nama aplikasi, lalu klik Register.
   - Salin blok kode `firebaseConfig` (yang berisi apiKey, authDomain, projectId, dsb).
   - Buka file `js/firebase-config.js` pada kode sumber repositori ini. Ganti nilai konfigurasi yang lama dengan konfigurasi milik Anda. Simpan file.

## B. PELAKSANAAN DEPLOYMENT (VERCEL)

1. Pastikan seluruh perubahan kode sumber (termasuk pembaruan `firebase-config.js`) telah diunggah (*push*) ke repositori GitHub.
2. Buka situs Vercel (https://vercel.com/) dan login menggunakan akun GitHub yang terhubung dengan repositori ini.
3. Pada halaman dasbor utama, klik tombol **Add New...** dan pilih **Project**.
4. Pada menu *Import Git Repository*, pilih repositori aplikasi ini, kemudian klik **Import**.
5. Pertahankan seluruh pengaturan dalam kondisi *default* (Framework Preset: Other).
6. Klik tombol **Deploy**.
7. Tunggu hingga proses instalasi selesai. Vercel akan mendistribusikan tautan URL publik secara otomatis.

## C. KETENTUAN TAMBAHAN

1. Aplikasi ini beroperasi sepenuhnya pada sisi klien (statis) dan tidak lagi menggunakan arsitektur *backend server* tradisional (seperti PHP/Laravel).
2. Mekanisme integrasi berlanjut (CI/CD) telah aktif. Setiap perubahan kode (HTML/CSS/JS) yang di-*push* ke cabang utama (main) di GitHub akan memicu pembaruan sistem (*redeployment*) otomatis di Vercel.
