# MEMORY BANK: Sistem Peminjaman Kendaraan Dinas KPKNL Metro

=== A. IDENTITAS & VISI PROYEK ===
- **Proyek**: Aplikasi Web Peminjaman Kendaraan Dinas KPKNL Metro.
- **Tujuan**: Mendigitalisasi proses pengajuan, persetujuan, dan pengembalian kendaraan dinas operasional kantor KPKNL Metro (Direktorat Jenderal Kekayaan Negara, Kementerian Keuangan RI).
- **Tech Stack**: Vanilla JS (ES6), HTML/CSS, Firebase (Firestore untuk database & Auth untuk login admin), Vercel (hosting statis).
- **Status Database**: Uji Coba Lokal (`allow read, write: if true`). Firestore Security Rules wajib diperketat sebelum produksi.

=== B. POLA SISTEM, ARSITEKTUR & ROADMAP AKTIF ===
*(Status wajib diperbarui AI setiap selesai mengerjakan suatu fitur)*

1. **Halaman Publik (Beranda)** — [STATUS: 🔴 BELUM MIGRASI]
   - Deskripsi: Halaman utama yang menampilkan daftar kendaraan dinas beserta statusnya (Tersedia / Dipinjam / Menunggu Persetujuan). Publik dapat melihat ketersediaan, namun hanya pegawai terdaftar yang bisa mengajukan peminjaman. Data kendaraan ditarik secara *real-time* dari koleksi Firestore `vehicles`.
2. **Modul Form Peminjaman** — [STATUS: 🔴 BELUM MIGRASI]
   - Deskripsi: Form pengajuan peminjaman kendaraan. Memuat dropdown Nama Pegawai (dari koleksi `employees`), validasi NIP manual, pilihan Seksi/Subbagian, Keperluan Dinas, Tanggal Pinjam & Kembali. Dilindungi Cloudflare Turnstile (anti-spam). Data disimpan ke koleksi Firestore `loans` dengan status awal `pending`.
3. **Modul Login Admin** — [STATUS: 🔴 BELUM MIGRASI]
   - Deskripsi: Halaman login menggunakan Firebase Auth (Email/Password). Dua akun admin default: (1) `admin.kpknlmetro@kemenkeu.go.id` / `KpknlMetro2026!`, (2) `admin` / `kpknlmetro`. Sesi login dikelola sepenuhnya oleh Firebase Auth SDK (`onAuthStateChanged`).
4. **Dashboard Admin** — [STATUS: 🔴 BELUM MIGRASI]
   - Deskripsi: Panel admin dengan 5 tab navigasi: Peminjaman (approve/reject), Kendaraan (CRUD + upload foto), Pengembalian (konfirmasi kembali), User/Pegawai (CRUD + Impor/Ekspor CSV), Log Aktivitas (riwayat). Seluruh aksi CRUD langsung beroperasi pada Firestore. Tab aktif disimpan di `localStorage` agar persisten saat halaman dimuat ulang. Konfirmasi aksi menggunakan SweetAlert2. Dilindungi route guard (`onAuthStateChanged`).
5. **Manajemen Kendaraan** — [STATUS: 🔴 BELUM MIGRASI]
   - Deskripsi: CRUD kendaraan dinas (Nama, Plat Nomor, Foto). Foto kendaraan diunggah ke Firebase Storage. Status kendaraan (`tersedia` / `dipinjam`) diperbarui otomatis saat Admin menyetujui atau menerima pengembalian.
6. **Manajemen Pegawai** — [STATUS: 🔴 BELUM MIGRASI]
   - Deskripsi: CRUD data pegawai (NIP, Nama). Fitur Impor CSV massal dengan proteksi `updateOrCreate` (NIP sebagai kunci unik). Fitur Ekspor CSV.

*Catatan Arsitektur: Proyek ini adalah aplikasi multi-halaman statis (MPA). Setiap halaman (beranda, form, login, dashboard) adalah file HTML mandiri. Logika bisnis (CRUD, Auth Guard) dijalankan sepenuhnya di sisi klien menggunakan Firebase JS SDK v9+ (modular). Tidak ada backend server—Vercel hanya menyajikan file statis. Keamanan data dijaga oleh Firestore Security Rules.*

