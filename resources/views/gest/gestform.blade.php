<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registration | Spot Workspace</title>
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
    .wrap{max-width:500px;width:100%;margin:0 auto;}

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

    /* select styles */
    .form-select{
      width:100%;
      padding:10px 12px;
      border:1px solid #d1d5db;
      border-radius:8px;
      font-size:14px;
      background:#fff;
      appearance:none;
      background-image:url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
      background-position:right 12px center;
      background-repeat:no-repeat;
      background-size:16px;
    }
    .form-select:focus{
      outline:none;
      border-color:var(--accent);
      box-shadow:0 0 0 3px rgba(124,58,237,0.1);
    }

    /* checkbox styles */
    .checkbox-group{
      display:flex;
      align-items:flex-start;
      gap:8px;
      margin-bottom:16px;
    }
    .checkbox-input{
      width:16px;
      height:16px;
      margin-top:2px;
      border:1px solid #d1d5db;
      border-radius:4px;
      background:#fff;
    }
    .checkbox-label{
      font-size:13px;
      color:#374151;
      line-height:1.4;
    }
    .checkbox-label a{
      color:var(--accent);
      text-decoration:none;
    }
    .checkbox-label a:hover{
      text-decoration:underline;
    }

    /* actions */
    .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent));color:white;text-decoration:none;cursor:pointer;font-weight:600;font-size:14px;width:100%;justify-content:center;transition:all 0.2s ease;}
    .btn:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(124,58,237,0.3);}
    .btn:active{transform:translateY(0);}

    /* responsive layout */
    @media(min-width:768px){
      .wrap{max-width:450px;}
    }

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
      <div class="avatar">S</div>
      <div class="user-info">
        <h2>Spot</h2>
        <p class="muted">Create your account to get started</p>
      </div>
    </div>

    <div class="card">
      <h3>Rejester</h3>
      <form id="registrationForm" method="POST" action="{{ route('guests.store') }}">
    @csrf

    <div class="form-group">
      <label class="form-label">Full Name</label>
      <input type="text" name="fullname" class="form-input" placeholder="Enter your full name" required>
    </div>

    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input type="email" name="email" class="form-input" placeholder="your@email.com" required>
    </div>

    <div class="form-group">
      <label class="form-label">Phone Number</label>
      <input type="tel" name="phone" class="form-input" placeholder="01012345678" required>
    </div>

    <div class="form-group">
      <label class="form-label">College</label>
      <input type="text" name="college" class="form-input" placeholder="Enter your College">
    </div>

    <div class="form-group">
      <label class="form-label">University</label>
      <input type="text" name="university" class="form-input" placeholder="Enter your University">
    </div>

    <div class="form-group">
      <label class="form-label">Create Password</label>
      <input type="password" name="password" class="form-input" placeholder="Create a strong password" required>
    </div>

    <div class="form-group">
      <label class="form-label">Confirm Password</label>
      <input type="password" name="password_confirmation" class="form-input" placeholder="Re-enter your password" required>
    </div>

    <button type="submit" class="btn">Create Account</button>
</form>



      <p class="muted" style="text-align:center;margin-top:16px;font-size:13px;">
        Already have an account? <a href="{{ route('guests.login') }}" style="color:var(--accent);text-decoration:none;">Sign in</a>
      </p>
    </div>
  </div>

  <!-- <script>
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Basic validation
      const password = document.querySelector('input[type="password"]').value;
      const confirmPassword = document.querySelectorAll('input[type="password"]')[1].value;
      
      if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
      }
      
      // Simulate form submission
      alert('Account created successfully! Welcome to Workspace + Bar');
      
      // Here you would typically send the data to your server
      // const formData = new FormData(this);
      // fetch('/api/register', { method: 'POST', body: formData });
    });

    // Add some basic form validation styling
    const inputs = document.querySelectorAll('.form-input, .form-select');
    inputs.forEach(input => {
      input.addEventListener('blur', function() {
        if (this.value === '' && this.hasAttribute('required')) {
          this.classList.add('error');
        } else {
          this.classList.remove('error');
        }
      });
    });
  </script> -->
</body>
</html>
