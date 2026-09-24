// --- js/utils.js ---

// Inisialisasi SweetAlert2 Toast
export const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  }
});

// Helper: Tampilkan Toast Notifikasi
export function showToast(type, message) {
  Toast.fire({
    icon: type, // 'success', 'error', 'warning', 'info'
    title: message
  });
}

// Helper: Format Tanggal ke Bahasa Indonesia (Contoh: 15 Agustus 2026)
export function formatDateID(timestamp) {
  if (!timestamp) return '-';
  
  // Jika timestamp dari Firestore (memiliki property seconds)
  let date;
  if (timestamp.toDate) {
    date = timestamp.toDate();
  } else if (timestamp instanceof Date) {
    date = timestamp;
  } else {
    date = new Date(timestamp);
  }

  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return date.toLocaleDateString('id-ID', options);
}

// Helper: Ekstrak ekstensi file untuk foto
export function getFileExtension(filename) {
  return filename.split('.').pop();
}
