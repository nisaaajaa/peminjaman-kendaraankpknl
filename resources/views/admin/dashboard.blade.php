<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - KPKNL Metro</title>
  <style>
    :root {
      --kemenkeu-main: #062145;
      --kemenkeu-gold: #F2C94C;
      --bg-gray: #F8FAFC;
      --text-dark: #1E293B;
      --danger: #E74C3C;
      --success: #2ECC71;
      --warning: #F39C12;
      --info: #3498DB;
      --secondary: #6C757D;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background-color: var(--bg-gray); color: var(--text-dark); line-height: 1.6; }

    header {
      background-color: var(--kemenkeu-main);
      border-bottom: 3px solid var(--kemenkeu-gold);
      padding: 1rem 3rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .brand-title h2 { color: #FFFFFF; font-size: 1.2rem; }
    .brand-title p { color: var(--kemenkeu-gold); font-size: 0.75rem; font-weight: bold; }

    .btn-logout {
      background-color: var(--danger); color: white;
      padding: 0.5rem 1.2rem; border-radius: 6px;
      text-decoration: none; font-weight: bold; font-size: 0.9rem;
    }

    .container { width: 95%; max-width: 1800px; margin: 2rem auto; padding: 0 1.5rem; }

    /* Navigasi Tab */
    .nav-tabs { display: flex; gap: 10px; margin-bottom: 1.5rem; border-bottom: 2px solid #CBD5E1; }
    .tab-btn {
      padding: 0.8rem 1.2rem; border: none; background: transparent; font-size: 0.95rem;
      font-weight: bold; color: var(--secondary); cursor: pointer; border-bottom: 3px solid transparent;
      transition: all 0.2s;
    }
    .tab-btn.active { color: var(--kemenkeu-main); border-bottom-color: var(--kemenkeu-gold); background: #E2E8F0; }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .card-table { background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 1.5rem; margin-bottom: 2rem; }
    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }

    table { width: 100%; border-collapse: collapse; margin-top: 0.5rem; }
    th, td { padding: 0.8rem 1rem; text-align: left; border-bottom: 1px solid #E2E8F0; font-size: 0.88rem; }
    th { background-color: #F1F5F9; color: var(--kemenkeu-main); font-weight: 700; }

    .badge { padding: 0.35rem 0.8rem; border-radius: 50px; font-size: 0.78rem; font-weight: bold; display: inline-block; }
    .badge-pending { background: #FEF3C7; color: #D97706; }
    .badge-approved { background: #D1FAE5; color: #059669; }
    .badge-rejected { background: #FEE2E2; color: #DC2626; }

    .action-group { display: flex; gap: 5px; flex-wrap: wrap; }
    .btn-act { padding: 0.35rem 0.65rem; border-radius: 4px; border: none; color: white; font-weight: bold; cursor: pointer; font-size: 0.78rem; text-decoration: none; }
    .btn-add { background-color: var(--kemenkeu-main); padding: 0.5rem 1rem; }
    .btn-approve { background-color: var(--success); }
    .btn-reject { background-color: var(--danger); }
    .btn-edit { background-color: var(--info); }
    .btn-delete { background-color: var(--danger); }
    .btn-act:hover { opacity: 0.85; }

    /* Media Queries for Mobile Responsiveness */
    @media (max-width: 768px) {
      .container { width: 100%; padding: 0 1rem; margin: 1rem auto; }
      header { padding: 1rem; flex-direction: column; gap: 1rem; text-align: center; }
      .brand-title h2 { font-size: 1.1rem; }
      .nav-tabs { overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; padding-bottom: 0.5rem; }
      .nav-tabs::-webkit-scrollbar { height: 4px; }
      .nav-tabs::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
      .card-header { flex-direction: column; gap: 1rem; align-items: stretch; text-align: center; }
      .card-header h2 { font-size: 1.2rem; }
      
      table, thead, tbody, th, td, tr { display: block; }
      thead tr { position: absolute; top: -9999px; left: -9999px; }
      tr { margin-bottom: 1rem; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0.5rem 1rem; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
      td { border: none; border-bottom: 1px solid #F1F5F9; position: relative; padding: 0.8rem 0 0.8rem 45%; text-align: right; min-height: 2.5rem; }
      td:last-child { border-bottom: 0; }
      td::before { 
        content: attr(data-label); position: absolute; left: 0; width: 40%; 
        white-space: nowrap; text-align: left; font-weight: bold; color: var(--kemenkeu-main); font-size: 0.85rem; top: 0.8rem;
      }
      .action-group { justify-content: flex-end; margin-top: 0.5rem; }
      .btn-act { padding: 0.6rem 0.8rem; flex: 1; text-align: center; }
    }
  </style>
</head>
<body>

  <header>
    <div class="brand-title">
      <h2>PANEL ADMIN - SISTEM PEMINJAMAN KENDARAAN</h2>
      <p>KPKNL METRO</p>
    </div>
    <a href="/logout" class="btn-logout">Logout</a>
  </header>

  <div class="container">
    <!-- Navigasi Tab -->
    <div class="nav-tabs">
      <button class="tab-btn active" onclick="switchTab('peminjaman')">📋 CRUD Peminjaman</button>
      <button class="tab-btn" onclick="switchTab('kendaraan')">🚗 Kendaraan</button>
      <button class="tab-btn" onclick="switchTab('pengembalian')">🔄 Pengembalian</button>
      <button class="tab-btn" onclick="switchTab('user')">👤 User</button>
      <button class="tab-btn" onclick="switchTab('log')">🕒 Log Aktivitas</button>
    </div>

    <!-- Tab 1: Peminjaman -->
    <div id="tab-peminjaman" class="tab-content active card-table">
      <div class="card-header">
        <h2>Daftar Pengajuan Peminjaman</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>Waktu Input</th>
            <th>NIP</th>
            <th>Pegawai</th>
            <th>Kendaraan</th>
            <th>Masa Pinjam</th>
            <th>Keperluan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($loans as $loan)
          <tr>
            <td data-label="Waktu Input"><small>{{ $loan->created_at->format('d/m/Y H:i') }}</small></td>
            <td data-label="NIP">{{ $loan->nip }}</td>
            <td data-label="Pegawai"><strong>{{ $loan->nama_peminjam }}</strong></td>
            <td data-label="Kendaraan">{{ $loan->vehicle ? $loan->vehicle->nama_kendaraan : 'Mobil Dihapus' }}</td>
            <td data-label="Masa Pinjam">{{ $loan->masa_pinjam }}</td>
            <td data-label="Keperluan">{{ $loan->keperluan }}</td>
            <td data-label="Status"><span class="badge badge-pending">Menunggu</span></td>
            <td data-label="Aksi">
              <div class="action-group">
                <button class="btn-act btn-approve" onclick="alert('Fitur Setuju tahap pengembangan')">Setujui</button>
                <button class="btn-act btn-reject" onclick="alert('Fitur Tolak tahap pengembangan')">Tolak</button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center; color:#888;">Belum ada pengajuan peminjaman di Database.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Tab 3: Kendaraan -->
    <div id="tab-kendaraan" class="tab-content card-table">
      <div class="card-header">
        <h2>Kelola Data Kendaraan Dinas</h2>
        <button class="btn-act btn-add" onclick="alert('Fitur Tambah tahap pengembangan')">+ Tambah Kendaraan</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama & Plat Kendaraan</th>
            <th>Status Kendaraan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($vehicles as $v)
          <tr>
            <td data-label="ID">#{{ $v->id }}</td>
            <td data-label="Nama & Plat"><strong>{{ $v->nama_kendaraan }}</strong></td>
            <td data-label="Status Kendaraan">
              @if($v->status == 'tersedia')
                <span class="badge badge-approved">Tersedia</span>
              @else
                <span class="badge badge-rejected">Dipinjam</span>
              @endif
            </td>
            <td data-label="Aksi">
              <div class="action-group">
                <button class="btn-act btn-edit" onclick="alert('Fitur Edit tahap pengembangan')">Edit</button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center; color:#888;">Tidak ada kendaraan di Database.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Tab 2: Pengembalian (Tahap Pengembangan) -->
    <div id="tab-pengembalian" class="tab-content card-table">
      <div class="card-header">
        <h2>Monitoring Pengembalian</h2>
      </div>
      <p style="text-align:center; padding: 2rem; color: #888;">Sedang dalam tahap migrasi ke backend (Tahap Pengembangan).</p>
    </div>

    <!-- Tab 4: User -->
    <div id="tab-user" class="tab-content card-table">
      <div class="card-header">
        <h2>Kelola Data User & Pegawai</h2>
        <div>
          <button class="btn-act btn-add" onclick="alert('Fitur Tambah tahap pengembangan')">+ Tambah Pegawai</button>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>NIP</th>
            <th>Nama Pegawai</th>
            <th>Terdaftar Sejak</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($employees as $emp)
          <tr>
            <td data-label="ID">#{{ $emp->id }}</td>
            <td data-label="NIP"><strong>{{ $emp->nip }}</strong></td>
            <td data-label="Nama Pegawai">{{ $emp->nama_pegawai }}</td>
            <td data-label="Terdaftar Sejak">{{ $emp->created_at->format('d/m/Y') }}</td>
            <td data-label="Aksi">
              <div class="action-group">
                <button class="btn-act btn-edit" onclick="alert('Fitur Edit tahap pengembangan')">Edit</button>
                <button class="btn-act btn-delete" onclick="alert('Fitur Hapus tahap pengembangan')">Hapus</button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center; color:#888;">Tidak ada data pegawai di Database.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Tab 5: Log Aktivitas (Tahap Pengembangan) -->
    <div id="tab-log" class="tab-content card-table">
      <div class="card-header">
        <h2>Log Aktivitas</h2>
      </div>
      <p style="text-align:center; padding: 2rem; color: #888;">Sedang dalam tahap migrasi ke backend (Tahap Pengembangan).</p>
    </div>
  </div>

  <script>
    function switchTab(tabName) {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      event.target.classList.add('active');
      document.getElementById('tab-' + tabName).classList.add('active');
    }
  </script>

</body>
</html>