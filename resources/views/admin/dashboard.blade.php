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

    .container { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }

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
    .btn-act { padding: 0.35rem 0.65rem; border-radius: 4px; border: none; color: white; font-weight: bold; cursor: pointer; font-size: 0.78rem; }
    .btn-add { background-color: var(--kemenkeu-main); padding: 0.5rem 1rem; }
    .btn-approve { background-color: var(--success); }
    .btn-reject { background-color: var(--danger); }
    .btn-edit { background-color: var(--info); }
    .btn-delete { background-color: var(--danger); }
    .btn-act:hover { opacity: 0.85; }

    /* Modal Form */
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; justify-content: center; align-items: center; }
    .modal-content { background: white; padding: 2rem; border-radius: 8px; width: 400px; max-width: 90%; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: 0.85rem; font-weight: bold; margin-bottom: 0.3rem; }
    .form-group input, .form-group select { width: 100%; padding: 0.5rem; border: 1px solid #CBD5E1; border-radius: 4px; }
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
      <button class="tab-btn" onclick="switchTab('pengembalian')">🔄 CRUD Pengembalian</button>
      <button class="tab-btn" onclick="switchTab('kendaraan')">🚗 CRUD Kendaraan</button>
      <button class="tab-btn" onclick="switchTab('user')">👤 CRUD User</button>
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
            <th>Pegawai</th>
            <th>Seksi</th>
            <th>Kendaraan</th>
            <th>Masa Pinjam</th>
            <th>Keperluan</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tb-peminjaman"></tbody>
      </table>
    </div>

    <!-- Tab 2: Pengembalian -->
    <div id="tab-pengembalian" class="tab-content card-table">
      <div class="card-header">
        <h2>Monitoring & CRUD Pengembalian</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>Pegawai</th>
            <th>Kendaraan</th>
            <th>Batas Kembali</th>
            <th>Status Pengembalian</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tb-pengembalian"></tbody>
      </table>
    </div>

    <!-- Tab 3: Kendaraan -->
    <div id="tab-kendaraan" class="tab-content card-table">
      <div class="card-header">
        <h2>Kelola Data Kendaraan Dinas</h2>
        <button class="btn-act btn-add" onclick="openModalKendaraan()">+ Tambah Kendaraan</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>Nama & Plat Kendaraan</th>
            <th>Kondisi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tb-kendaraan"></tbody>
      </table>
    </div>

    <!-- Tab 4: User -->
    <div id="tab-user" class="tab-content card-table">
      <div class="card-header">
        <h2>Kelola Data User & Pegawai</h2>
        <div>
          <button class="btn-act btn-delete" onclick="clearAllUsers()" style="margin-right: 5px;">🗑️ Hapus Semua User</button>
          <button class="btn-act btn-add" onclick="openModalUser()">+ Tambah User</button>
        </div>
      </div>
      <table>
        <thead>
          <tr>
            <th>Nama Pegawai</th>
            <th>Seksi</th>
            <th>Role</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tb-user"></tbody>
      </table>
    </div>

    <!-- Tab 5: Log Aktivitas -->
    <div id="tab-log" class="tab-content card-table">
      <div class="card-header">
        <h2>Log Aktivitas Penginputan System</h2>
        <button class="btn-act btn-delete" onclick="clearAllLogs()">Hapus Semua Log</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>Waktu (Timestamp)</th>
            <th>Pegawai / User</th>
            <th>Aktivitas</th>
            <th>Rincian</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tb-log"></tbody>
      </table>
    </div>
  </div>

  <!-- Modal User -->
  <div id="modalUser" class="modal">
    <div class="modal-content">
      <h3 style="margin-bottom:1rem;">Tambah / Edit User</h3>
      <input type="hidden" id="userIndex">
      <div class="form-group"><label>Nama</label><input type="text" id="uNama"></div>
      <div class="form-group">
        <label>Seksi</label>
        <select id="uSeksi">
          <option value="Seksi PKN">Seksi PKN</option>
          <option value="Subbag Umum">Subbag Umum</option>
          <option value="Seksi PN">Seksi PN</option>
          <option value="Seksi HI">Seksi HI</option>
          <option value="Seksi KI">Seksi KI</option>
          <option value="Jafung Pelelang">Jafung Pelelang</option>
          <option value="-">-</option>
        </select>
      </div>
      <div class="form-group">
        <label>Role</label>
        <select id="uRole"><option value="Pegawai">Pegawai</option><option value="Admin">Admin</option></select>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button class="btn-act btn-delete" onclick="closeModal('modalUser')">Batal</button>
        <button class="btn-act btn-approve" onclick="saveUser()">Simpan</button>
      </div>
    </div>
  </div>

  <!-- Modal Kendaraan -->
  <div id="modalKendaraan" class="modal">
    <div class="modal-content">
      <h3 style="margin-bottom:1rem;">Tambah / Edit Kendaraan</h3>
      <input type="hidden" id="kenIndex">
      <div class="form-group"><label>Nama & Plat</label><input type="text" id="kNama" placeholder="Toyota Rush - BE 1007 FZ"></div>
      <div class="form-group">
        <label>Kondisi</label>
        <select id="kKondisi"><option value="Baik">Baik</option><option value="Perbaikan">Perbaikan</option></select>
      </div>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button class="btn-act btn-delete" onclick="closeModal('modalKendaraan')">Batal</button>
        <button class="btn-act btn-approve" onclick="saveKendaraan()">Simpan</button>
      </div>
    </div>
  </div>

  <!-- Modal Log -->
  <div id="modalLog" class="modal">
    <div class="modal-content">
      <h3 style="margin-bottom:1rem;">Edit Log Aktivitas</h3>
      <input type="hidden" id="logIndex">
      <div class="form-group"><label>Pegawai / User</label><input type="text" id="lUser"></div>
      <div class="form-group"><label>Aktivitas</label><input type="text" id="lAktivitas"></div>
      <div class="form-group"><label>Rincian</label><input type="text" id="lRincian"></div>
      <div style="display:flex; gap:10px; justify-content:flex-end;">
        <button class="btn-act btn-delete" onclick="closeModal('modalLog')">Batal</button>
        <button class="btn-act btn-approve" onclick="saveLog()">Simpan</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      initData();
      renderAll();
    });

    function switchTab(tabName) {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      event.target.classList.add('active');
      document.getElementById('tab-' + tabName).classList.add('active');
    }

    function initData() {
      // Inisialisasi awal hanya jika localStorage benar-benar kosong (pertama kali dibuka)
      if(localStorage.getItem('users') === null) {
        const initialUsers = [
          {nama: 'MOHAMAD RIYANTO', seksi: 'Seksi PKN', role: 'Pegawai'},
          {nama: 'ANGGA APRIANTO', seksi: 'Seksi PKN', role: 'Pegawai'},
          {nama: 'RUBIN HARYADI', seksi: 'Seksi PKN', role: 'Pegawai'},
          {nama: 'WAHIDIN HARYA DITAMA', seksi: 'Seksi PKN', role: 'Pegawai'},
          {nama: 'MARYANTO', seksi: 'Subbag Umum', role: 'Pegawai'},
          {nama: 'HABIB BURAKHMAN', seksi: 'Subbag Umum', role: 'Pegawai'},
          {nama: 'ADHYTIA PRATAMA ALBEN', seksi: 'Subbag Umum', role: 'Pegawai'}
        ];
        localStorage.setItem('users', JSON.stringify(initialUsers));
      }

      if(localStorage.getItem('kendaraanList') === null) {
        localStorage.setItem('kendaraanList', JSON.stringify([
          {nama: 'Toyota Rush - BE 1007 FZ', kondisi: 'Baik'},
          {nama: 'Toyota Rush - BE 1068 FZ', kondisi: 'Baik'},
          {nama: 'Toyota Kijang Innova - BE 1101 FZ', kondisi: 'Baik'}
        ]));
      }

      if(localStorage.getItem('logs') === null) {
        localStorage.setItem('logs', JSON.stringify([]));
      }
    }

    function addLog(user, aktivitas, rincian) {
      let logs = JSON.parse(localStorage.getItem('logs') || '[]');
      let now = new Date().toLocaleString('id-ID');
      logs.unshift({ waktu: now, user: user, aktivitas: aktivitas, rincian: rincian });
      localStorage.setItem('logs', JSON.stringify(logs));
    }

    function renderAll() {
      renderPeminjaman();
      renderPengembalian();
      renderKendaraan();
      renderUsers();
      renderLogs();
    }

    /* 1. CRUD Peminjaman */
    function renderPeminjaman() {
      let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
      const tbody = document.getElementById('tb-peminjaman');
      tbody.innerHTML = '';
      const keys = Object.keys(statusMobil);

      if (keys.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#888;">Belum ada pengajuan peminjaman.</td></tr>`;
        return;
      }

      keys.forEach((key) => {
        const item = statusMobil[key];
        let statusBadge = `<span class="badge badge-pending">Menunggu</span>`;
        if (item.statusApproval === 'Disetujui') statusBadge = `<span class="badge badge-approved">Disetujui</span>`;
        if (item.statusApproval === 'Ditolak') statusBadge = `<span class="badge badge-rejected">Ditolak</span>`;

        tbody.innerHTML += `
          <tr>
            <td><small>${item.waktuInput || '-'}</small></td>
            <td><strong>${item.peminjam || '-'}</strong></td>
            <td>${item.seksi || '-'}</td>
            <td>${key}</td>
            <td>${item.tglPinjam || '-'} s.d. ${item.tglKembali || '-'}</td>
            <td>${item.keperluan || '-'}</td>
            <td>${statusBadge}</td>
            <td>
              <div class="action-group">
                <button class="btn-act btn-approve" onclick="updateStatus('${key}', 'Disetujui')">Setujui</button>
                <button class="btn-act btn-reject" onclick="updateStatus('${key}', 'Ditolak')">Tolak</button>
                <button class="btn-act btn-edit" onclick="updateStatus('${key}', 'Pending')">Edit (Reset)</button>
                <button class="btn-act btn-delete" onclick="hapusPeminjaman('${key}')">Hapus</button>
              </div>
            </td>
          </tr>
        `;
      });
    }

    function updateStatus(key, status) {
      let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
      if (statusMobil[key]) {
        statusMobil[key].statusApproval = status;
        localStorage.setItem('statusMobil', JSON.stringify(statusMobil));
        addLog('Admin', 'Update Status Peminjaman', `${key} diubah ke ${status}`);
        renderAll();
      }
    }

    function hapusPeminjaman(key) {
      if (confirm(`Hapus pengajuan untuk ${key}?`)) {
        let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
        delete statusMobil[key];
        localStorage.setItem('statusMobil', JSON.stringify(statusMobil));
        addLog('Admin', 'Hapus Peminjaman', `Pengajuan ${key} dihapus`);
        renderAll();
      }
    }

    /* 2. CRUD Pengembalian */
    function renderPengembalian() {
      let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
      const tbody = document.getElementById('tb-pengembalian');
      tbody.innerHTML = '';
      const keys = Object.keys(statusMobil).filter(k => statusMobil[k].statusApproval === 'Disetujui');

      if (keys.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#888;">Tidak ada kendaraan yang sedang dipinjam.</td></tr>`;
        return;
      }

      keys.forEach((key) => {
        const item = statusMobil[key];
        const statusKembali = item.sudahKembali ? `<span class="badge badge-approved">Selesai Dikembalikan</span>` : `<span class="badge badge-pending">Belum Dikembalikan</span>`;

        tbody.innerHTML += `
          <tr>
            <td><strong>${item.peminjam}</strong></td>
            <td>${key}</td>
            <td>${item.tglKembali}</td>
            <td>${statusKembali}</td>
            <td>
              <div class="action-group">
                <button class="btn-act btn-approve" onclick="prosesPengembalian('${key}')">Konfirmasi Kembali</button>
                <button class="btn-act btn-delete" onclick="hapusPeminjaman('${key}')">Hapus Data</button>
              </div>
            </td>
          </tr>
        `;
      });
    }

    function prosesPengembalian(key) {
      if(confirm(`Konfirmasi bahwa kendaraan ${key} sudah dikembalikan?`)) {
        let statusMobil = JSON.parse(localStorage.getItem('statusMobil') || '{}');
        delete statusMobil[key];
        localStorage.setItem('statusMobil', JSON.stringify(statusMobil));
        addLog('Admin', 'Pengembalian Kendaraan', `Kendaraan ${key} telah resmi dikembalikan`);
        renderAll();
      }
    }

    /* 3. CRUD Kendaraan */
    function renderKendaraan() {
      let list = JSON.parse(localStorage.getItem('kendaraanList') || '[]');
      const tbody = document.getElementById('tb-kendaraan');
      tbody.innerHTML = '';
      list.forEach((item, idx) => {
        tbody.innerHTML += `
          <tr>
            <td><strong>${item.nama}</strong></td>
            <td>${item.kondisi}</td>
            <td>
              <div class="action-group">
                <button class="btn-act btn-edit" onclick="editKendaraan(${idx})">Edit</button>
                <button class="btn-act btn-delete" onclick="deleteKendaraan(${idx})">Hapus</button>
              </div>
            </td>
          </tr>
        `;
      });
    }

    function openModalKendaraan() { document.getElementById('kenIndex').value = ''; document.getElementById('kNama').value = ''; document.getElementById('modalKendaraan').style.display = 'flex'; }
    function editKendaraan(idx) {
      let list = JSON.parse(localStorage.getItem('kendaraanList'));
      document.getElementById('kenIndex').value = idx;
      document.getElementById('kNama').value = list[idx].nama;
      document.getElementById('kKondisi').value = list[idx].kondisi;
      document.getElementById('modalKendaraan').style.display = 'flex';
    }
    function saveKendaraan() {
      let list = JSON.parse(localStorage.getItem('kendaraanList') || '[]');
      let idx = document.getElementById('kenIndex').value;
      let data = { nama: document.getElementById('kNama').value, kondisi: document.getElementById('kKondisi').value };
      if (idx === '') { list.push(data); addLog('Admin', 'Tambah Kendaraan', data.nama); } 
      else { list[idx] = data; addLog('Admin', 'Edit Kendaraan', data.nama); }
      localStorage.setItem('kendaraanList', JSON.stringify(list));
      closeModal('modalKendaraan');
      renderAll();
    }
    function deleteKendaraan(idx) {
      if(confirm('Hapus kendaraan ini?')) {
        let list = JSON.parse(localStorage.getItem('kendaraanList'));
        addLog('Admin', 'Hapus Kendaraan', list[idx].nama);
        list.splice(idx, 1);
        localStorage.setItem('kendaraanList', JSON.stringify(list));
        renderAll();
      }
    }

    /* 4. CRUD User */
    function renderUsers() {
      let list = JSON.parse(localStorage.getItem('users') || '[]');
      const tbody = document.getElementById('tb-user');
      tbody.innerHTML = '';

      if (list.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" style="text-align:center; color:#888;">Tidak ada data user / pegawai.</td></tr>`;
        return;
      }

      list.forEach((item, idx) => {
        tbody.innerHTML += `
          <tr>
            <td><strong>${item.nama}</strong></td>
            <td>${item.seksi}</td>
            <td>${item.role}</td>
            <td>
              <div class="action-group">
                <button class="btn-act btn-edit" onclick="editUser(${idx})">Edit</button>
                <button class="btn-act btn-delete" onclick="deleteUser(${idx})">Hapus</button>
              </div>
            </td>
          </tr>
        `;
      });
    }

    function openModalUser() { 
      document.getElementById('userIndex').value = ''; 
      document.getElementById('uNama').value = ''; 
      document.getElementById('uSeksi').value = 'Seksi PKN'; 
      document.getElementById('modalUser').style.display = 'flex'; 
    }

    function editUser(idx) {
      let list = JSON.parse(localStorage.getItem('users'));
      document.getElementById('userIndex').value = idx;
      document.getElementById('uNama').value = list[idx].nama;
      document.getElementById('uSeksi').value = list[idx].seksi;
      document.getElementById('uRole').value = list[idx].role;
      document.getElementById('modalUser').style.display = 'flex';
    }

    function saveUser() {
      let list = JSON.parse(localStorage.getItem('users') || '[]');
      let idx = document.getElementById('userIndex').value;
      let data = { nama: document.getElementById('uNama').value, seksi: document.getElementById('uSeksi').value, role: document.getElementById('uRole').value };
      if (idx === '') { 
        list.push(data); 
        addLog('Admin', 'Tambah User', data.nama); 
      } else { 
        list[idx] = data; 
        addLog('Admin', 'Edit User', data.nama); 
      }
      localStorage.setItem('users', JSON.stringify(list));
      closeModal('modalUser');
      renderAll();
    }

    function deleteUser(idx) {
      let list = JSON.parse(localStorage.getItem('users') || '[]');
      let namaUser = list[idx].nama;
      if(confirm(`Apakah Anda yakin ingin menghapus user "${namaUser}"?`)) {
        addLog('Admin', 'Hapus User', namaUser);
        list.splice(idx, 1);
        localStorage.setItem('users', JSON.stringify(list));
        renderAll();
      }
    }

    /* Fitur Baru: Hapus Semua User */
    function clearAllUsers() {
      if(confirm('Apakah Anda yakin ingin menghapus SELURUH data user & pegawai?')) {
        localStorage.setItem('users', JSON.stringify([]));
        addLog('Admin', 'Hapus Semua User', 'Seluruh data user telah dihapus');
        renderAll();
      }
    }

    /* 5. CRUD Log Aktivitas */
    function renderLogs() {
      let logs = JSON.parse(localStorage.getItem('logs') || '[]');
      const tbody = document.getElementById('tb-log');
      tbody.innerHTML = '';
      if(logs.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; color:#888;">Belum ada log aktivitas.</td></tr>`;
        return;
      }
      logs.forEach((item, idx) => {
        tbody.innerHTML += `
          <tr>
            <td><small>${item.waktu}</small></td>
            <td><strong>${item.user}</strong></td>
            <td><span class="badge badge-pending">${item.aktivitas}</span></td>
            <td>${item.rincian || '-'}</td>
            <td>
              <div class="action-group">
                <button class="btn-act btn-edit" onclick="editLog(${idx})">Edit</button>
                <button class="btn-act btn-delete" onclick="deleteLog(${idx})">Hapus</button>
              </div>
            </td>
          </tr>
        `;
      });
    }

    function editLog(idx) {
      let logs = JSON.parse(localStorage.getItem('logs') || '[]');
      document.getElementById('logIndex').value = idx;
      document.getElementById('lUser').value = logs[idx].user;
      document.getElementById('lAktivitas').value = logs[idx].aktivitas;
      document.getElementById('lRincian').value = logs[idx].rincian || '';
      document.getElementById('modalLog').style.display = 'flex';
    }

    function saveLog() {
      let logs = JSON.parse(localStorage.getItem('logs') || '[]');
      let idx = document.getElementById('logIndex').value;
      if (idx !== '') {
        logs[idx].user = document.getElementById('lUser').value;
        logs[idx].aktivitas = document.getElementById('lAktivitas').value;
        logs[idx].rincian = document.getElementById('lRincian').value;
        localStorage.setItem('logs', JSON.stringify(logs));
        closeModal('modalLog');
        renderLogs();
      }
    }

    function deleteLog(idx) {
      if(confirm('Hapus log aktivitas ini?')) {
        let logs = JSON.parse(localStorage.getItem('logs') || '[]');
        logs.splice(idx, 1);
        localStorage.setItem('logs', JSON.stringify(logs));
        renderLogs();
      }
    }

    function clearAllLogs() {
      if(confirm('Apakah Anda yakin ingin menghapus SELURUH log aktivitas?')) {
        localStorage.setItem('logs', JSON.stringify([]));
        renderLogs();
      }
    }

    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
  </script>

</body>
</html>