import { auth, db, storage } from './firebase-config.js';
import { requireAuth } from './auth.js';
import { collection, getDocs, doc, updateDoc, deleteDoc, addDoc, query, where, orderBy, Timestamp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
import { ref, uploadBytes, getDownloadURL } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-storage.js";
import { showToast, formatDateID } from './utils.js';

// 1. Proteksi Halaman
requireAuth();
auth.onAuthStateChanged((user) => {
  if (user) {
    document.body.style.display = 'block'; // Tampilkan UI jika sudah login
    initDashboard();
  }
});

// 2. Logika Navigasi Modul (Sidebar) & Tab
function switchModule(moduleName) {
  document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
  document.querySelectorAll('.module-section').forEach(m => m.classList.remove('active'));
  
  const targetLink = document.querySelector(`.sidebar-link[data-module="${moduleName}"]`);
  if (targetLink) targetLink.classList.add('active');
  
  const targetModule = document.getElementById('module-' + moduleName);
  if (targetModule) targetModule.classList.add('active');
  
  localStorage.setItem('activeAdminModule', moduleName);
}

document.querySelectorAll('.sidebar-link').forEach(link => {
  link.addEventListener('click', (e) => {
    e.preventDefault();
    switchModule(e.target.dataset.module || e.target.closest('a').dataset.module);
  });
});

const savedModule = localStorage.getItem('activeAdminModule') || 'kendaraan';
switchModule(savedModule);

function switchTab(tabName) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  
  const targetBtn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
  if (targetBtn) targetBtn.classList.add('active');
  
  const targetTab = document.getElementById('tab-' + tabName);
  if (targetTab) targetTab.classList.add('active');
  
  localStorage.setItem('activeAdminTab', tabName);
}

document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', (e) => switchTab(e.target.dataset.tab));
});

const savedTab = localStorage.getItem('activeAdminTab') || 'peminjaman';
switchTab(savedTab);

// 3. Logika Modal
function openModal(id) { document.getElementById(id).style.display = 'block'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
document.querySelectorAll('.close').forEach(btn => {
  btn.addEventListener('click', (e) => closeModal(e.target.dataset.dismiss));
});
window.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal')) e.target.style.display = 'none';
});

// Helper Confirm
function confirmAction(message, callback) {
  Swal.fire({
    title: 'Konfirmasi', text: message, icon: 'warning',
    showCancelButton: true, confirmButtonColor: 'var(--kemenkeu-main)', cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Lanjutkan!', cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) callback();
  });
}

// ==========================================
// INISIALISASI DATA DASHBOARD
// ==========================================
function initDashboard() {
  loadPendingLoans();
  loadVehicles();
  loadApprovedLoans();
  loadEmployees();
  loadLogs();
}

// --- A. TAB PEMINJAMAN (Menunggu Persetujuan) ---
async function loadPendingLoans() {
  const tbody = document.getElementById('tbody-peminjaman');
  try {
    const q = query(collection(db, "loans"), where("status", "==", "pending"));
    const snap = await getDocs(q);
    
    if (snap.empty) {
      tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; color:#888;">Belum ada pengajuan peminjaman di Database.</td></tr>';
      return;
    }

    let html = '';
    snap.forEach(docSnap => {
      const d = docSnap.data();
      const id = docSnap.id;
      const date = d.created_at ? formatDateID(d.created_at) : '-';
      html += `
        <tr>
          <td data-label="Waktu Input"><small>${date}</small></td>
          <td data-label="NIP">${d.nip}</td>
          <td data-label="Pegawai"><strong>${d.nama_peminjam}</strong></td>
          <td data-label="Kendaraan">${d.vehicle_name}</td>
          <td data-label="Masa Pinjam">${d.masa_pinjam}</td>
          <td data-label="Keperluan">${d.keperluan}</td>
          <td data-label="Status"><span class="badge badge-pending">Menunggu</span></td>
          <td data-label="Aksi">
            <div class="action-group">
              <button class="btn-act btn-approve" onclick="window.approveLoan('${id}')">Setujui</button>
              <button class="btn-act btn-reject" onclick="window.rejectLoan('${id}')">Tolak</button>
            </div>
          </td>
        </tr>
      `;
    });
    tbody.innerHTML = html;
  } catch (e) {
    console.error(e);
    tbody.innerHTML = '<tr><td colspan="8" style="color:red; text-align:center;">Gagal memuat data.</td></tr>';
  }
}

