<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Spot | Admin Dashboard</title>
  <style>
    :root{
      --bg:#f9fafb;
      --card:#ffffff;
      --muted:#6b7280;
      --accent:#7c3aed;
      --glass: rgba(0,0,0,0.04);
      --glass-2: rgba(0,0,0,0.02);
      --success:#10b981;
      --danger:#ef4444;
      --card-radius:14px;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background: #e5e7eb
      color:#111827;
      -webkit-font-smoothing:antialiased;
      padding:18px;
    }

    .wrap{max-width:1100px;margin:0 auto;display:grid;gap:16px}
    .card{background:var(--card);padding:16px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.1);}
    h2{margin:0 0 10px 0;font-size:18px}
    .muted{color:var(--muted);font-size:13px}

    .btn{
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:6px 12px;
      border-radius:10px;
      border:0;
      background:linear-gradient(90deg,var(--accent),#a78bfa);
      color:white;
      text-decoration:none;
      cursor:pointer;
      font-size:13px;
      transition:0.2s;
    }
    .btn:hover{opacity:0.9}
    .btn.ghost{
      background:transparent;
      border:1px solid rgba(0,0,0,0.1);
      color:#374151;
    }

    table{
      width:100%;
      border-collapse:collapse;
      font-size:14px;
      margin-top:10px;
    }
    th,td{
      padding:10px;
      text-align:left;
    }
    th{
      color:var(--muted);
      border-bottom:1px solid rgba(0,0,0,0.1);
    }
    tr:nth-child(even){
      background:rgba(0,0,0,0.02);
    }

    .status{
      padding:4px 8px;
      border-radius:999px;
      font-size:13px;
      font-weight:500;
    }
    .status.active{background:rgba(16,185,129,0.12);color:var(--success);}
    .status.checkout{background:rgba(239,68,68,0.12);color:var(--danger);}
    
    @media(max-width:640px){
      table, thead, tbody, th, td, tr{display:block;}
      th{display:none;}
      td{padding:8px;border-bottom:1px solid rgba(0,0,0,0.05);}
      td::before{
        content:attr(data-label);
        display:block;
        color:var(--muted);
        font-size:12px;
        margin-bottom:4px;
      }
    }

    #end:hover {
      background-color: #f87171;
    }

    #print:hover {
      background-color: #00A300;
    }



  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
          <h2>Admin Dashboard</h2>

          <p class="muted" id="subtitle">Active Guests Currently in the Lounge</p>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn" id="showActive">Active Guests</button>
          <button class="btn ghost" id="showHistory">Check-out History</button>
        </div>
      </div>

      <!-- Active Guests Table -->
      <table id="activeTable">
        <thead>
          <tr>
            <th>Guest Name</th>
            <th>Check-in</th>
            <th>Duration</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
    @foreach($activeSessions as $session)
      <tr>
        <td data-label="Guest Name">{{ $session->guest->fullname }}</td>
        <td data-label="Check-in">{{ \Carbon\Carbon::parse($session->check_in)->format('H:i') }}</td>
        <td data-label="Duration">
      @php
      $checkIn = \Carbon\Carbon::parse($session->check_in);
      $now = \Carbon\Carbon::now();
      $duration = $checkIn->diff($now);
      echo $duration->h . 'h ' . $duration->i . 'm';
      @endphp
        </td>
        <td data-label="Status"><span class="status active">In Session</span></td>
        <td data-label="Actions" style="text-align:right;">
          <button class="btn" onclick="window.location.href='{{ url('/profile/' . $session->guest->id) }}'">View Profile</button>
          <form action="{{ route('sessions.end', $session->id) }}" method="POST" style="display:inline;" onsubmit="return confirmEndSession();">
            @csrf
            <button type="submit" id="end" class="btn ghost">End Session</button>
        </form>

        <script>
        function confirmEndSession() {
            return confirm("Are you sure you want to end this session?");
            
        }
        </script>
        </td>
      </tr>
    @endforeach
        </tbody>
      </table>

      <!-- History Table -->
<table id="historyTable" style="display:none;">
  <thead>
    <tr>
      <th>Guest Name</th>
      <th>Session Time</th>
      <th>Duration</th>
      <th>Bill</th>
      <th>Status</th>
      <th style="text-align:right;">Actions</th>
    </tr>
  </thead>
  <tbody>
@foreach ($historySessions as $date => $sessions)
  {{-- عنوان اليوم --}}
<tr style="background: #efefef;">
  <td colspan="6" style="text-align:center; font-weight:bold; color:#333;">
    📅 {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
  </td>
</tr>

  {{-- السيشنات الخاصة باليوم --}}
  @foreach ($sessions as $session)
    <tr>
      <td data-label="Guest Name">{{ $session->guest->fullname ?? 'N/A' }}</td>

      <td data-label="Session Time">
        {{ \Carbon\Carbon::parse($session->check_in)->format('H:i') }}
        →
        {{ \Carbon\Carbon::parse($session->check_out)->format('H:i') }}
      </td>

      <td data-label="Duration">
        @php
          $checkIn = \Carbon\Carbon::parse($session->check_in);
          $checkOut = \Carbon\Carbon::parse($session->check_out);
          $duration = $checkIn->diff($checkOut);
          echo $duration->h . 'h ' . $duration->i . 'm';
        @endphp
      </td>

      <td data-label="Bill">{{ number_format($session->bill_amount, 2) }} EGP</td>

      <td data-label="Status">
        <span class="status checkout">Checked Out</span>
      </td>
      <td data-label="Actions" style="text-align:right;">
          <button class="btn" onclick="window.location.href='{{ url('/profile/' . $session->guest->id) }}'">View Profile</button>
          <a id="print" href="{{ route('sessions.check', $session->id) }}" class="btn ghost" target="_blank">Print The Check</a>



      </td>
    </tr>
  @endforeach
@endforeach


    @if($historySessions->isEmpty())
      <tr>
        <td colspan="5" style="text-align:center;color:#888;">No check-out history yet</td>
      </tr>
    @endif
  </tbody>
</table>
    </div>
  </div>

  <script>
    const activeBtn = document.getElementById('showActive');
    const historyBtn = document.getElementById('showHistory');
    const activeTable = document.getElementById('activeTable');
    const historyTable = document.getElementById('historyTable');
    const subtitle = document.getElementById('subtitle');

    activeBtn.addEventListener('click', () => {
      activeTable.style.display = 'table';
      historyTable.style.display = 'none';
      subtitle.textContent = 'Active Guests Currently in the Lounge';
      activeBtn.classList.remove('ghost');
      historyBtn.classList.add('ghost');
    });

    historyBtn.addEventListener('click', () => {
      activeTable.style.display = 'none';
      historyTable.style.display = 'table';
      subtitle.textContent = 'Guests Who Checked Out';
      historyBtn.classList.remove('ghost');
      activeBtn.classList.add('ghost');
    });


      // تعمل refresh كل 10 ثواني
  setInterval(function() {
    window.location.reload();
  }, 30000); // 10000ms = 10 ثواني
  

  


  </script>
</body>
</html>