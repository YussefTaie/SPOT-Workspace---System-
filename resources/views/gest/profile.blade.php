<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Spot | Profile</title>
  <style>
    :root{
      --bg:#f9fafb; /* light background */
      --card:#ffffff; /* white card background */
      --muted:#6b7280; /* gray-500 */
      --accent:#7c3aed; /* violet */
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
      background: #e5e7eb;
      color:#111827; /* dark text */
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:18px;
    }

    /* container */
    .wrap{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr;gap:16px}

    /* header */
    .header{display:flex;align-items:center;gap:12px;background:var(--glass);padding:12px;border-radius:12px; color: #1f2937;}
    .avatar{width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,var(--accent),#a78bfa);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;color:white;}
    .user-info h2{margin:0;font-size:18px}
    .user-info p{margin:0;color:var(--muted);font-size:13px}

    /* main grid */
    .main-grid{display:grid;grid-template-columns:1fr;gap:12px}

    /* cards */
    .card{background:var(--card);padding:14px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.1); color: #1f2937;}
    .card h3{margin:0 0 10px 0;font-size:15px}
    .muted{color:var(--muted);font-size:13px}

    /* current session */
    .session{display:flex;flex-direction:column;gap:10px}
    .session-row{display:flex;justify-content:space-between;align-items:center}
    .badge{padding:6px 10px;border-radius:999px;background:var(--glass-2);font-size:13px; color: #374151;}
    .time-large{font-weight:700;font-size:20px}

    /* orders */
    .orders-list{display:flex;flex-direction:column;gap:8px}
    .order-item{display:flex;justify-content:space-between;align-items:center;padding:8px;background:rgba(0,0,0,0.03);border-radius:10px; color: #1f2937;}
    .order-left{display:flex;gap:10px;align-items:center}
    .drink-thumb{width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,#f97316,#fb7185);display:flex;align-items:center;justify-content:center;font-weight:700;color:white;}
    .status{font-size:13px;padding:6px 8px;border-radius:999px}
    .status.pending{background:rgba(255,193,7,0.12);color:#b45309}
    .status.done{background:rgba(16,185,129,0.12);color:var(--success)}

    /* history grid */
    .history-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
    .stat{padding:12px;border-radius:12px;background:linear-gradient(180deg, rgba(0,0,0,0.02), rgba(0,0,0,0.01));text-align:center; color: #1f2937;}
    .stat .num{font-size:20px;font-weight:700}
    .stat .label{font-size:13px;color:var(--muted)}

    /* admin quick summary */
    .admin-panel{display:flex;flex-direction:column;gap:10px}
    .mini-row{display:flex; flex-direction:column; gap:10px}
    .mini-card{flex:1;padding:10px;border-radius:10px;background:var(--glass); color: #1f2937;}

    /* actions */
    .btn{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent),#a78bfa);color:white;text-decoration:none;cursor:pointer}
    .btn.ghost{background:transparent;border:1px solid rgba(0,0,0,0.1); color: #374151;}

    /* responsive layout */
    @media(min-width:860px){
      .wrap{grid-template-columns:360px 1fr;align-items:start}
      .main-grid{grid-template-columns:1fr;gap:12px}
      .left-col{position:sticky;top:18px}
      .history-grid{grid-template-columns:repeat(3,1fr)}
      .card.inline{display:block}
    }

    /* small screens adjustments */
    @media(max-width:420px){
      .avatar{width:56px;height:56px}
      .drink-thumb{width:40px;height:40px}
    }
  </style>
</head>
<body>
<div class="wrap">

    <!-- LEFT COLUMN: profile + session summary -->
    <div class="left-col">
      <div class="header card">
        <div class="avatar">
          <div class="avatar">
            {{ strtoupper(substr(explode(' ', $userData['fullname'])[0], 0, 1) . substr(explode(' ', $userData['fullname'])[1] ?? '', 0, 1)) }}
          </div>

        </div>
        <div class="user-info">
          <h2>{{ $userData['fullname'] }}</h2>
          <p class="muted">{{ $userData['phone'] }} · Registered since {{ $userData['registered_at'] }}</p>
          
        </div>
      </div>

      <div class="card session" style="margin-top:12px">
        <h3>Current Session</h3>
        <div class="session-row">
          <div>
            <div class="muted">Check-in Time</div>
            <div class="time-large">{{ $userData['check_in'] }}</div>
          </div>
          <div style="text-align:right">
            <div class="muted">Check-out Time</div>
            <div class="time-large">{{ $userData['check_out'] }}</div>
          </div>
        </div>
        <div class="session-row">
          <div>
            <div class="muted">Current Duration</div>
            <div class="time-large">{{ $userData['duration'] }}</div>
          </div>
          <div style="text-align:right">
            <div class="muted">Hourly Rate</div>
            <div class="time-large">{{ $userData['rate'] }}</div>
          </div>
        </div>

      </div>

        <div class="card" style="margin-top:12px">
        <h3>Current Orders</h3>
        <div class="orders-list">
            @foreach($userData['orders'] as $order)
            <div class="order-item">
            <div class="order-left">
                <div class="drink-thumb">{{ strtoupper(substr($order['name'], 0, 2)) }}</div>
                <div>
                <div style="font-weight:700">{{ $order['name'] }}</div>
                <div class="muted" style="font-size:13px">Ordered {{ $order['time'] }}</div>
                </div>
            </div>
            <div style="text-align:right">
                <div class="status {{ strtolower($order['status']) }}"> {{ $order['status'] }} </div>
                <div class="muted" style="margin-top:6px">By: {{ $order['by'] }}</div>
            </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:12px; text-align: start;">
            <button class="btn" onclick="window.location.href='{{ route('menu.guest', ['guest' => $userData['id'] ?? $guest->id]) }}'">Order Now</button>
        </div>
        </div>


      <div class="card" style="margin-top:12px">
        <h3>Guest Information</h3>
        <p class="muted">Email: {{ $userData['email'] }}</p>
        <p class="muted">Phone: {{ $userData['phone'] }}</p>
        <p class="muted">Collage: {{ $userData['college'] }}</p>
        <p class="muted">University: {{ $userData['university'] }}</p>
        <p>Here’s your unique QR code 👇</p>
        {!! QrCode::size(250)->generate(url('/scan?guest_id=' . $guest->id)) !!}

      </div>

    </div>

    <!-- RIGHT COLUMN: history / stats / admin quick -->
    <div class="main-grid">

      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Quick Overview</h3>
          <div class="muted">Last activity: {{ $userData['last_activity'] }}</div>
        </div>
        <p class="muted" style="margin-top:8px">Current bill: <strong>{{ $userData['current_bill'] }}</strong> (time + drinks)</p>

        <div class="history-grid" style="margin-top:12px">
          <div class="stat">
            <div class="num">{{ $userData['visits'] }}</div>
            <div class="label">Number of Visits</div>
          </div>
          <div class="stat">
            <!-- <div class="num">{{ $userData['total_duration'] }}</div> -->
            <div class="num">{{ $userData['duration'] }}</div>
            <div class="label">Sitting Time</div>
          </div>
          <div class="stat">
            <!-- <div class="num">{{ $userData['total_expenses'] }}</div> -->
            <div class="num">{{ $userData['current_bill'] }}</div>
            <div class="label">Expenses</div>
          </div>
        </div>
      </div>

      <div class="card admin-panel">
        <h3>History & Previous Orders</h3>
        <div class="mini-row">
            @foreach($userData['history'] as $item)
          <div class="mini-card">
            <div style="font-weight:700">{{ $item['date'] }}</div>
            <div class="muted">{{ $item['duration'] }}</div>
            <div style="margin-top:8px;font-weight:700">Bill: {{ $item['bill'] }}</div>
          </div>
            @endforeach
        </div>

        <div style="margin-top:10px">
          <table style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
              <tr style="text-align:left;color:var(--muted)">
                <th style="padding:8px">Date</th>
                <th style="padding:8px">Order</th>
                <th style="padding:8px;text-align:right">Price</th>
              </tr>
            </thead>
            <tbody>
                @foreach($userData['history_orders'] as $order)
              <tr style="border-top:1px solid rgba(0,0,0,0.05)">
                <td style="padding:8px">{{ $order['date'] }}</td>
                <td style="padding:8px">{{ $order['order'] }}</td>
                <td style="padding:8px;text-align:right">{{ $order['price'] }}</td>
              </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>

</body>
</html>









