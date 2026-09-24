import { db } from './firebase-config.js';
import { collection, getDocs, addDoc, doc, getDoc, query, where, Timestamp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
import { showToast } from './utils.js';

// === LOGIKA BERANDA (index.html) ===
const vehicleGrid = document.getElementById('vehicleGrid');
if (vehicleGrid) {
  async function loadVehicles() {
    try {
      const vehiclesSnapshot = await getDocs(collection(db, "vehicles"));
      let html = '';

      if (vehiclesSnapshot.empty) {
        vehicleGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #888; padding: 2rem;"><h3>Belum ada kendaraan yang terdaftar.</h3></div>';
        return;
      }

      // Gunakan for...of untuk mendukung await di dalam loop
      for (const vehicleDoc of vehiclesSnapshot.docs) {
        const v = vehicleDoc.data();
        const vId = vehicleDoc.id;
        
        // Cari status peminjaman aktif (pending atau approved) untuk kendaraan ini
        const loansRef = collection(db, "loans");
        const qPending = query(loansRef, where("vehicle_id", "==", vId), where("status", "==", "pending"));
        const qApproved = query(loansRef, where("vehicle_id", "==", vId), where("status", "==", "approved"));
        
        const [snapPending, snapApproved] = await Promise.all([getDocs(qPending), getDocs(qApproved)]);
        
        let activeLoan = null;
        if (!snapApproved.empty) {
          activeLoan = snapApproved.docs[0].data();
        } else if (!snapPending.empty) {
          activeLoan = snapPending.docs[0].data();
        }

        // Tentukan path gambar
        const imgSrc = v.foto_url ? v.foto_url : (v.foto ? `images/${v.foto}` : '');
        let imgTag = '';
        if (imgSrc) {
          imgTag = `<img src="${imgSrc}" class="vehicle-img" alt="Foto Kendaraan" onerror="this.onerror=null; this.src='images/kemenkeu.jpg';">`;
        } else {
          imgTag = `<div class="vehicle-img" style="background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b;">No Image</div>`;
        }

        // Render Card
        let statusHtml = '';
        if (activeLoan && activeLoan.status === 'approved') {
          statusHtml = `
            <span class="badge badge-busy">✕ Dalam Masa Dinas</span>
            <p class="vehicle-desc" style="color: var(--danger); font-weight: 600;">
              Sedang dipinjam oleh <strong>${activeLoan.nama_peminjam}</strong><br>
              <small style="color: #7F8C8D;">(${activeLoan.masa_pinjam || 'Masa Dinas'})</small>
            </p>
            <span class="btn-action btn-disabled">Tidak Dapat Dipinjam</span>
          `;
        } else if (activeLoan && activeLoan.status === 'pending') {
          statusHtml = `
            <span class="badge badge-pending">⏳ Menunggu Persetujuan</span>
            <p class="vehicle-desc" style="color: var(--warning); font-weight: 600;">
              Diajukan oleh <strong>${activeLoan.nama_peminjam}</strong><br>
              <small style="color: #7F8C8D;">(Menunggu konfirmasi admin)</small>
            </p>
            <span class="btn-action btn-disabled">Prosedur Verifikasi</span>
          `;
        } else {
          statusHtml = `
            <span class="badge badge-available">✓ Belum Dipinjam</span>
            <p class="vehicle-desc">Kendaraan siap digunakan untuk perjalanan dinas resmi kantor.</p>
            <a href="form.html?id=${vId}" class="btn-action btn-primary">Pinjam Kendaraan</a>
          `;
        }

        html += `
          <div class="vehicle-card">
            ${imgTag}
            <div class="vehicle-name">${v.nama_kendaraan} - ${v.plat_nomor}</div>
            <div class="status-container">${statusHtml}</div>
          </div>
        `;
      }
      
      vehicleGrid.innerHTML = html;
    } catch (error) {
      console.error("Gagal memuat kendaraan:", error);
      vehicleGrid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: red;">Gagal memuat data dari server.</div>';
    }
  }
  
  loadVehicles();
}


// === LOGIKA FORM PEMINJAMAN (form.html) ===
const loanForm = document.getElementById('loanForm');
if (loanForm) {
  // Parsing parameter ID dari URL
  const urlParams = new URLSearchParams(window.location.search);
  const vehicleId = urlParams.get('id');
  
  if (!vehicleId) {
    document.getElementById('loanForm').style.display = 'none';
    document.getElementById('errorOverlay').style.display = 'block';
  } else {
    // Muat data kendaraan ke form
    document.getElementById('vehicle_id').value = vehicleId;
    getDoc(doc(db, "vehicles", vehicleId)).then(docSnap => {
      if (docSnap.exists()) {
        const v = docSnap.data();
        document.getElementById('nama_kendaraan_display').value = `${v.nama_kendaraan} - ${v.plat_nomor}`;
      } else {
        showToast('error', 'Kendaraan tidak ditemukan!');
      }
    });

    // Muat data pegawai ke dropdown
    getDocs(collection(db, "employees")).then(snapshot => {
      const select = document.getElementById('nama_pegawai');
      select.innerHTML = '<option value="">-- Pilih Nama Pegawai --</option>';
      
      // Simpan data NIP dalam variable global untuk validasi client-side
      window.employeeData = [];
      
      snapshot.forEach(doc => {
        const emp = doc.data();
        window.employeeData.push(emp);
        const option = document.createElement('option');
        option.value = emp.nama_pegawai;
        option.textContent = emp.nama_pegawai;
        select.appendChild(option);
      });
      select.disabled = false;
    });
  }

  // Handle Submit Form
  loanForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Cek Cloudflare Turnstile (Mock)
    const turnstileResponse = document.querySelector('[name="cf-turnstile-response"]')?.value;
    if (!turnstileResponse) {
      showToast('error', 'Silakan selesaikan validasi keamanan (Captcha) terlebih dahulu.');
      return;
    }

    const nipInput = document.getElementById('nip').value;
    const namaInput = document.getElementById('nama_pegawai').value;
    const tglPinjam = document.getElementById('tgl_pinjam').value;
    const tglKembali = document.getElementById('tgl_kembali').value;
    
    // Validasi NIP vs Nama Pegawai (Client Side)
    const matchedEmployee = window.employeeData.find(emp => emp.nama_pegawai === namaInput && emp.nip === nipInput);
    if (!matchedEmployee) {
      showToast('error', 'NIP tidak cocok dengan Nama Pegawai yang dipilih!');
      return;
    }
    
    if (new Date(tglKembali) < new Date(tglPinjam)) {
      showToast('error', 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!');
      return;
    }

    const btn = document.getElementById('btnSubmitForm');
    const oriText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Mengirim...';

    try {
      await addDoc(collection(db, "loans"), {
        vehicle_id: document.getElementById('vehicle_id').value,
        vehicle_name: document.getElementById('nama_kendaraan_display').value,
        nama_peminjam: namaInput,
        nip: nipInput,
        seksi: document.getElementById('seksi').value,
        keperluan: document.getElementById('keperluan').value,
        tgl_pinjam: tglPinjam,
        tgl_kembali: tglKembali,
        masa_pinjam: `${tglPinjam} s/d ${tglKembali}`,
        status: "pending",
        created_at: Timestamp.now()
      });

      showToast('success', 'Permohonan berhasil dikirim!');
      setTimeout(() => { window.location.href = 'index.html'; }, 2000);
    } catch (error) {
      console.error(error);
      showToast('error', 'Gagal mengirim permohonan. Coba lagi.');
      btn.disabled = false;
      btn.innerHTML = oriText;
    }
  });
}
