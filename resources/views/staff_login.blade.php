<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Staff Login | Spot Workspace</title>
  <style>
    :root{
      --bg:#f3f4f6;
      --card:#ffffff;
      --muted:#6b7280;
      --accent:#ef4444; /* different accent for admin (red) */
      --accent-2:#fb7185;
      --glass: rgba(0,0,0,0.04);
      --card-radius:14px;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color-scheme: light;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background: linear-gradient(180deg, #fff7f6 0%, #fbeaea 100%);
      color:#111827;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:18px;
      display:flex;
      align-items:center;
      justify-content:center;
      min-height:100vh;
    }

    .wrap{max-width:420px;width:100%;margin:0 auto;}
    .header{display:flex;align-items:center;gap:12px;background:var(--glass);padding:12px;border-radius:12px;color:#1f2937;margin-bottom:20px;}
    .avatar{width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,var(--accent),var(--accent-2));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;color:white;}
    .user-info h2{margin:0;font-size:18px}
    .user-info p{margin:0;color:var(--muted);font-size:13px}

    .card{background:var(--card);padding:20px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.08); color: #1f2937;}
    .card h3{margin:0 0 15px 0;font-size:18px;text-align:center;}
    .muted{color:var(--muted);font-size:13px}

    .form-group{margin-bottom:14px;}
    .form-label{display:block;margin-bottom:6px;font-weight:600;font-size:14px;color:#374151;}
    .form-input{width:100%;padding:10px 12px;border:1px solid #e6a2a2;border-radius:8px;font-size:14px;background:#fff;transition:all .15s ease;}
    .form-input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 4px rgba(239,68,68,0.07);}
    .form-input::placeholder{color:#9ca3af}

    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent),var(--accent-2));color:white;text-decoration:none;cursor:pointer;font-weight:700;font-size:14px;width:100%;justify-content:center;transition:all .12s ease;}
    .btn:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(239,68,68,0.18);}
    .link{display:block;margin-top:12px;font-size:13px;color:var(--accent);text-align:center;text-decoration:none;cursor:pointer}
    .link:hover{text-decoration:underline}

    .error-box{background:#fff1f2;border:1px solid #fecaca;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:12px;font-size:13px;}
    .small-muted{color:var(--muted);font-size:13px;text-align:center;margin-top:12px;}

    @media(max-width:480px){
      body{padding:12px}
      .card{padding:16px}
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="header">
      <div class="avatar">ADMIN</div>
      <div class="user-info">
        <h2>Staff Portal</h2>
        <p class="muted">Admin / Barista sign in</p>
      </div>
    </div>

    <div class="card">
      <h3>Staff Login</h3>

      {{-- عرض الأخطاء العامة --}}
      @if(session('error'))
        <div class="error-box">{{ session('error') }}</div>
      @endif

      @if($errors->any())
        <div class="error-box">
          <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="staffSignIn" method="POST" action="{{ route('staff.login.submit') }}">
        @csrf

        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input name="email" type="email" id="email" class="form-input" placeholder="admin@spot.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input name="password" type="password" id="password" class="form-input" placeholder="Enter your password" required>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;color:#374151;">
            <input type="checkbox" name="remember" style="width:16px;height:16px;"> Remember
          </label>
          <a href="{{ route('guests.login') }}" class="link" style="text-decoration:none;color:var(--muted);font-size:13px;">Switch to Guest Login</a>
        </div>

        <button type="submit" class="btn">Sign In as Admin</button>
      </form>

      <p class="small-muted">If you don't have an account contact the system administrator.</p>
    </div>
  </div>
</body>
</html>
