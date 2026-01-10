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
    .avatar{width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,var(--accent));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:20px;color:white;}
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
    .mini-card{flex:1;padding:10px;border-radius:10px;background:var(--card); color: #1f2937;}

    /* actions */
    .btn{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent));color:white;text-decoration:none;cursor:pointer}
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
            <div style="margin-left:auto">
              <br>
    <button
      class="btn"
      onclick="startSession({{ $guest->id }})"
    >
      ▶️ Start Session
    </button>
  </div>

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
            <div
              class="time-large"
              id="live-duration"
              @if($guest->sessions->last() && !$guest->sessions->last()->check_out)
                data-check-in="{{ $guest->sessions->last()->check_in }}"
              @endif
            >
              {{ $userData['duration'] }}
            </div>

          </div>

        </div>

      </div>

        <div class="card" style="margin-top:12px">
        <h3>Current Orders</h3>

        <div id="current-orders-container">
            @include('gest.partials.current_orders', ['orders' => $userData['orders']])
        </div>


        <!-- <div class="orders-list">
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
        </div> -->
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

        <div id="quick-overview" style="margin-top:8px">
          @if($userData['live_session'])
            <p class="muted">
              Current bill:
              <strong id="qo-grand-total">
                {{ $userData['live_session']['grand_total'] }} EGP
              </strong>
            </p>
          @else
            <p class="muted" id="qo-no-session">No active session</p>
          @endif
        </div>

        <div class="history-grid" style="margin-top:12px">
          <div class="stat">
            <div class="num">{{ $userData['visits'] }}</div>
            <div class="label">Number of Visits</div>
          </div>
          <div class="stat">
  <div
    class="num"
    id="sitting-time"
    @if($guest->sessions->last() && !$guest->sessions->last()->check_out)
      data-check-in="{{ $guest->sessions->last()->check_in }}"
    @endif
  >
    {{ $userData['duration'] }}
  </div>
  <div class="label">Sitting Time</div>
</div>

          <div class="stat">
            <div class="num">
              {{ $userData['live_session']['grand_total'] ?? '-' }} EGP
            </div>
            <div class="label">Expenses</div>
          </div>
        </div>
      </div>




  @foreach($userData['history'] as $session)
  <div class="mini-card">

    <!-- Session Header -->
    <div style="font-weight:700">
      {{ $session['started_at'] }}
    </div>
    <div class="muted">
      Duration: {{ $session['duration'] }}
    </div>

    <!-- Sub Guests -->
    <div style="margin-top:8px">
      <strong>Guests:</strong>
      @if($session['sub_guests']->count())
        {{ $session['sub_guests']->join(', ') }}
      @else
        <span class="muted">No additional guests</span>
      @endif
    </div>

    <!-- Orders -->
    <div style="margin-top:8px">
      <strong>Orders:</strong>
      @if(count($session['orders']))
        <ul style="margin:6px 0 0 16px; padding:0">
          @foreach($session['orders'] as $order)
            <li>
              {{ $order['name'] }} —
              {{ number_format($order['price'], 2) }} EGP
            </li>
          @endforeach
        </ul>
      @else
        <div class="muted">No orders</div>
      @endif
    </div>
<div style="margin-top:8px">
  <strong>Session Fee:</strong>
  {{ number_format($session['session_fee'], 2) }} EGP
</div>

<!-- Billing -->
<div style="margin-top:8px">
  <strong>Drinks ordered:</strong>
  {{ number_format($session['drinks_total'], 2) }} EGP
</div>

@if($session['discount'] > 0)
  <div style="color:#ef4444;margin-top:4px">
    Discount applied: -{{ number_format($session['discount'], 2) }} EGP
  </div>

  @if(!empty($session['discount_reason']))
    <div class="muted">
      Reason: {{ $session['discount_reason'] }}
    </div>
  @endif
@endif
<hr>
<div style="margin-top:8px;font-weight:700">
  Grand Total: {{ number_format($session['grand_total'], 2) }} EGP
</div>

    <!-- <div style="margin-top:6px;font-weight:700">
      Grand Total: {{ number_format($session['grand_total'], 2) }} EGP
    </div> -->
  </div>
@endforeach



<!-- <script>
(function () {
  const guestId = "{{ $guest->id }}";
  const url = `/profile/${guestId}/snapshot`;

  function refreshOverview() {
    fetch(url)
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('quick-overview');
        if (!container) return;

        if (!data.active) {
          container.innerHTML =
            '<p class="muted" id="qo-no-session">No active session</p>';
          return;
        }

        container.innerHTML = `
          <p class="muted">
            Current bill:
            <strong id="qo-grand-total">
              ${data.session.grand_total} EGP
            </strong>
          </p>
        `;
      })
      .catch(() => {});
  }

  // أول مرة
  refreshOverview();

  // كل 15 ثانية
  setInterval(refreshOverview, 15000);
})();
</script> -->
<script>
(function () {
  const guestId = "{{ $guest->id }}";
  const ordersUrl = `/profile/${guestId}/orders/partial`;

  function refreshOrders() {
    fetch(ordersUrl)
      .then(res => res.text())
      .then(html => {
        document.getElementById('current-orders-container').innerHTML = html;
      });
  }

  // أول تحميل
  refreshOrders();

  // تحديث كل 10 ثواني (خفيف جدًا)
  setInterval(refreshOrders, 10000);
})();
</script>


<!-- window.addEventListener('guest-session-updated', function () {

  // Update quick overview
  refreshOverview();

  // Update orders list
  fetch(`/profile/${guestId}/orders/partial`)
    .then(res => res.text())
    .then(html => {
      document.getElementById('current-orders-container').innerHTML = html;
    });

}); -->

<script>
(function () {
  const durationEl = document.getElementById('live-duration');
  const sittingEl  = document.getElementById('sitting-time');
  if (!durationEl) return;

  const checkInRaw = durationEl.dataset.checkIn;
  if (!checkInRaw) return; // مفيش session شغالة

  const checkIn = new Date(checkInRaw.replace(' ', 'T')); // Laravel format fix

  function updateDuration() {
    const now = new Date();
    const diffMs = now - checkIn;
    if (diffMs < 0) return;

    const totalMinutes = Math.floor(diffMs / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    const text = `${hours}h ${minutes}m`;

if (durationEl) durationEl.textContent = text;
if (sittingEl)  sittingEl.textContent  = text;

  }

  // أول مرة
  updateDuration();

  // كل دقيقة
  setInterval(updateDuration, 60 * 1000);
})();
</script>
<script>
function startSession(guestId) {
  if (!confirm('Send session request to hold list?')) return;

  fetch('/admin/hold-sessions/request', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ guest_id: guestId })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'ok') {
      alert('Hold session created and waiting for admin approval');
    } else {
      alert(data.message || 'Unable to create hold session');
    }
  })
  .catch(err => {
    console.error(err);
    alert('Failed to create hold session');
  });
}
</script>



</body>
</html>









