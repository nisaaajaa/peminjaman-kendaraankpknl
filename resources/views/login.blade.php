<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - KPKNL Metro</title>
  <style>
    :root {
      --kemenkeu-main: #062145;
      --kemenkeu-gold: #F2C94C;
      --bg-gray: #F4F6F9;
      --card-white: #FFFFFF;
      --text-dark: #1E293B;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background-color: var(--bg-gray); display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 1rem; }

    .login-container {
      width: 100%;
      max-width: 400px;
      background: var(--card-white);
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      overflow: hidden;
      border: 1px solid #E2E8F0;
    }

    .login-header {
      background-color: var(--kemenkeu-main);
      color: white;
      padding: 1.5rem;
      text-align: center;
      border-bottom: 4px solid var(--kemenkeu-gold);
    }

    .login-header h2 { font-size: 1.3rem; margin-bottom: 0.2rem; }
    .login-header p { color: var(--kemenkeu-gold); font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; }

    .login-body { padding: 2rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; font-weight: 600; font-size: 0.85rem; color: var(--kemenkeu-main); margin-bottom: 0.4rem; }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #CBD5E1;
      border-radius: 6px;
      font-size: 0.95rem;
      background-color: #F8FAFC;
    }

    .btn-login {
      display: block;
      width: 100%;
      padding: 0.8rem;
      background-color: var(--kemenkeu-main);
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      margin-top: 1.5rem;
      text-align: center;
      text-decoration: none;
    }

    .btn-login:hover { background-color: #03142C; }
    .btn-back { display: block; text-align: center; margin-top: 1rem; color: #64748B; text-decoration: none; font-size: 0.85rem; }
  </style>
</head>
<body>

  <div class="login-container">
    <div class="login-header">
      <h2>LOGIN ADMIN</h2>
      <p>KPKNL METRO - KEMENTERIAN KEUANGAN RI</p>
    </div>

    <div class="login-body">
      <div class="form-group">
        <label for="email">Email Admin</label>
        <input type="email" class="form-control" value="admin.kpknlmetro@kemenkeu.go.id" readonly>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="text" class="form-control" value="KpknlMetro2026!" readonly>
      </div>

      <!-- LINK LANGSUNG KE DASHBOARD -->
      <a href="/admin/dashboard" class="btn-login">Masuk ke Dashboard</a>
      
      <a href="/" class="btn-back">← Kembali ke Halaman Utama</a>
    </div>
  </div>

</body>
</html>