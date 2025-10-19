<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>QR Scanner Station</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #121212;
      color: #fff;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .box {
      background: #1e1e1e;
      padding: 32px;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      text-align: center;
      width: 400px;
    }
    input {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      margin-top: 16px;
      font-size: 18px;
      text-align: center;
      outline: none;
    }
    .success {
      margin-top: 16px;
      color: #00ff88;
      font-weight: bold;
      display: none;
    }
  </style>
</head>
<body>

  <div class="box">
    <h2>🔍 Scan Guest QR Code</h2>
    <p>Please scan the guest QR using the USB scanner.</p>
    <input type="text" id="scannerInput" placeholder="Waiting for scan..." autofocus>
    <div class="success" id="successMsg">✅ Guest Checked In Successfully!</div>
  </div>

  <script>
    const input = document.getElementById('scannerInput');
    const msg = document.getElementById('successMsg');

    input.addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const url = input.value.trim();

        if (url.includes('/scan?guest_id=')) {
          // نعمل redirect تلقائي للرابط اللي السكان جابه
          window.location.href = url;
        } else {
          msg.style.display = 'block';
          msg.textContent = "⚠️ Invalid QR Code";
          msg.style.color = "#ff5555";
          setTimeout(() => msg.style.display = "none", 3000);
        }

        input.value = ''; // نفرغ الحقل بعد كل سكان
      }
    });
  </script>

</body>
</html>
