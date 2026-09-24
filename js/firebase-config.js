// Inisialisasi Firebase
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-firestore.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";
import { getStorage } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-storage.js";

// Konfigurasi Web App Firebase dari pengguna
const firebaseConfig = {
  apiKey: "AIzaSyDX3rltk8Rt0zx29gJA9J9OxobZerSzqmo",
  authDomain: "kpknl-request.firebaseapp.com",
  projectId: "kpknl-request",
  storageBucket: "kpknl-request.firebasestorage.app",
  messagingSenderId: "286851760300",
  appId: "1:286851760300:web:2e04167b07c7ef25b71f09",
  measurementId: "G-82FY6K9S23"
};

// Inisialisasi Firebase Services
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const auth = getAuth(app);
const storage = getStorage(app);

// Export agar bisa digunakan di file JS lain
export { app, db, auth, storage };