// Aksi Setujui/Tolak (Harus ditaruh di window agar bisa dipanggil onclick html string)
window.approveLoan = (id) => confirmAction('Setujui peminjaman ini?', async () => {
  try { await updateDoc(doc(db, "loans", id), { status: "approved", updated_at: Timestamp.now() }); showToast('success', 'Disetujui!'); initDashboard(); } catch(e) { showToast('error', 'Gagal menyetujui'); }
});
window.rejectLoan = (id) => confirmAction('Tolak peminjaman ini?', async () => {
  try { await updateDoc(doc(db, "loans", id), { status: "rejected", updated_at: Timestamp.now() }); showToast('success', 'Ditolak!'); initDashboard(); } catch(e) { showToast('error', 'Gagal menolak'); }
});


// --- B. TAB PENGEMBALIAN (Sedang Dipinjam) ---
async function loadApprovedLoans() {
  const tbody = document.getElementById('tbody-pengembalian');
  try {
    const q = query(collection(db, "loans"), where("status", "==", "approved"));
    const snap = await getDocs(q);
    if (snap.empty) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:#888;">Tidak ada kendaraan yang sedang dipinjam saat ini.</td></tr>';
      return;
    }
    let html = '';
    snap.forEach(docSnap => {
      const d = docSnap.data();
      html += `
        <tr>
          <td data-label="NIP">${d.nip}</td>
          <td data-label="Pegawai"><strong>${d.nama_peminjam}</strong></td>
          <td data-label="Kendaraan">${d.vehicle_name}</td>
          <td data-label="Masa Pinjam">${d.masa_pinjam}</td>
          <td data-label="Status"><span class="badge badge-approved">Sedang Dipinjam</span></td>
          <td data-label="Aksi">
            <button class="btn-act btn-info" style="background-color: var(--info); color:white;" onclick="window.returnLoan('${docSnap.id}')">Selesaikan / Dikembalikan</button>
          </td>
        </tr>
      `;
    });
    tbody.innerHTML = html;
  } catch (e) { tbody.innerHTML = '<tr><td colspan="6" style="color:red; text-align:center;">Gagal memuat data.</td></tr>'; }
}

window.returnLoan = (id) => confirmAction('Konfirmasi kendaraan telah dikembalikan dengan aman?', async () => {
  try { await updateDoc(doc(db, "loans", id), { status: "returned", updated_at: Timestamp.now() }); showToast('success', 'Kendaraan dikembalikan!'); initDashboard(); } catch(e) { showToast('error', 'Gagal memproses'); }
});

