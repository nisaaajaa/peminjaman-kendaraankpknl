import { auth } from './firebase-config.js';
import { signInWithEmailAndPassword, signOut, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";
import { showToast } from './utils.js';

// Menangani form login
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    let email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const btnSubmit = document.getElementById('btnLoginSubmit');
    const oriText = btnSubmit.innerHTML;

    // Loading State
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner"></span> Memproses...';

    // Trik Custom Mapping untuk admin tanpa email (seperti kesepakatan)
    if (email.toLowerCase() === 'admin') {
      email = 'admin@kpknl.local';
    }

    try {
      await signInWithEmailAndPassword(auth, email, password);
      // Login sukses, redirect ke halaman dashboard admin
      showToast('success', 'Login berhasil! Mengalihkan...');
      setTimeout(() => {
        window.location.href = '/admin.html';
      }, 1000);
    } catch (error) {
      // Handling Error
      let errorMsg = 'Gagal login. Silakan coba lagi.';
      if (error.code === 'auth/invalid-credential' || error.code === 'auth/user-not-found' || error.code === 'auth/wrong-password') {
        errorMsg = 'User atau Password salah!';
      } else if (error.code === 'auth/too-many-requests') {
        errorMsg = 'Terlalu banyak percobaan gagal. Coba lagi nanti.';
      }
      
      showToast('error', errorMsg);
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = oriText;
    }
  });
}

// Menangani klik tombol logout
const btnLogout = document.getElementById('btnLogout');
if (btnLogout) {
  btnLogout.addEventListener('click', async (e) => {
    e.preventDefault();
    try {
      await signOut(auth);
      // Simpan indikator logout di sessionStorage agar Toast muncul di halaman login
      sessionStorage.setItem('logout_success', 'true');
      window.location.href = '/';
    } catch (error) {
      showToast('error', 'Gagal melakukan logout.');
    }
  });
}

// Route Guard - Proteksi Halaman
// Fungsi ini dipanggil di dalam file admin.js agar halaman dashboard hanya bisa diakses user login
export function requireAuth() {
  onAuthStateChanged(auth, (user) => {
    if (!user) {
      // Lempar ke login jika belum ada sesi (menghindari bypass)
      window.location.href = '/login.html';
    }
  });
}
