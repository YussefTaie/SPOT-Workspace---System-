<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | Spot Workspace</title>
  <style>
    :root{
      --bg:#f9fafb; /* light background */
      --card:#ffffff; /* white card background */
      --muted:#6b7280; /* gray-500 */
      --accent:#E0AA3E; /* violet */
      --glass: rgba(0,0,0,0.04);
      --glass-2: rgba(0,0,0,0.02);
      --success:#10b981;
      --danger:#ef4444;
      --card-radius:14px;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      color-scheme: light;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background: linear-gradient(180deg, #f3f4f6 0%, #e5e7eb 100%);
      color:#111827; /* dark text */
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:18px;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
    }

    /* container */
    .wrap{max-width:400px;width:100%;margin:0 auto;}

    /* header */
    .header{display:flex;align-items:center;gap:12px;background:var(--glass);padding:12px;border-radius:12px; color: #1f2937; margin-bottom:20px;}
    .avatar{width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,var(--accent));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;color:white;}
    .user-info h2{margin:0;font-size:18px}
    .user-info p{margin:0;color:var(--muted);font-size:13px}

    /* cards */
    .card{background:var(--card);padding:20px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.1); color: #1f2937;}
    .card h3{margin:0 0 15px 0;font-size:18px; text-align: center;}
    .muted{color:var(--muted);font-size:13px}

    /* form styles */
    .form-group{margin-bottom:16px;}
    .form-label{display:block;margin-bottom:6px;font-weight:600;font-size:14px;color:#374151;}
    .form-input{
      width:100%;
      padding:10px 12px;
      border:1px solid #d1d5db;
      border-radius:8px;
      font-size:14px;
      background:#fff;
      transition:all 0.2s ease;
    }
    .form-input:focus{
      outline:none;
      border-color:var(--accent);
      box-shadow:0 0 0 3px rgba(124,58,237,0.1);
    }
    .form-input::placeholder{
      color:#9ca3af;
    }

    /* actions */
    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent));color:white;text-decoration:none;cursor:pointer;font-weight:600;font-size:14px;width:100%;justify-content:center;transition:all 0.2s ease;}
    .btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(124,58,237,0.3);}
    .btn:active{transform:translateY(0);}

    /* links */
    .link {
      display: block;
      margin-top: 12px;
      font-size: 13px;
      color: var(--accent);
      text-align: center;
      text-decoration: none;
      cursor: pointer;
      transition: color 0.2s ease;
    }
    .link:hover {
      text-decoration: underline;
      color: #5b21b6;
    }

    /* responsive layout */
    @media(max-width:480px){
      body{padding:12px;}
      .card{padding:16px;}
    }

    /* error styles */
    .error-message{
      color:var(--danger);
      font-size:12px;
      margin-top:4px;
      display:none;
    }
    .form-input.error{
      border-color:var(--danger);
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="header">
      <div class="avatar">SPOT</div>
      <div class="user-info">
        <h2>Workspace + Bar</h2>
        <p class="muted">Welcome back! Please sign in to your account</p>
      </div>
    </div>

    <div class="card">
      <h3>LogIn</h3>
      
      <form id="signInForm" method="POST" action="{{ route('guests.login.submit') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input name="email" type="email" id="email" class="form-input" placeholder="your@email.com" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input name="password" type="password" id="password" class="form-input" placeholder="Enter your password" required>
        </div>

        <button type="submit" class="btn">Sign In</button>
      </form>

      <a href="#" class="link">Forgot password?</a>
      <p class="muted" style="text-align:center;margin-top:16px;font-size:13px;">
        Don't have an account? <a href="{{ route('guests.create') }}" style="color:var(--accent);text-decoration:none;">Create one</a>
      </p>
    </div>
  </div>

  <!-- <script>
    document.getElementById('signInForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      if (!email || !password) {
        alert('Please fill in all required fields.');
        return;
      }

      // Simulate sign-in success
      alert(`Welcome back, ${email}!`);

      // Here you would typically send the data to your server
      // fetch('/api/signin', { method: 'POST', body: JSON.stringify({email, password}), headers: {'Content-Type': 'application/json'} });
    });
  </script> -->
</body>
</html>