// --- C. TAB KENDARAAN ---
async function loadVehicles() {
  const tbody = document.getElementById('tbody-kendaraan');
  try {
    const snap = await getDocs(collection(db, "vehicles"));
    if (snap.empty) { tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; color:#888;">Tidak ada kendaraan di Database.</td></tr>'; return; }
    
    let html = '';
    let i = 1;
    for (const docSnap of snap.docs) {
      const v = docSnap.data();
      const id = docSnap.id;
      
      // Cek apakah sedang dipinjam
      const q = query(collection(db, "loans"), where("vehicle_id", "==", id), where("status", "==", "approved"));
      const activeLoans = await getDocs(q);
      const isAvailable = activeLoans.empty;
      
      const statusBadge = isAvailable ? '<span class="badge badge-approved">Tersedia</span>' : '<span class="badge badge-rejected">Dipinjam</span>';
      const imgSrc = v.foto_url ? v.foto_url : (v.foto ? `images/${v.foto}` : '');
      const imgHtml = imgSrc ? `<img src="${imgSrc}" style="height: 40px; width: 60px; object-fit: cover; border-radius: 4px; vertical-align: middle; margin-right: 10px;" onerror="this.onerror=null; this.src='images/kemenkeu.jpg';">` : `<div style="display:inline-block; height:40px; width:60px; background:#e2e8f0; border-radius:4px; vertical-align:middle; margin-right:10px; text-align:center; line-height:40px; font-size:10px; color:#64748b;">No Image</div>`;
      
      html += `
        <tr>
          <td data-label="No">${i++}</td>
          <td data-label="Nama & Plat">${imgHtml} <strong>${v.nama_kendaraan} (${v.plat_nomor})</strong></td>
          <td data-label="Status Kendaraan">${statusBadge}</td>
          <td data-label="Aksi">
            <div class="action-group">
              <button class="btn-act btn-edit" onclick="window.editVehicle('${id}', '${v.nama_kendaraan}', '${v.plat_nomor}')">Edit</button>
              <button class="btn-act btn-delete" onclick="window.deleteVehicle('${id}')">Hapus</button>
            </div>
          </td>
        </tr>
      `;
    }
    tbody.innerHTML = html;
  } catch (e) { tbody.innerHTML = '<tr><td colspan="4" style="color:red; text-align:center;">Gagal memuat data.</td></tr>'; }
}

// Add Vehicle Modal & Logic
document.getElementById('btnOpenAddVehicle').addEventListener('click', () => { document.getElementById('formAddVehicle').reset(); openModal('modalAddVehicle'); });
document.getElementById('formAddVehicle').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitAddVehicle'); btn.disabled = true; btn.innerText = 'Menyimpan...';
  try {
    const file = document.getElementById('add_v_foto').files[0];
    let foto_url = '';
    if (file) {
      const fileRef = ref(storage, 'vehicles/' + Date.now() + '_' + file.name);
      await uploadBytes(fileRef, file);
      foto_url = await getDownloadURL(fileRef);
    }
    await addDoc(collection(db, "vehicles"), {
      nama_kendaraan: document.getElementById('add_v_nama').value,
      plat_nomor: document.getElementById('add_v_plat').value,
      foto_url: foto_url, created_at: Timestamp.now()
    });
    showToast('success', 'Kendaraan ditambahkan!');
    closeModal('modalAddVehicle'); initDashboard();
  } catch (err) { showToast('error', 'Gagal menambahkan kendaraan'); }
  btn.disabled = false; btn.innerText = 'Simpan Kendaraan';
});

window.editVehicle = (id, nama, plat) => {
  document.getElementById('edit_v_id').value = id;
  document.getElementById('edit_v_nama').value = nama;
  document.getElementById('edit_v_plat').value = plat;
  openModal('modalEditVehicle');
}
document.getElementById('formEditVehicle').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitEditVehicle'); btn.disabled = true; btn.innerText = 'Mengupdate...';
  try {
    const id = document.getElementById('edit_v_id').value;
    const file = document.getElementById('edit_v_foto').files[0];
    let updateData = {
      nama_kendaraan: document.getElementById('edit_v_nama').value,
      plat_nomor: document.getElementById('edit_v_plat').value
    };
    if (file) {
      const fileRef = ref(storage, 'vehicles/' + Date.now() + '_' + file.name);
      await uploadBytes(fileRef, file);
      updateData.foto_url = await getDownloadURL(fileRef);
    }
    await updateDoc(doc(db, "vehicles", id), updateData);
    showToast('success', 'Kendaraan diperbarui!'); closeModal('modalEditVehicle'); initDashboard();
  } catch (err) { showToast('error', 'Gagal memperbarui kendaraan'); }
  btn.disabled = false; btn.innerText = 'Update Kendaraan';
});