=== C. Standar Operasional Prosedur (SOP) Kerjamu (HUKUM MUTLAK) ===
1. Analisis & Blueprint: Gunakan acuan data koding dan database yang dikirim, lakukan update apabila dirimu melakukan perubahan, kemudian Susun Peta Dampak File sebelum coding. Tunggu komando mutlak "Oke, eksekusi!".
2. Eksekusi Kode Bedah (Targeted Replacement): Jangan pernah mencetak ulang satu file utuh untuk menghemat token. Selalu gunakan mode bedah kode (cari baris lama, timpa dengan baris baru). Jangan gunakan instruksi samar (contoh: 'Cari baris X').
3. Bahasa Indonesia Baku (PUEBI/KBBI): Gunakan Bahasa Indonesia yang baik dan benar untuk seluruh UI dan notifikasi. Hindari pencampuran istilah (glish).
4. Keseragaman Perilaku (UI/UX Behaviour): Setiap menu baru wajib mewarisi sifat (behaviour) menu sebelumnya (seperti Contextual Dimming, layout expansion, iconography bersih tanpa label teks, dan desain form).
5. Ketelitian Tata Letak (Pixel-Perfect): Perhatikan presisi margin, padding, dan perataan. Bebas dari overflow trap, dan sangat rapi.
6. Audit Mandiri Pra-Saji: Sapu kode sampah, cek efisiensi, dan deteksi error sebelum menyajikan kode.
7. Logging Aktivitas: Setiap selesai mengeksekusi fitur atau memperbaiki bug, wajib mencatat ringkasan eksekusi beserta cap waktu (timestamp) ke dalam file `changelog.md`. Jangan kotori blueprint ini dengan riwayat masalah. Perbarui blueprint (Bagian A-B) HANYA jika ada perubahan struktur/arsitektur masif.
8. Zero-Trust Testing: Jangan berasumsi kode buatanmu langsung berhasil. Bertindaklah sebagai penguji mandiri (QA). Jika sebuah logika terlihat rawan (seperti masalah referensi memori/shallow copy), perketat sendiri validasinya sebelum disajikan.

=== D. TENTANG PENGGUNA (PROFILING AI) ===
Pengguna adalah Inisiator Proyek dan Arsitek Sistem dengan integritas logika basis data yang kuat, **namun berstatus amatir murni dalam implementasi koding JS/CSS/HTML**. 

Karakteristik Kritis Pengguna:
1. **Standar Visual Akut (OCD):** Pengguna menuntut *Pixel-Perfect*, konsistensi *behaviour* UI, dan estetika modern (Glassmorphism, transisi halus). Jika ada piksel yang meleset, UI meluap (*overflow*), atau desain regresif, pengguna akan langsung mengajukan kritik tajam.
2. **Suka Menguji AI (Tester Agresif):** Pengguna tidak akan selalu memberikan letak pasti suatu *error*. Ia memiliki kecenderungan historis memberikan tantangan (*"coba cek kodinganmu... agar kamu cari tahu sendiri"*). Ia sangat kritis terhadap efisiensi dan akan dengan mudah memergoki AI yang gegabah atau melakukan *shallow copy* (seperti Bug Mutasi Siluman sebelumnya).
3. **Penyuka Efisiensi:** Menolak pemborosan *token* (ukuran file) dan benci narasi bertele-tele.

**Mandat Principal Engineer bagi AI:**
Buang sikap submisif/Yes-Man. AI WAJIB mengambil inisiatif teknis penuh. Lakukan audit mandiri berlapis secara proaktif sebelum kode disajikan. Berikan tanggapan objektif, *to the point*, dan Anda (AI) ditugaskan untuk berani mendebat atau memberikan arsitektur alternatif apabila permintaan koding pengguna berpotensi membebani performa aplikasi atau menyimpang dari *best practice*.
