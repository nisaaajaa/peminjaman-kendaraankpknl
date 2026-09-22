# Panduan Paling Mudah: Mem-publish Aplikasi ke Internet

Tutorial ini dibuat khusus untuk pemula yang belum pernah meng-online-kan aplikasi sebelumnya. Kita akan menggunakan **Railway.app** karena sistemnya 100% otomatis dan ramah kolaborasi, layaknya sebuah keajaiban.

---

## BAGIAN 1: Mematikan GitHub Pages

Pertama, kita harus mematikan fitur GitHub Pages. 
Kenapa? Karena GitHub Pages itu ibarat "Papan Mading" yang hanya bisa memajang kertas dan gambar statis. Sedangkan aplikasi Peminjaman Kendaraan kita adalah "Mesin Pintar" yang harus mengolah data. Mesin tidak bisa berjalan jika hanya ditempel di papan mading.

**Cara Mematikannya:**
1. Buka halaman repositori GitHub milik Anda (tempat Anda menyimpan kode ini).
2. Lihat menu bagian atas, lalu klik **Settings** (ikon roda gigi).
3. Pada deretan menu di sebelah kiri, gulir ke bawah dan klik **Pages**.
4. Di bagian bertuliskan *Build and deployment*, ada pilihan kotak menurun (*dropdown*) bernama *Source*. Ubah isinya menjadi **None**. (Atau jika ada tombol bertuliskan *Unpublish site*, klik tombol tersebut).
5. Selesai!

---

## BAGIAN 2: Menyambungkan Aplikasi ke Railway.app

**Railway** adalah layanan ajaib yang akan membaca kode Anda dari GitHub, merakitnya sendiri, dan membuatnya *online* secara otomatis (jika ada pembaruan kode, ia juga akan *update* sendiri).

1. Buka situs **[railway.app](https://railway.app)** di *browser* Anda.
2. Klik tombol **Login** di pojok kanan atas, lalu pilih **Login with GitHub**.
3. Jika ditanya persetujuan, klik **Authorize Railway**.

---

## BAGIAN 3: Membuat Database MySQL (Lemari Penyimpanan)

Untuk aplikasi kelas profesional seperti Vercel dan Railway, sistem mereka selalu "Dihapus dan Dibuat Ulang" saat ada pembaruan. Oleh karena itu, file *database* SQLite akan terhapus. Solusinya? Kita harus pindah menggunakan **MySQL**. 
Tenang saja, di Railway membuat MySQL hanya butuh satu klik!

1. Di layar utama Railway, klik tombol ungu bertuliskan **New Project** (atau tombol ikon **+**).
2. Akan muncul daftar panjang, silakan cari dan klik pilihan **Provision MySQL**.
3. Tunggu beberapa detik sampai muncul sebuah kotak ungu di layar Anda. Selamat, Anda baru saja berhasil membuat server *database* sungguhan!

---

## BAGIAN 4: Memasukkan Kode Aplikasi Kita

Sekarang, kita harus memasukkan kode aplikasi Peminjaman Anda dari GitHub agar berdampingan dengan kotak MySQL tadi.

1. Di proyek Railway yang sama (tempat kotak MySQL tadi berada), klik tombol **New** di pojok kanan atas (atau klik sembarang tempat kosong di layar kotak-kotak itu, lalu pilih **New**).
2. Pilih **Deploy from GitHub repo**.
3. Jika ditanya izin, beri izin Railway untuk membaca repositori GitHub Anda.
4. Pilih nama repositori aplikasi Peminjaman Kendaraan Anda (contoh: `nisaaajaa/peminjaman-kendaraankpknl`).
5. Selesai! Anda akan melihat sebuah kotak baru muncul di sebelah kotak MySQL. Kotak baru itu adalah aplikasi web Anda.

---

## BAGIAN 5: Menghubungkan Aplikasi ke MySQL (Sangat Penting!)

Aplikasi web Anda dan MySQL Anda saat ini masih terpisah dan belum saling kenal. Kita harus memberi tahu aplikasi Anda agar dia "bertanya" ke kotak MySQL.

*(Catatan: Saya sudah menyuntikkan "kode ajaib" di dalam proyek ini sehingga Anda hanya perlu menambahkan 1 kata sandi rahasia saja. Sangat mudah!)*

1. Klik kotak **Aplikasi Web** Anda di layar Railway (bukan kotak MySQL).
2. Di jendela pengaturan yang muncul, klik menu tab **Variables** (Variabel).
3. Di layar tersebut, klik tombol **New Variable** (Variabel Baru).
4. Akan ada 2 kotak isian. Isi persis seperti ini:
   - Kotak Kiri (VARIABLE_NAME): ketik `DB_CONNECTION`
   - Kotak Kanan (VALUE): ketik `mysql`
5. Setelah diisi, klik tombol **Add** (atau tekan Enter).
6. Saat Anda menekan tombol Add, aplikasi akan otomatis memproses ulang kodenya (*Re-deploy*). Biarkan saja prosesnya berjalan!

---

## BAGIAN 6: Mengisi Tabel Database (Selesai!)

Aplikasi sudah terhubung, tetapi lemari MySQL kita di dalam masih kosong melompong. Kita harus membuat rak-raknya (struktur tabel) dan mengisinya dengan daftar 5 mobil awal KPKNL.

1. Masih di kotak **Aplikasi Web** Anda, klik menu tab **Terminal** (sebelah kanan tab Variables).
2. Anda akan melihat layar hitam (seperti layar peretas/*hacker*). Jangan takut, ini sangat aman.
3. Klik di dalam layar hitam tersebut, ketik perintah ini persis seperti ini:
   `php artisan migrate --force`
4. Tekan tombol **Enter**. (Tunggu sebentar, perintah ini sedang otomatis membuatkan rak-rak tabel).
5. Setelah selesai (muncul kembali *prompt* berkedip), ketik perintah satu lagi:
   `php artisan db:seed --force`
6. Tekan tombol **Enter**. (Perintah ini akan mengisi rak tabel tersebut dengan daftar mobil awal).

**SELAMAT! 🎉** Semua proses telah selesai! 
Tutup jendela terminal tersebut. Anda bisa melihat **Tautan Web Anda** (*URL Domain* yang bisa diklik) di dalam menu tab **Settings**. Bagikan *link* tersebut ke rekan kerja Anda, dan mereka sudah bisa langsung mengakses aplikasinya lewat HP atau Komputer manapun!