window.deleteVehicle = (id) => confirmAction('Yakin ingin menghapus kendaraan ini?', async () => {
  try { await deleteDoc(doc(db, "vehicles", id)); showToast('success', 'Dihapus!'); initDashboard(); } catch(e) { showToast('error', 'Gagal menghapus'); }
});


// --- D. TAB USER ---
async function loadEmployees() {
  const tbody = document.getElementById('tbody-user');
  try {
    const snap = await getDocs(collection(db, "employees"));
    if (snap.empty) { tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#888;">Tidak ada data pegawai di Database.</td></tr>'; return; }
    
    let html = '';
    let i = 1;
    snap.forEach(docSnap => {
      const e = docSnap.data();
      const id = docSnap.id;
      const date = e.created_at ? formatDateID(e.created_at) : '-';
      html += `
        <tr>
          <td data-label="No">${i++}</td>
          <td data-label="NIP"><strong>${e.nip}</strong></td>
          <td data-label="Nama Pegawai">${e.nama_pegawai}</td>
          <td data-label="Terdaftar Sejak">${date}</td>
          <td data-label="Aksi">
            <div class="action-group">
              <button class="btn-act btn-edit" onclick="window.editEmployee('${id}', '${e.nip}', '${e.nama_pegawai}')">Edit</button>
              <button class="btn-act btn-delete" onclick="window.deleteEmployee('${id}')">Hapus</button>
            </div>
          </td>
        </tr>
      `;
    });
    tbody.innerHTML = html;
  } catch (e) { tbody.innerHTML = '<tr><td colspan="5" style="color:red; text-align:center;">Gagal memuat data.</td></tr>'; }
}

document.getElementById('btnOpenAddEmployee').addEventListener('click', () => { document.getElementById('formAddEmployee').reset(); openModal('modalAddEmployee'); });
document.getElementById('formAddEmployee').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitAddEmployee'); btn.disabled = true; btn.innerText = 'Menyimpan...';
  try {
    await addDoc(collection(db, "employees"), { nip: document.getElementById('add_e_nip').value, nama_pegawai: document.getElementById('add_e_nama').value, created_at: Timestamp.now() });
    showToast('success', 'Pegawai ditambahkan!'); closeModal('modalAddEmployee'); initDashboard();
  } catch (err) { showToast('error', 'Gagal menambahkan pegawai'); }
  btn.disabled = false; btn.innerText = 'Simpan Data';
});

window.editEmployee = (id, nip, nama) => {
  document.getElementById('edit_e_id').value = id;
  document.getElementById('edit_e_nip').value = nip;
  document.getElementById('edit_e_nama').value = nama;
  openModal('modalEditEmployee');
}
document.getElementById('formEditEmployee').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitEditEmployee'); btn.disabled = true; btn.innerText = 'Mengupdate...';
  try {
    const id = document.getElementById('edit_e_id').value;
    await updateDoc(doc(db, "employees", id), { nip: document.getElementById('edit_e_nip').value, nama_pegawai: document.getElementById('edit_e_nama').value });
    showToast('success', 'Pegawai diperbarui!'); closeModal('modalEditEmployee'); initDashboard();
  } catch (err) { showToast('error', 'Gagal memperbarui pegawai'); }
  btn.disabled = false; btn.innerText = 'Update Data';
});
window.deleteEmployee = (id) => confirmAction('Yakin ingin menghapus pegawai ini?', async () => {
  try { await deleteDoc(doc(db, "employees", id)); showToast('success', 'Dihapus!'); initDashboard(); } catch(e) { showToast('error', 'Gagal menghapus'); }
});


