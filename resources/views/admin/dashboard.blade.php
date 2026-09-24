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

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
    .modal-content { background-color: #fff; margin: 10% auto; padding: 2rem; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close:hover { color: black; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; font-size: 0.9rem; }
    .form-group input { width: 100%; padding: 0.8rem; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 1rem; }
    .btn-submit { background-color: var(--kemenkeu-main); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 1rem; }
    .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 4px; font-weight: bold; }
    .alert-success { background-color: #D1FAE5; color: #059669; }
    .alert-danger { background-color: #FEE2E2; color: #DC2626; }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <!-- Pesan Alert lama diganti dengan Toast SweetAlert di script bawah -->

    <!-- Navigasi Tab -->
    <div class="nav-tabs">
      <button class="tab-btn active" onclick="switchTab('peminjaman')">📋 Peminjaman</button>
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
          @forelse($pendingLoans as $loan)
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
                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button type="button" class="btn-act btn-approve" onclick="confirmAction(this.form, 'Setujui peminjaman ini?')">Setujui</button>
                </form>
                <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button type="button" class="btn-act btn-reject" onclick="confirmAction(this.form, 'Tolak peminjaman ini?')">Tolak</button>
                </form>
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
        <button class="btn-act btn-add" onclick="openModal('modalAddVehicle')">+ Tambah Kendaraan</button>
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
            <td data-label="Nama & Plat">
              @if($v->foto)
                <img src="{{ asset('images/' . $v->foto) }}" alt="Foto" style="height: 40px; width: 60px; object-fit: cover; border-radius: 4px; vertical-align: middle; margin-right: 10px;">
              @else
                <div style="display:inline-block; height:40px; width:60px; background:#e2e8f0; border-radius:4px; vertical-align:middle; margin-right:10px; text-align:center; line-height:40px; font-size:10px; color:#64748b;">No Image</div>
              @endif
              <strong>{{ $v->nama_kendaraan }} ({{ $v->plat_nomor }})</strong>
            </td>
            <td data-label="Status Kendaraan">
              @if($v->status == 'tersedia')
                <span class="badge badge-approved">Tersedia</span>
              @else
                <span class="badge badge-rejected">Dipinjam</span>
              @endif
            </td>
            <td data-label="Aksi">
              <div class="action-group">
                <button class="btn-act btn-edit" onclick="openEditVehicleModal({{ $v->id }}, '{{ addslashes($v->nama_kendaraan) }}', '{{ addslashes($v->plat_nomor) }}')">Edit</button>
                <form action="{{ route('admin.vehicles.delete', $v->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn-act btn-delete" onclick="confirmAction(this.form, 'Yakin ingin menghapus kendaraan ini?')">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center; color:#888;">Tidak ada kendaraan di Database.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Tab 2: Pengembalian -->
    <div id="tab-pengembalian" class="tab-content card-table">
      <div class="card-header">
        <h2>Monitoring Pengembalian Kendaraan</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>NIP</th>
            <th>Pegawai</th>
            <th>Kendaraan</th>
            <th>Masa Pinjam</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($approvedLoans as $loan)
          <tr>
            <td data-label="NIP">{{ $loan->nip }}</td>
            <td data-label="Pegawai"><strong>{{ $loan->nama_peminjam }}</strong></td>
            <td data-label="Kendaraan">{{ $loan->vehicle ? $loan->vehicle->nama_kendaraan : 'Mobil Dihapus' }}</td>
            <td data-label="Masa Pinjam">{{ $loan->masa_pinjam }}</td>
            <td data-label="Status"><span class="badge badge-approved">Sedang Dipinjam</span></td>
            <td data-label="Aksi">
              <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST">
                @csrf
                <button type="button" class="btn-act btn-info" style="background-color: var(--info); color:white;" onclick="confirmAction(this.form, 'Konfirmasi kendaraan telah dikembalikan dengan aman?')">Selesaikan / Dikembalikan</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center; color:#888;">Tidak ada kendaraan yang sedang dipinjam saat ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Tab 4: User -->
    <div id="tab-user" class="tab-content card-table">
      <div class="card-header">
        <h2>Kelola Data User & Pegawai</h2>
        <div class="action-group" style="justify-content: flex-end;">
          <a href="{{ route('admin.employees.export') }}" class="btn-act" style="background-color: var(--success); text-decoration:none;">📤 Ekspor CSV</a>
          <button class="btn-act" onclick="openModal('modalImportCsv')" style="background-color: var(--info);">📥 Impor CSV</button>
          <button class="btn-act btn-add" onclick="openModal('modalAddEmployee')">+ Tambah Pegawai</button>
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
                <button class="btn-act btn-edit" onclick="openEditEmployeeModal({{ $emp->id }}, '{{ $emp->nip }}', '{{ addslashes($emp->nama_pegawai) }}')">Edit</button>
                <form action="{{ route('admin.employees.delete', $emp->id) }}" method="POST" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn-act btn-delete" onclick="confirmAction(this.form, 'Yakin ingin menghapus pegawai ini?')">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center; color:#888;">Tidak ada data pegawai di Database.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Tab 5: Log Aktivitas -->
    <div id="tab-log" class="tab-content card-table">
      <div class="card-header">
        <h2>Riwayat Peminjaman (Log)</h2>
      </div>
      <table>
        <thead>
          <tr>
            <th>Waktu Selesai</th>
            <th>Pegawai</th>
            <th>Kendaraan</th>
            <th>Keperluan</th>
            <th>Status Akhir</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logLoans as $loan)
          <tr>
            <td data-label="Waktu Selesai"><small>{{ $loan->updated_at->format('d/m/Y H:i') }}</small></td>
            <td data-label="Pegawai"><strong>{{ $loan->nama_peminjam }}</strong></td>
            <td data-label="Kendaraan">{{ $loan->vehicle ? $loan->vehicle->nama_kendaraan : 'Mobil Dihapus' }}</td>
            <td data-label="Keperluan">{{ $loan->keperluan }}</td>
            <td data-label="Status Akhir">
              @if($loan->status === 'returned')
                <span class="badge badge-success" style="background: #D1FAE5; color: #059669;">Selesai/Dikembalikan</span>
              @else
                <span class="badge badge-danger" style="background: #FEE2E2; color: #DC2626;">Ditolak</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center; color:#888;">Belum ada riwayat aktivitas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Tambah Kendaraan -->
  <div id="modalAddVehicle" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Tambah Kendaraan Baru</h2>
        <span class="close" onclick="closeModal('modalAddVehicle')">&times;</span>
      </div>
      <form action="{{ route('admin.vehicles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label>Nama Kendaraan (Contoh: Toyota Rush)</label>
          <input type="text" name="nama_kendaraan" required>
        </div>
        <div class="form-group">
          <label>Plat Nomor</label>
          <input type="text" name="plat_nomor" required>
        </div>
        <div class="form-group">
          <label>Foto Kendaraan</label>
          <input type="file" name="foto" accept="image/*" style="padding: 0.5rem; border: none;">
        </div>
        <button type="submit" class="btn-submit">Simpan Kendaraan</button>
      </form>
    </div>
  </div>

  <!-- Modal Edit Kendaraan -->
  <div id="modalEditVehicle" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Kendaraan</h2>
        <span class="close" onclick="closeModal('modalEditVehicle')">&times;</span>
      </div>
      <form id="formEditVehicle" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>Nama Kendaraan</label>
          <input type="text" id="edit_v_nama" name="nama_kendaraan" required>
        </div>
        <div class="form-group">
          <label>Plat Nomor</label>
          <input type="text" id="edit_v_plat" name="plat_nomor" required>
        </div>
        <div class="form-group">
          <label>Ubah Foto (Opsional)</label>
          <input type="file" name="foto" accept="image/*" style="padding: 0.5rem; border: none;">
        </div>
        <button type="submit" class="btn-submit">Update Kendaraan</button>
      </form>
    </div>
  </div>

  <!-- Modal Tambah Pegawai -->
  <div id="modalAddEmployee" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Tambah Pegawai Baru</h2>
        <span class="close" onclick="closeModal('modalAddEmployee')">&times;</span>
      </div>
      <form action="{{ route('admin.employees.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label>NIP</label>
          <input type="text" name="nip" required placeholder="Masukkan NIP (18 digit)">
        </div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="nama_pegawai" required placeholder="Masukkan Nama Lengkap">
        </div>
        <button type="submit" class="btn-submit">Simpan Data</button>
      </form>
    </div>
  </div>

  <!-- Modal Edit Pegawai -->
  <div id="modalEditEmployee" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Edit Data Pegawai</h2>
        <span class="close" onclick="closeModal('modalEditEmployee')">&times;</span>
      </div>
      <form id="formEditEmployee" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label>NIP</label>
          <input type="text" id="edit_nip" name="nip" required>
        </div>
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" id="edit_nama" name="nama_pegawai" required>
        </div>
        <button type="submit" class="btn-submit">Update Data</button>
      </form>
    </div>
  </div>

  <!-- Modal Impor CSV -->
  <div id="modalImportCsv" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Impor Data Pegawai (CSV)</h2>
        <span class="close" onclick="closeModal('modalImportCsv')">&times;</span>
      </div>
      <form action="{{ route('admin.employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label>Pilih File (.csv)</label>
          <input type="file" name="csv_file" accept=".csv" required style="padding: 0.5rem; border: none;">
        </div>
        <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem; line-height: 1.4;">
          <strong>Panduan:</strong> Format file harus CSV. Baris pertama (Header) akan diabaikan. Pastikan NIP berada di kolom pertama (A), dan Nama Pegawai di kolom kedua (B).<br><br>
          <em>*Jika NIP sudah ada di database, sistem akan memperbarui nama pegawai tersebut.</em>
        </p>
        <button type="submit" class="btn-submit">Upload & Proses Data</button>
      </form>
    </div>
  </div>

  <script>
    // Tab persistency logic
    function switchTab(tabName) {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
      
      const targetBtn = document.querySelector(`.tab-btn[onclick="switchTab('${tabName}')"]`);
      if (targetBtn) targetBtn.classList.add('active');
      
      document.getElementById('tab-' + tabName).classList.add('active');
      localStorage.setItem('activeAdminTab', tabName);
    }

    document.addEventListener("DOMContentLoaded", function() {
      const savedTab = localStorage.getItem('activeAdminTab');
      if (savedTab) {
        switchTab(savedTab);
      }
    });

    // Modal functions
    function openModal(id) { document.getElementById(id).style.display = 'block'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function openEditEmployeeModal(id, nip, nama) {
      document.getElementById('formEditEmployee').action = '/admin/employees/' + id;
      document.getElementById('edit_nip').value = nip;
      document.getElementById('edit_nama').value = nama;
      openModal('modalEditEmployee');
    }

    function openEditVehicleModal(id, nama, plat) {
      document.getElementById('formEditVehicle').action = '/admin/vehicles/' + id;
      document.getElementById('edit_v_nama').value = nama;
      document.getElementById('edit_v_plat').value = plat;
      openModal('modalEditVehicle');
    }

    // SweetAlert2 Configurations
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    // Handle session flashes
    @if(session('success'))
      Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
    @endif
    
    @if($errors->any())
      Toast.fire({ icon: 'error', title: "Terjadi kesalahan. Periksa form anda!" });
    @endif

    function confirmAction(form, message) {
      Swal.fire({
        title: 'Konfirmasi',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--kemenkeu-main)',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    }
  </script>

</body>
</html>