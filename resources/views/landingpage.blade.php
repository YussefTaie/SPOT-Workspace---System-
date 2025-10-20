<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SPOT | Your Creative Workspace</title>
  <style>
    :root {
      --bg:#f9fafb;
      --card:#ffffff;
      --muted:#6b7280;
      --accent:#7c3aed;
      --glass: rgba(0,0,0,0.04);
      --accent-light:#a78bfa;
      --radius:14px;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      background: var(--bg);
      color: #1f2937;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      width: 100%;
      background: var(--glass);
      backdrop-filter: blur(10px);
      position: sticky;
      top: 0;
      z-index: 50;
      padding: 14px 6%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .logo {
      font-size: 22px;
      font-weight: 800;
      color: var(--accent);
      letter-spacing: 1px;
    }

    .actions {
      display: flex;
      gap: 10px;
    }

    .btn {
      padding: 8px 16px;
      border-radius: var(--radius);
      border: none;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.25s ease;
    }

    .btn-primary {
      background: linear-gradient(90deg,var(--accent),var(--accent-light));
      color: white;
    }

    .btn-primary:hover { transform: translateY(-2px); }

    .btn-ghost {
      background: transparent;
      border: 1px solid rgba(0,0,0,0.1);
      color: #374151;
    }

    .btn-ghost:hover {
      background: rgba(0,0,0,0.05);
    }

    /* HERO */
    .hero {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 80px 24px;
      animation: fadeIn 1s ease-out forwards;
    }

    .hero h1 {
      font-size: 42px;
      font-weight: 800;
      color: #111827;
      max-width: 700px;
      line-height: 1.2;
      margin-bottom: 16px;
      animation: slideUp 1s ease-out 0.2s both;
    }

    .hero p {
      font-size: 17px;
      color: var(--muted);
      max-width: 520px;
      margin-bottom: 30px;
      animation: slideUp 1s ease-out 0.4s both;
    }

    .hero-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
      animation: slideUp 1s ease-out 0.6s both;
    }

    /* IMAGE / MOCKUP SECTION */
    .showcase {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 18px;
      padding: 60px 6%;
      animation: fadeIn 1.2s ease-out 0.8s both;
    }

    .card {
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      padding: 24px;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: transform 0.3s ease;
    }

    .card:hover { transform: translateY(-6px); }

    .card img {
      width: 100%;
      height: 80%;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 14px;
    }

    .card h3 {
      font-size: 18px;
      margin-bottom: 8px;
      color: #1f2937;
    }

    .card p {
      color: var(--muted);
      font-size: 14px;
      text-align: center;
    }

    footer {
      text-align: center;
      padding: 40px 0;
      color: var(--muted);
      font-size: 14px;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media(max-width: 600px) {
      .hero h1 { font-size: 30px; }
      .hero p { font-size: 15px; }

    }
  </style>
</head>
<body>

  <header>
    <div class="logo">SPOT</div>
    <div class="actions">
      <button class="btn btn-ghost" onclick="window.location='{{ route('guests.login') }}'">Log In</button>
      <button class="btn btn-primary" onclick="window.location='{{ route('guests.create') }}'">Register</button>
    </div>
  </header>

  <section class="hero">
    <h1>Find Your Focus. Work, Create, and Collaborate at <span style="color:var(--accent)">SPOT</span>.</h1>
    <p>SPOT is your modern workspace — designed for creators, developers, and dreamers.  
       Work comfortably, grab a drink, and stay productive in a community that inspires.</p>
    <div class="hero-actions">
      <button class="btn btn-primary" onclick="window.location='{{ route('guests.create') }}'">Get Started</button>

    </div>
  </section>

  <section class="showcase">
    <div class="card">
      <img src="{{ asset('images/space.jpg') }}" alt="Workspace">
      <h3>Modern & Cozy Spaces</h3>
      <p>Designed for productivity, with high-speed internet, comfort, and inspiring atmosphere.</p>
    </div>
    <div class="card">
      <img src="{{ asset('images/cafe.jpg') }}" alt="Coffee">
      <h3>Café Vibes</h3>
      <p>Enjoy top-quality drinks and snacks while you focus on what matters most.</p>
    </div>
    <div class="card">
      <img src="{{ asset('images/metting.jpg') }}" alt="Community">
      <h3>Community & Events</h3>
      <p>Connect with like-minded creators, attend workshops, and build your network.</p>
    </div>
  </section>

  <footer>
    © 2025 SPOT Workspace. All rights reserved.
  </footer>

</body>
</html>