// --- E. TAB LOG AKTIVITAS ---
async function loadLogs() {
  const tbody = document.getElementById('tbody-log');
  try {
    const snap = await getDocs(collection(db, "loans"));
    let logs = [];
    snap.forEach(docSnap => {
      const d = docSnap.data();
      if (d.status === 'returned' || d.status === 'rejected') logs.push(d);
    });
    
    // Sort by updated_at desc manually
    logs.sort((a, b) => {
      if(!a.updated_at || !b.updated_at) return 0;
      return b.updated_at.toMillis() - a.updated_at.toMillis();
    });

    if (logs.length === 0) { tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; color:#888;">Belum ada riwayat aktivitas.</td></tr>'; return; }
    
    let html = '';
    logs.forEach(d => {
      const date = d.updated_at ? formatDateID(d.updated_at) : '-';
      const statusBadge = d.status === 'returned' 
        ? '<span class="badge" style="background: #D1FAE5; color: #059669;">Selesai/Dikembalikan</span>'
        : '<span class="badge" style="background: #FEE2E2; color: #DC2626;">Ditolak</span>';
        
      html += `
        <tr>
          <td data-label="Waktu Selesai"><small>${date}</small></td>
          <td data-label="Pegawai"><strong>${d.nama_peminjam}</strong></td>
          <td data-label="Kendaraan">${d.vehicle_name}</td>
          <td data-label="Keperluan">${d.keperluan}</td>
          <td data-label="Status Akhir">${statusBadge}</td>
        </tr>
      `;
    });
    tbody.innerHTML = html;
  } catch (e) { tbody.innerHTML = '<tr><td colspan="5" style="color:red; text-align:center;">Gagal memuat data log.</td></tr>'; }
}

// --- F. IMPOR & EKSPOR CSV ---
document.getElementById('btnExportCsv').addEventListener('click', async () => {
  try {
    const snap = await getDocs(collection(db, 'employees'));
    let csv = 'NIP,Nama Pegawai\n';
    snap.forEach(doc => {
      const d = doc.data();
      csv += '"' + d.nip + '","' + d.nama_pegawai + '"\n';
    });
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', 'Data_Pegawai_KPKNL.csv');
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  } catch (e) { showToast('error', 'Gagal mengekspor data'); }
});

document.getElementById('btnImportCsv').addEventListener('click', () => {
  document.getElementById('formImportCsv').reset();
  openModal('modalImportCsv');
});

document.getElementById('formImportCsv').addEventListener('submit', (e) => {
  e.preventDefault();
  const btn = document.getElementById('btnSubmitImportCsv');
  btn.disabled = true; btn.innerText = 'Memproses...';
  const file = document.getElementById('import_csv_file').files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = async function(event) {
    const text = event.target.result;
    const rows = text.split('\n');
    let successCount = 0;
    for (let i = 1; i < rows.length; i++) {
      const row = rows[i].trim();
      if (!row) continue;
      // Parse CSV line (handle quotes)
      let cols = [];
      let inQuotes = false;
      let val = '';
      for(let j=0; j<row.length; j++) {
        const c = row[j];
        if(c === '\u0022') { inQuotes = !inQuotes; }
        else if(c === ',' && !inQuotes) { cols.push(val.trim()); val=''; }
        else { val += c; }
      }
      cols.push(val.trim());
      if (cols.length >= 2) {
        const nip = cols[0]; const nama = cols[1];
        // Check exist
        const q = query(collection(db, 'employees'), where('nip', '==', nip));
        const snap = await getDocs(q);
        if (!snap.empty) {
           await updateDoc(doc(db, 'employees', snap.docs[0].id), { nama_pegawai: nama });
        } else {
           await addDoc(collection(db, 'employees'), { nip: nip, nama_pegawai: nama, created_at: Timestamp.now() });
        }
        successCount++;
      }
    }
    showToast('success', successCount + ' data berhasil diimpor!');
    closeModal('modalImportCsv');
    initDashboard();
    btn.disabled = false; btn.innerText = 'Upload & Proses Data';
  };
  reader.readAsText(file);
});
