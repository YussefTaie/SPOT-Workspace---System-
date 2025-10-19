<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your QR Code</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      background: white;
      padding: 24px;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    img {
      margin-top: 16px;
      border-radius: 12px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Welcome, {{ $guest->fullname }}!</h2>
    <p>Here’s your unique QR code 👇</p>

    {!! QrCode::size(250)->generate(url('/scan?guest_id=' . $guest->id)) !!}

    <p style="margin-top:12px;">Keep this safe — it identifies your account.</p>
  </div>
</body>
</html>
