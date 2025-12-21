<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Spot | Host Dashboard</title>
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
          <h2>Host Dashboard</h2>

          <p class="muted" id="subtitle">Active Guests Currently in the Lounge</p>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn" id="showActive">Active Guests</button>
          <button class="btn ghost" id="showHistory">Check-out History</button>
          <!-- <button class="btn ghost" onclick="window.location.href='{{ route('admin.menu.index') }}'" id="menu">Edit Menu</button> -->
        </div>
      </div>

      <!-- Active Guests Table -->
      <table id="activeTable">
        <thead>
          <tr>
            <th>Guest Name</th>
            <th>Check-in</th>
            <th>Duration</th>
            <th>People</th>
            <th>Type</th>
            <th>Room</th>
            <th>Status</th>
            <th style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody>
    @foreach($activeSessions as $session)
      <tr>
        <td data-label="Guest Name">{{ $session->guest->fullname }}</td>
        <td data-label="Check-in">{{ \Carbon\Carbon::parse($session->check_in)->format('H:i') }}</td>
        <td data-label="Duration" id="duration-{{ $session->id }}">
      @php
      $checkIn = \Carbon\Carbon::parse($session->check_in);
      $now = \Carbon\Carbon::now();
      $duration = $checkIn->diff($now);
      echo $duration->h . 'h ' . $duration->i . 'm';
      @endphp
        </td>
        <td data-label="People">
          <input 
            type="number"
            min="1"
            value="{{ $session->people_count }}"
            style="width:60px;padding:4px;border-radius:6px;border:1px solid #ccc;"
            onchange="updatePeople({{ $session->id }}, this.value)"
          >
        </td>
        <td data-label="Type">
  <select
    onchange="updateSessionType({{ $session->id }}, this.value)"
    style="padding:4px;border-radius:6px;"
  >
    <option value="regular" {{ $session->session_type === 'regular' ? 'selected' : '' }}>Regular</option>
    <option value="room" {{ $session->session_type === 'room' ? 'selected' : '' }}>Room</option>
  </select>
</td>
<td data-label="Room">
  <select
    onchange="updateRoom({{ $session->id }}, this.value)"
    {{ $session->session_type !== 'room' ? 'disabled' : '' }}
    style="padding:4px;border-radius:6px;"
  >
    <option value="">—</option>
    <option value="1" {{ $session->room_number == 1 ? 'selected' : '' }}>Room 1</option>
    <option value="2" {{ $session->room_number == 2 ? 'selected' : '' }}>Room 2</option>
    <option value="3" {{ $session->room_number == 3 ? 'selected' : '' }}>Room 3</option>
    <option value="4" {{ $session->room_number == 4 ? 'selected' : '' }}>Room 4</option>
  </select>
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
      
      <script>
  setInterval(() => {
    fetch('{{ route('admin.sessions.durations') }}')
      .then(res => res.json())
      .then(data => {
        for (const [sessionId, duration] of Object.entries(data)) {
          const cell = document.getElementById(`duration-${sessionId}`);
          if (cell) cell.textContent = duration;
        }
      })
      .catch(err => console.error('Error updating durations:', err));
  }, 10000); // يحدث كل 10 ثواني
</script>


      <!-- History Table -->
<table id="historyTable" style="display:none;">
  <thead>
    <tr>
      <th>Guest Name</th>
      <th>Session Time</th>
      <th>Duration</th>
      <th>Type</th>
      <th>Bill</th>
      <th>Status</th>
      <th style="text-align:right;">Actions</th>
    </tr>
  </thead>
  <tbody>

  @foreach ($historySessions as $date => $sessions)
    {{-- عنوان اليوم --}}

    @php
    // نتأكد من تحويل $sessions الى Collection علشان نقدر نستخدم sum بسهولة
    $daySessions = collect($sessions);
    $dayTotal = $daySessions->sum(function($s) {
        return (float) ($s->bill_amount ?? 0);
    });
  @endphp

