<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KPKNL Metro - Kementerian Keuangan RI</title>
  <style>
    :root {
      --kemenkeu-main: #062145;
      --kemenkeu-dark: #03142C;
      --kemenkeu-gold: #F2C94C;
      --kemenkeu-light-gold: #FFECB3;
      --bg-gray: #F8FAFC;
      --card-white: #FFFFFF;
      --text-dark: #1E293B;
      --danger: #E74C3C;
      --warning: #D97706;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background-color: var(--bg-gray); color: var(--text-dark); line-height: 1.6; }

    header {
      background-color: var(--kemenkeu-main);
      border-bottom: 3px solid var(--kemenkeu-gold);
      padding: 0.8rem 3rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .brand-container { display: flex; align-items: center; gap: 15px; }
    .brand-logo-img { height: 45px; width: auto; object-fit: contain; }
    .brand-text h2 { color: #FFFFFF; font-size: 1.2rem; line-height: 1.2; text-align: left; }
    .brand-text p { color: var(--kemenkeu-gold); font-size: 0.75rem; font-weight: 600; text-align: left; }

    .admin-btn {
      background-color: transparent; color: var(--kemenkeu-gold);
      border: 2px solid var(--kemenkeu-gold); padding: 0.5rem 1.2rem;
      border-radius: 6px; font-weight: 600; text-decoration: none;
      transition: all 0.2s;
    }
    .admin-btn:hover { background-color: var(--kemenkeu-gold); color: var(--kemenkeu-main); }

    .hero {
      height: calc(100vh - 75px);
      background: linear-gradient(rgba(6, 33, 69, 0.88), rgba(3, 20, 44, 0.92)), 
                  url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop');
      background-size: cover; background-position: center;
      display: flex; flex-direction: column; justify-content: center;
      align-items: center; text-align: center; color: white; padding: 0 2rem;
    }

    .hero-badge {
      background: rgba(242, 201, 76, 0.15); border: 1px solid var(--kemenkeu-gold);
      color: var(--kemenkeu-light-gold); padding: 0.4rem 1.2rem; border-radius: 50px;
      font-size: 0.85rem; font-weight: 600; margin-bottom: 1.5rem; text-transform: uppercase;
    }

    .hero h1 { font-size: 2.8rem; font-weight: 800; color: #FFFFFF; margin-bottom: 1rem; }
    .hero p { max-width: 800px; font-size: 1.1rem; color: #E2E8F0; margin-bottom: 2rem; }

    .hero-scroll-btn {
      background: var(--kemenkeu-gold); color: var(--kemenkeu-main);
      padding: 0.8rem 2rem; border-radius: 30px; font-weight: bold; text-decoration: none;
    }

    .content-container { max-width: 1200px; margin: 4rem auto; padding: 0 1.5rem; }
    .section-title { text-align: center; margin-bottom: 3rem; }
    .section-title h2 { font-size: 2rem; color: var(--kemenkeu-main); }

    .notice-card {
      background: white; border-left: 5px solid var(--kemenkeu-main);
      padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-bottom: 3rem; display: flex; align-items: center; gap: 15px;
    }

    .vehicle-grid {
      display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; align-items: stretch;
    }

    @media (max-width: 992px) { .vehicle-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .vehicle-grid { grid-template-columns: 1fr; } }

    .vehicle-card {
      background: var(--card-white); border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #E2E8F0;
      text-align: center; padding: 1.5rem; display: flex; flex-direction: column;
      justify-content: space-between; height: 100%;
    }

    .vehicle-img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; }
    .vehicle-name { font-size: 1.2rem; color: var(--kemenkeu-main); margin-bottom: 1rem; font-weight: 700; }

    .badge {
      display: inline-block; padding: 0.35rem 1rem; border-radius: 50px;
      font-size: 0.85rem; font-weight: 700; margin: 0 auto 1rem auto; width: fit-content;
    }

    .badge-available { background-color: #E8F8F5; color: #117A65; border: 1px solid #A3E4D7; }
    .badge-pending { background-color: #FEF3C7; color: #D97706; border: 1px solid #FCD34D; }
    .badge-busy { background-color: #FDEDEC; color: #C0392B; border: 1px solid #F9E79F; }

    .vehicle-desc { font-size: 0.85rem; color: #7F8C8D; margin-bottom: 1.5rem; min-height: 3rem; }

    .btn-action {
      display: block; width: 100%; padding: 0.8rem; border-radius: 6px;
      font-weight: 700; text-decoration: none; text-align: center; margin-top: auto;
    }

    .btn-primary { background-color: var(--kemenkeu-main); color: white; }
    .btn-disabled { background-color: #BDC3C7; color: #555; cursor: not-allowed; }

    footer {
      background-color: var(--kemenkeu-main); color: white; text-align: center;
      padding: 1.5rem; margin-top: 5rem; border-top: 3px solid var(--kemenkeu-gold); font-size: 0.85rem;
    }
  </style>
</head>
<body>

  <header>
    <div class="brand-container">
      <img src="{{ asset('images/kemenkeu.jpg') }}" alt="Logo Kemenkeu RI" class="brand-logo-img">
      <div class="brand-text">
        <h2>KPKNL METRO</h2>
        <p>KEMENTERIAN KEUANGAN RI</p>
      </div>
    </div>
    <a href="/login" class="admin-btn">Login Admin</a>
  </header>

  <section class="hero">
    <div class="hero-badge">Direktorat Jenderal Kekayaan Negara</div>
    <h1>KPKNL METRO</h1>
    <p>
      Kantor Pelayanan Kekayaan Negara dan Lelang (KPKNL) Metro merupakan unit vertikal di bawah naungan <strong>Direktorat Jenderal Kekayaan Negara (DJKN)</strong>.
    </p>
    <a href="#peminjaman" class="hero-scroll-btn">Lihat Layanan Peminjaman Kendaraan ↓</a>
  </section>

  <div class="content-container" id="peminjaman">
    <div class="notice-card">
      <div style="font-size: 1.8rem;">ℹ️</div>
      <div>
        <strong style="color: var(--kemenkeu-main);">Ketentuan Peminjaman Kendaraan Dinas:</strong>
        <p style="font-size: 0.9rem; color: #555;">Fasilitas peminjaman kendaraan operasional kantor ini khusus diperuntukkan bagi Pegawai / Pejabat KPKNL Metro dalam rangka pelaksanaan tugas dinas resmi.</p>
      </div>
    </div>

    <div class="section-title">
      <h2>Peminjaman dan Jenis Kendaraan</h2>
    </div>

    <div class="vehicle-grid">
      <!-- Mobil 1 -->
      <div class="vehicle-card" data-name="Toyota Rush - BE 1007 FZ" data-id="1">
        <img src="{{ asset('images/toyotarush.jpg') }}" class="vehicle-img">
        <div class="vehicle-name">Toyota Rush - BE 1007 FZ</div>
        <div class="status-container"></div>
      </div>

      <!-- Mobil 2 -->
      <div class="vehicle-card" data-name="Toyota Rush - BE 1068 FZ" data-id="2">
        <img src="{{ asset('images/toyotarush2.jpg') }}" class="vehicle-img">
        <div class="vehicle-name">Toyota Rush - BE 1068 FZ</div>
        <div class="status-container"></div>
      </div>

      <!-- Mobil 3 -->
      <div class="vehicle-card" data-name="Toyota Kijang Innova - BE 1101 FZ" data-id="3">
        <img src="{{ asset('images/innova.jpg') }}" class="vehicle-img">
        <div class="vehicle-name">Toyota Kijang Innova - BE 1101 FZ</div>
        <div class="status-container"></div>
      </div>

      <!-- Mobil 4 -->
      <div class="vehicle-card" data-name="Mitsubishi Xpander - BE 1006 FZ" data-id="4">
        <img src="{{ asset('images/xpander.jpg') }}" class="vehicle-img">
        <div class="vehicle-name">Mitsubishi Xpander - BE 1006 FZ</div>
        <div class="status-container"></div>
      </div>

      <!-- Mobil 5 -->
      <div class="vehicle-card" data-name="Toyota Hilux - B 9440 PSE" data-id="5">
        <img src="{{ asset('images/hilux.jpg') }}" class="vehicle-img">
        <div class="vehicle-name">Toyota Hilux - B 9440 PSE</div>
        <div class="status-container"></div>
      </div>
    </div>
  </div>

  <footer>
    <p>&copy; 2026 KPKNL Metro - Direktorat Jenderal Kekayaan Negara | Kementerian Keuangan RI</p>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
      const cards = document.querySelectorAll('.vehicle-card');
      const today = new Date().toISOString().split('T')[0];

      cards.forEach((card) => {
        const namaMobil = card.getAttribute('data-name');
        const mobilId = card.getAttribute('data-id') || '1';
        const container = card.querySelector('.status-container');
        const dataPinjam = statusMobil[namaMobil];

        if (dataPinjam && dataPinjam.tglKembali >= today) {
          if (dataPinjam.statusApproval === 'Pending') {
            container.innerHTML = `
              <span class="badge badge-pending">⏳ Menunggu Persetujuan</span>
              <p class="vehicle-desc" style="color: var(--warning); font-weight: 600;">
                Diajukan oleh <strong>${dataPinjam.peminjam}</strong><br>
                <small style="color: #7F8C8D;">(Menunggu konfirmasi admin)</small>
              </p>
              <span class="btn-action btn-disabled">Prosedur Verifikasi</span>
            `;
          } else {
            container.innerHTML = `
              <span class="badge badge-busy">✕ Dalam Masa Dinas</span>
              <p class="vehicle-desc" style="color: var(--danger); font-weight: 600;">
                Sedang dipinjam oleh <strong>${dataPinjam.peminjam}</strong><br>
                <small style="color: #7F8C8D;">(s.d. ${dataPinjam.tglKembali})</small>
              </p>
              <span class="btn-action btn-disabled">Tidak Dapat Dipinjam</span>
            `;
          }
        } else {
          if (dataPinjam && dataPinjam.tglKembali < today) {
            delete statusMobil[namaMobil];
            localStorage.setItem('statusMobil', JSON.stringify(statusMobil));
          }

          container.innerHTML = `
            <span class="badge badge-available">✓ Belum Dipinjam</span>
            <p class="vehicle-desc">Kendaraan siap digunakan untuk perjalanan dinas resmi kantor.</p>
            <a href="/pinjam/${mobilId}" class="btn-action btn-primary">Pinjam Kendaraan</a>
          `;
        }
      });
    });
  </script>

</body>
</html>