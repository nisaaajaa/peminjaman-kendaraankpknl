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
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body>

  <div class="form-container">
    <div class="form-header">
      <h2>FORM PEMINJAMAN KENDARAAN</h2>
      <p>KPKNL METRO - KEMENTERIAN KEUANGAN RI</p>
    </div>

    <!-- Menampilkan Error Validasi Laravel -->
    @if ($errors->any())
    <div style="background-color: #FEE2E2; color: #DC2626; padding: 1rem; margin: 1rem; border-radius: 8px;">
      <ul style="margin-left: 1rem;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    @if(session('error'))
    <div style="background-color: #FEE2E2; color: #DC2626; padding: 1rem; margin: 1rem; border-radius: 8px;">
      {{ session('error') }}
    </div>
    @endif

    <form id="loanForm" class="form-body" action="{{ route('pinjam.store', $vehicle->id) }}" method="POST">
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
          @foreach($employees as $emp)
            <option value="{{ $emp->nama_pegawai }}">{{ $emp->nama_pegawai }}</option>
          @endforeach
        </select>
      </div>

      <!-- NIP (Untuk Validasi Identitas) -->
      <div class="form-group">
        <label for="nip">NIP Pegawai</label>
        <input type="number" id="nip" name="nip" class="form-control" placeholder="Masukkan 18 digit NIP Anda" required>
        <small style="color: #64748B;">NIP digunakan untuk validasi identitas.</small>
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

      <!-- Cloudflare Turnstile -->
      <div class="form-group" style="display: flex; justify-content: center; margin-top: 1rem;">
        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="light"></div>
      </div>

      <!-- Tombol Aksi -->
      <div class="form-actions">
        <a href="/" class="btn btn-cancel">Batal</a>
        <button type="submit" class="btn btn-submit">Kirim Permohonan</button>
      </div>
    </form>
  </div>

</body>
</html>