<tr style="background: #efefef;">
  <td colspan="10" style="text-align:center; font-weight:bold; color:#333;">
    📅 {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d M Y') }}
    <span style="margin-left:12px; font-weight:600; color:#111;">— Total: {{ number_format($dayTotal, 2) }} EGP</span>
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

      <td data-label="Type">
        @if($session->session_type === 'room')
          Room {{ $session->room_number }}
        @else
          Regular
        @endif
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
      <!-- Hidden input to capture QR scans -->
    <input type="text" id="hiddenScanner" style="opacity:0;position:absolute;left:-9999px;">
    <!-- <input type="text" id="hiddenScanner" > -->

    <!-- <script>
    const scannerInput = document.getElementById('hiddenScanner');

    // حافظ على الفوكس
    function keepFocus() {
      scannerInput.focus();
    }
    setInterval(keepFocus, 1000);
    keepFocus();

    // إضافة بسيطة مع debounce بدلاً من dispatch فوري
    let dispatchTimeout = null;
    scannerInput.addEventListener('input', function () {
      clearTimeout(dispatchTimeout);
      // نأجل تنفيذ change لحد ما يوقف الإدخال 120ms
      dispatchTimeout = setTimeout(() => {
        scannerInput.dispatchEvent(new Event('change'));
      }, 120);
    });

    // باقي سكريبتك بدون أي تعديل
    scannerInput.addEventListener('change', function () {
  const rawValue = scannerInput.value;
  scannerInput.value = '';

  console.log('RAW SCAN:', rawValue);

  const match = rawValue.match(/=(\d+)/);

  console.log('MATCH:', match);

  if (!match) {
    alert('NO MATCH FOUND');
    return;
  }

  const guestId = match[1];

  console.log('GUEST ID SENT:', guestId);

  fetch(`/scan?guest_id=${guestId}`)
    .then(res => res.json())
    .then(data => {
      console.log('SERVER RESPONSE:', data);
      if (data.status === 'success') {
        location.reload();
      } else {
        alert('⚠️ ' + data.message);
      }
    });
});

</script> -->



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


  </script>
  <script>
function updatePeople(sessionId, value) {
  if (value < 1) {
    alert('People count must be at least 1');
    return;
  }

  fetch(`/admin/sessions/${sessionId}/people`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ people_count: value })
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      alert('Failed to update people count');
    }
  })
  .catch(() => alert('Error updating people count'));
}
</script>
<script>
function updateSessionType(sessionId, value) {
  fetch(`/admin/sessions/${sessionId}/type`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ session_type: value })
  }).then(() => location.reload());
}

function updateRoom(sessionId, value) {
  fetch(`/admin/sessions/${sessionId}/room`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ room_number: value })
  });
}
</script>
<script>
function updateSessionType(sessionId, value) {
  fetch(`/admin/sessions/${sessionId}/type`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ session_type: value })
  }).then(() => {
    location.reload(); // مؤقتًا
  });
}
</script>

<script>
let scanBuffer = '';
let scanTimeout = null;

document.addEventListener('keydown', function (e) {

  const activeEl = document.activeElement;

  // ❌ تجاهل الكتابة في inputs العادية
  // ✅ اسمح بالـ hiddenScanner
  if (
    activeEl &&
    activeEl.tagName === 'INPUT' &&
    activeEl.id !== 'hiddenScanner'
  ) {
    return;
  }

  if (activeEl && activeEl.tagName === 'SELECT') {
    return;
  }

  // أغلب scanners بتبعت Enter في الآخر
  if (e.key === 'Enter') {
    if (scanBuffer.length > 3) {
      handleScan(scanBuffer);
    }
    scanBuffer = '';
    return;
  }

  // ناخد الحروف بس
  if (e.key.length === 1) {
    scanBuffer += e.key;
  }

  clearTimeout(scanTimeout);
  scanTimeout = setTimeout(() => {
    scanBuffer = '';
  }, 120);
});

function handleScan(rawValue) {
  console.log('RAW SCAN:', rawValue);

  const match = rawValue.match(/=(\d+)/);
  if (!match) {
    alert('Invalid QR Code');
    return;
  }

  const guestId = match[1];

  console.log('GUEST ID:', guestId);

  fetch(`/scan?guest_id=${guestId}`)
    .then(res => res.json())
    .then(data => {
      console.log('SERVER RESPONSE:', data);
      if (data.status === 'success') {
        location.reload();
      } else {
        alert('⚠️ ' + data.message);
      }
    })
    .catch(() => alert('Scan failed'));
}
</script>
</body>
</html>