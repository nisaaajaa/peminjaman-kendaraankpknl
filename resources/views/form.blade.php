<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Peminjaman Kendaraan - KPKNL Metro</title>
  <style>
    :root {
      --kemenkeu-main: #062145;
      --kemenkeu-gold: #F2C94C;
      --bg-gray: #F4F6F9;
      --card-white: #FFFFFF;
      --text-dark: #1E293B;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background-color: var(--bg-gray); color: var(--text-dark); padding: 2rem 1rem; }
    
    .form-container {
      max-width: 600px;
      margin: 0 auto;
      background: var(--card-white);
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
      border: 1px solid #E2E8F0;
    }

    .form-header {
      background-color: var(--kemenkeu-main);
      color: white;
      padding: 1.5rem;
      text-align: center;
      border-bottom: 4px solid var(--kemenkeu-gold);
    }

    .form-header h2 { font-size: 1.4rem; letter-spacing: 0.5px; margin-bottom: 0.2rem; }
    .form-header p { color: var(--kemenkeu-gold); font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; }

    .form-body { padding: 2rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; font-weight: 600; font-size: 0.9rem; color: var(--kemenkeu-main); margin-bottom: 0.4rem; }
    
    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #CBD5E1;
      border-radius: 6px;
      font-size: 0.95rem;
      transition: border-color 0.2s;
      background-color: white;
    }

    .form-control:focus { outline: none; border-color: var(--kemenkeu-main); }
    .form-control[readonly] { background-color: #F1F5F9; color: #64748B; cursor: not-allowed; }
    textarea.form-control { resize: vertical; min-height: 90px; }

    .form-actions { display: flex; gap: 10px; margin-top: 1.8rem; }
    .btn { flex: 1; padding: 0.8rem; border-radius: 6px; font-weight: 700; text-align: center; text-decoration: none; border: none; cursor: pointer; font-size: 0.95rem; }
    .btn-submit { background-color: var(--kemenkeu-main); color: white; }
    .btn-submit:hover { background-color: #03142C; }
    .btn-cancel { background-color: #E2E8F0; color: #475569; }
    .btn-cancel:hover { background-color: #CBD5E1; }

    .alert-success {
      display: none;
      background-color: #D1E7DD;
      color: #0F5132;
      border: 1px solid #BADBCC;
      padding: 1.2rem;
      border-radius: 8px;
      text-align: center;
      margin: 2rem;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <div class="form-header">
      <h2>FORM PEMINJAMAN KENDARAAN</h2>
      <p>KPKNL METRO - KEMENTERIAN KEUANGAN RI</p>
    </div>

    <!-- PESAN SUKSES TERKIRIM -->
    <div id="successMessage" class="alert-success">
      <h3>✅ Permohonan Terkirim!</h3>
      <p>Permohonan peminjaman kendaraan berhasil diproses. Mengalihkan ke halaman utama...</p>
    </div>

    <form id="loanForm" class="form-body" onsubmit="submitForm(event)">
      @csrf

      <!-- Kendaraan yang Dipinjam -->
      <div class="form-group">
        <label>Kendaraan yang Dipinjam</label>
        <input type="text" id="nama_kendaraan" class="form-control" value="{{ $vehicle->nama_kendaraan ?? 'Toyota Hilux - B 9440 PSE' }}" readonly>
      </div>

      <!-- Nama Lengkap Pegawai -->
      <div class="form-group">
        <label for="nama_pegawai">Nama Lengkap Pegawai</label>
        <select id="nama_pegawai" name="nama_pegawai" class="form-control" required>
          <option value="">-- Pilih Nama Pegawai --</option>
          <option value="MOHAMAD RIYANTO">MOHAMAD RIYANTO</option>
          <option value="MARYANTO">MARYANTO</option>
          <option value="RAHMAD SIGIT">RAHMAD SIGIT</option>
          <option value="MUHAMMAD GANJAR NUGRAHA">MUHAMMAD GANJAR NUGRAHA</option>
          <option value="MUCHTAR NURWAHIDZAIN">MUCHTAR NURWAHIDZAIN</option>
          <option value="BARNO">BARNO</option>
          <option value="ISMARUDDIN">ISMARUDDIN</option>
          <option value="RUBIN HARYADI">RUBIN HARYADI</option>
          <option value="JOHAN WAHYUDI">JOHAN WAHYUDI</option>
          <option value="YOGI WISAKSONO">YOGI WISAKSONO</option>
          <option value="MELVIN INDRIANI">MELVIN INDRIANI</option>
          <option value="ADE HENDRA VASKAH TARIGAN">ADE HENDRA VASKAH TARIGAN</option>
          <option value="ANGGA APRIANTO">ANGGA APRIANTO</option>
          <option value="HABIB BURAKHMAN">HABIB BURAKHMAN</option>
          <option value="WAHIDIN HARYA DITAMA">WAHIDIN HARYA DITAMA</option>
          <option value="WIDI WIDAYAT">WIDI WIDAYAT</option>
          <option value="ADHYTIA PRATAMA ALBEN">ADHYTIA PRATAMA ALBEN</option>
          <option value="MEYZAR AHMAD">MEYZAR AHMAD</option>
          <option value="MUHAMAD RIZKIANA GUMILANG">MUHAMAD RIZKIANA GUMILANG</option>
          <option value="AHMAD NOPRAN">AHMAD NOPRAN</option>
          <option value="AMELIA RIZKYANTI">AMELIA RIZKYANTI</option>
          <option value="SANTO SULANDRY">SANTO SULANDRY</option>
        </select>
      </div>

      <!-- Seksi / Subbagian -->
      <div class="form-group">
        <label for="seksi">Seksi / Subbagian</label>
        <input type="text" id="seksi" name="seksi" class="form-control" placeholder="Contoh: Seksi Hukum dan Informasi" required>
      </div>

      <!-- Keperluan / Perjalanan Dinas -->
      <div class="form-group">
        <label for="keperluan">Keperluan / Perjalanan Dinas</label>
        <textarea id="keperluan" name="keperluan" class="form-control" placeholder="Jelaskan tujuan dan keperluan dinas..." required></textarea>
      </div>

      <!-- Tanggal Pinjam -->
      <div class="form-group">
        <label for="tgl_pinjam">Tanggal Pinjam</label>
        <input type="date" id="tgl_pinjam" name="tgl_pinjam" class="form-control" required>
      </div>

      <!-- Tanggal Kembali -->
      <div class="form-group">
        <label for="tgl_kembali">Tanggal Kembali</label>
        <input type="date" id="tgl_kembali" name="tgl_kembali" class="form-control" required>
      </div>

      <!-- Tombol Aksi -->
      <div class="form-actions">
        <a href="/" class="btn btn-cancel">Batal</a>
        <button type="submit" class="btn btn-submit">Kirim Permohonan</button>
      </div>
    </form>
  </div>

  <script>
    function submitForm(event) {
      event.preventDefault();
      
      const namaPegawai = document.getElementById('nama_pegawai').value;
      const namaKendaraan = document.getElementById('nama_kendaraan').value;
      const tglKembali = document.getElementById('tgl_kembali').value;

      // Simpan data peminjam & masa pinjam
      let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
      statusMobil[namaKendaraan] = {
        peminjam: namaPegawai,
        tglKembali: tglKembali
      };
      localStorage.setItem('statusMobil', JSON.stringify(statusMobil));

      document.getElementById('loanForm').style.display = 'none';
      document.getElementById('successMessage').style.display = 'block';

      setTimeout(function() {
        window.location.href = "/";
      }, 2000);
    }
  </script>

</body>
</html>