<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <title>Spot | Admin Dashboard</title>
  <style>
    :root{
      --bg:#f9fafb;
      --card:#ffffff;
      --muted:#6b7280;
      --accent:#E0AA3E;
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
      background:linear-gradient(90deg,var(--accent));
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


    .search-row{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:10px;
      border-radius:10px;
      background:rgba(0,0,0,0.03);
      margin-bottom:8px;
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
          <button class="btn ghost" onclick="openHoldSessionsModal()">🕓 Hold Sessions</button>
          <button class="btn ghost" onclick="openGuestSearchModal()">🔍 Search Guest</button>
          <button class="btn ghost" onclick="window.location.href='{{ route('admin.menu.index') }}'" id="menu">Edit Menu</button>
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
      <th>Discount</th>
      <th style="text-align:right;">Actions</th>
    </tr>
  </thead>

  <tbody>
    @foreach($activeSessions as $session)
      <tr>

        <td data-label="Guest Name">
          {{ $session->guest->fullname }}
        </td>

        <td data-label="Check-in">
          {{ \Carbon\Carbon::parse($session->check_in)->format('H:i') }}
        </td>

        <td data-label="Duration" id="duration-{{ $session->id }}">
          @php
            $checkIn = \Carbon\Carbon::parse($session->check_in);
            $now = \Carbon\Carbon::now();
            $duration = $checkIn->diff($now);
            echo $duration->h . 'h ' . $duration->i . 'm';
          @endphp
        </td>

        {{-- People (Manage SubGuests) --}}
        <td data-label="People">
          @if($session->session_type === 'regular')
            <button
              class="btn ghost"
              id="people-count-{{ $session->id }}"
              data-bs-toggle="modal"
              data-bs-target="#subGuestsModal-{{ $session->id }}"
            >
              👥 {{ $session->subGuests->whereNull('left_at')->count() }}
            </button>

          @else
            <span class="muted">—</span>
          @endif
        </td>

        {{-- Type --}}
        <td data-label="Type">
          <select
            onchange="updateSessionType({{ $session->id }}, this.value)"
            style="padding:4px;border-radius:6px;"
          >
            <option value="regular" {{ $session->session_type === 'regular' ? 'selected' : '' }}>
              Regular
            </option>
            <option value="room" {{ $session->session_type === 'room' ? 'selected' : '' }}>
              Room
            </option>
          </select>
        </td>

        {{-- Room --}}
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

        {{-- Status --}}
        <td data-label="Status">
          <span class="status active">In Session</span>
        </td>
        <td data-label="Discount">
  <button
    class="btn ghost"
    onclick="openDiscountModal(
      {{ $session->id }},
      {{ $session->discount_value ?? 0 }},
      @json($session->discount_reason)
    )"
  >
    💸 {{ $session->discount_value ? '-' . $session->discount_value . ' EGP' : 'Set' }}
  </button>
</td>


        {{-- Actions --}}
        <td data-label="Actions" style="text-align:right;">
          <button
            class="btn"
            onclick="window.location.href='{{ url('/profile/' . $session->guest->id) }}'"
          >
            View Profile
          </button>

          <form
            action="{{ route('sessions.end', $session->id) }}"
            method="POST"
            style="display:inline;"
            onsubmit="return confirm('Are you sure you want to end this session?');"
          >
            @csrf
            <button type="submit" class="btn ghost" id="end">
              End Session
            </button>
          </form>
        </td>

      </tr>
    @endforeach
  </tbody>
</table>
@foreach($activeSessions as $session)
  @if($session->session_type === 'regular')
    <div
      class="modal fade"
      id="subGuestsModal-{{ $session->id }}"
      tabindex="-1"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">
              Manage Guests — {{ $session->guest->fullname }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            {{-- Add Sub Guest --}}
            <form
              onsubmit="addSubGuest(event, {{ $session->id }})"
              class="d-flex gap-2 mb-3"
            >
              <input
                type="text"
                class="form-control"
                placeholder="Sub guest name"
                required
                id="subguest-name-{{ $session->id }}"
              >
              <button class="btn btn-primary">
                Add
              </button>
            </form>

            {{-- Sub Guests Table --}}
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Duration</th>
                  <th>Fee</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach($session->subGuests as $sg)
                  @php
                    $in = \Carbon\Carbon::parse($sg->joined_at);
                    $out = $sg->left_at ? \Carbon\Carbon::parse($sg->left_at) : now();
                    $diff = $in->diff($out);
                  @endphp
                  <tr
                      id="subguest-row-{{ $sg->id }}"
                      data-joined="{{ $sg->joined_at }}"
                      data-left="{{ $sg->left_at }}"
                    >

                    <td>{{ $sg->name }}</td>
                    <!-- <td>{{ $diff->h }}h {{ $diff->i }}m</td> -->
                    
                    <td class="sub-duration">0h 0m</td>
                    <td class="sub-fee">25 EGP</td>
                    
                    <td>
                      @if($sg->left_at)
                        <span class="badge bg-secondary">Ended</span>
                      @else
                        <span class="badge bg-success">Active</span>
                      @endif
                    </td>
                    <td>
                      @if(!$sg->left_at)
                        <button
                          class="btn btn-sm btn-danger"
                          onclick="endSubGuest({{ $sg->id }}, {{ $session->id }})"
                        >
                          End
                        </button>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {{-- 👇 Hidden discount value (لـ JS فقط) --}}
            <input
              type="hidden"
              id="discount-value-{{ $session->id }}"
              value="{{ $session->discount_value ?? 0 }}"
            >
            <div class="text-end mt-3">

              <div class="fw-bold">
                Session Total:
                <span id="session-total-{{ $session->id }}">0 EGP</span>
              </div>

              @if($session->discount_value)
                <div class="fw-bold" style="color:#dc2626">
                  Discount:
                  -{{ number_format($session->discount_value, 2) }} EGP
                </div>

                <div class="fw-bold" style="margin-top:4px">
                  Total After Discount:
                  <span id="session-total-after-discount-{{ $session->id }}">
                    0 EGP
                  </span>
                </div>
              @endif

            </div>


          </div>

        </div>
      </div>
      
    </div>
  @endif
@endforeach

      
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
  $daySessions = collect($sessions);

  $dayTotal = $daySessions->sum(function ($s) {

      $drinksTotal = $s->orders
          ->where('status', 'Received')
          ->sum(function ($order) {
              return $order->total_price
                  ?? (($order->unit_price ?? 0) * ($order->quantity ?? 1));
          });

      return (float) ($s->bill_amount ?? 0) + $drinksTotal;
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

      @php
  $drinksTotal = $session->orders
      ->where('status', 'Received')
      ->sum(function ($order) {
          return $order->total_price
              ?? (($order->unit_price ?? 0) * ($order->quantity ?? 1));
      });

  $grandTotal = ($session->bill_amount ?? 0) + $drinksTotal;
@endphp

<td data-label="Bill">
  {{ number_format($grandTotal, 2) }} EGP
</td>


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
    let scannerEnabled = true;

    // حافظ على الفوكس
    function keepFocus() {
        if (scannerEnabled) {
            scannerInput.focus();
        }
    }
    
    setInterval(keepFocus, 1000);
    keepFocus();

      document.addEventListener('focusin', function (e) {
    if (e.target.tagName === 'SELECT' || e.target.tagName === 'INPUT') {
      scannerEnabled = false;
    }
  });

  document.addEventListener('focusout', function (e) {
    if (e.target.tagName === 'SELECT' || e.target.tagName === 'INPUT') {
      setTimeout(() => {
        scannerEnabled = true;
        scannerInput.focus();
      }, 300);
    }
  });
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

<script>
function addSubGuest(e, sessionId) {
  e.preventDefault();

  const input = document.getElementById(`subguest-name-${sessionId}`);
  const name = input.value.trim();
  if (!name) return;

  fetch(`/admin/sessions/${sessionId}/sub-guests`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ name })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status !== 'success') return alert('Failed');

    location.reload(); // مؤقتًا — هنشيله بعدين
  });
  updateSubGuestsTimers();

}

function endSubGuest(subGuestId, sessionId) {
  if (!confirm('Are you sure you want to end this guest?')) return;

  fetch(`/admin/sub-guests/${subGuestId}/end`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(res => res.json())
  .then(() => {
    location.reload(); // مؤقتًا
  });
  updateSubGuestsTimers();
}
</script>

<script>
function addSubGuest(e, sessionId) {
  e.preventDefault();

  const input = document.getElementById(`subguest-name-${sessionId}`);
  const name = input.value.trim();
  if (!name) return;

  fetch(`/admin/sessions/${sessionId}/sub-guests`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ name })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status !== 'success') {
      alert('Failed to add guest');
      return;
    }

    const sg = data.sub_guest;

    const tableBody = document.querySelector(
      `#subGuestsModal-${sessionId} tbody`
    );

    const row = document.createElement('tr');
    row.dataset.joined = sg.joined_at;
    row.dataset.left = '';

    row.id = `subguest-row-${sg.id}`;
    row.innerHTML = `
  <td>${sg.name}</td>
  <td class="sub-duration">0h 0m</td>
  <td class="sub-fee">25 EGP</td>
  <td><span class="badge bg-success">Active</span></td>
  <td>
    <button
      class="btn btn-sm btn-danger"
      onclick="endSubGuest(${sg.id}, ${sessionId})"
    >
      End
    </button>
  </td>
`;


    tableBody.appendChild(row);

    // Update counter
    const counter = document.getElementById(`people-count-${sessionId}`);
    const current = parseInt(counter.textContent.replace(/\D/g, '')) || 0;
    counter.innerHTML = `👥 ${current + 1}`;

    input.value = '';
  })
  .catch(() => alert('Error adding guest'));
}

function endSubGuest(subGuestId, sessionId) {
  if (!confirm('Are you sure you want to end this guest?')) return;

  fetch(`/admin/sub-guests/${subGuestId}/end`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(res => res.json())
  .then(data => {
    if (data.status !== 'success') {
      alert('Failed to end guest');
      return;
    }

    const row = document.getElementById(`subguest-row-${subGuestId}`);
    if (!row) return;

    // 🟢 1) ثبت وقت الخروج عشان التايمر يقف
    row.dataset.left = new Date().toISOString();

    // 🟢 2) Update status (العمود الرابع بعد إضافة Fee)
    row.children[3].innerHTML =
      '<span class="badge bg-secondary">Ended</span>';

    // 🟢 3) شيل زر End (العمود الخامس)
    row.children[4].innerHTML = '';

    // 🟢 4) Update people counter
    const counter = document.getElementById(`people-count-${sessionId}`);
    const current = parseInt(counter.textContent.replace(/\D/g, '')) || 1;
    counter.innerHTML = `👥 ${Math.max(0, current - 1)}`;
  })
  .catch(() => alert('Error ending guest'));
}

</script>


<script>
function calculateFee(hours) {
  const grace = 0.5;

  if (hours < 1 + grace) return 25;
  if (hours < 3 + grace) return 50;
  if (hours < 6 + grace) return 80;
  if (hours < 8 + grace) return 100;
  if (hours < 12 + grace) return 120;
  return 150;
}

function updateSubGuestsTimers() {

  const totals = {}; // total per session

  document.querySelectorAll('[id^="subguest-row-"]').forEach(row => {

    const joinedAt = row.dataset.joined;
    const leftAt = row.dataset.left;
    if (!joinedAt) return;

    const start = new Date(joinedAt);
    const end = leftAt ? new Date(leftAt) : new Date();

    const diffMs = end - start;
    if (diffMs < 0) return;

    const totalMinutes = Math.floor(diffMs / 60000);
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    const hoursFloat = hours + (minutes / 60);

    const durationCell = row.querySelector('.sub-duration');
    const feeCell = row.querySelector('.sub-fee');

    const fee = calculateFee(hoursFloat);

    if (durationCell) {
      durationCell.textContent = `${hours}h ${minutes}m`;
    }

    if (feeCell) {
      feeCell.textContent = fee + ' EGP';
    }

    // 👇 نجمع التوتال حسب السيشن
    const sessionId = row.closest('.modal').id.replace('subGuestsModal-', '');
    totals[sessionId] = (totals[sessionId] || 0) + fee;
  });

  // 👇 نحدث التوتال في الـ UI
for (const sessionId in totals) {

  const baseTotal = totals[sessionId];

  // Total قبل الخصم
  const totalEl = document.getElementById(`session-total-${sessionId}`);
  if (totalEl) {
    totalEl.textContent = baseTotal + ' EGP';
  }

  // 👇 لو فيه خصم
  const discountValue = parseFloat(
    document
      .getElementById(`discount-value-${sessionId}`)
      ?.value || 0
  );

  const afterDiscountEl =
    document.getElementById(`session-total-after-discount-${sessionId}`);

  if (afterDiscountEl) {
    const finalTotal = Math.max(0, baseTotal - discountValue);
    afterDiscountEl.textContent = finalTotal + ' EGP';
  }
}

}


setInterval(updateSubGuestsTimers, 60000);
updateSubGuestsTimers();
</script>

<div class="modal fade" id="discountModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Session Discount</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="discount-session-id">

        <div class="mb-3">
          <label class="form-label">Discount Amount (EGP)</label>
          <input
            type="number"
            class="form-control"
            id="discount-value"
            min="0"
            step="0.01"
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Reason (optional)</label>
          <textarea
            class="form-control"
            id="discount-reason"
            rows="3"
          ></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn ghost" data-bs-dismiss="modal">Cancel</button>
        <button class="btn" onclick="saveDiscount()">Save</button>
      </div>

    </div>
  </div>
</div>

<script>
let discountModal = new bootstrap.Modal(
  document.getElementById('discountModal')
);

function openDiscountModal(sessionId, value, reason) {
  document.getElementById('discount-session-id').value = sessionId;
  document.getElementById('discount-value').value = value || '';
  document.getElementById('discount-reason').value = reason || '';
  discountModal.show();
}

function saveDiscount() {
  const sessionId = document.getElementById('discount-session-id').value;
  const value = document.getElementById('discount-value').value;
  const reason = document.getElementById('discount-reason').value;

  fetch(`/admin/sessions/${sessionId}/discount`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      discount_value: value,
      discount_reason: reason
    })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      location.reload(); // safe & simple
    } else {
      alert('Failed to save discount');
    }
  })
  .catch(() => alert('Error saving discount'));
}
</script>

<div class="modal fade" id="guestSearchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">🔍 Search Guest</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Search Input -->
        <input
          type="text"
          class="form-control"
          placeholder="Search by name or phone..."
          id="guest-search-input"
        >

        <!-- Results -->
        <div id="guest-search-results" style="margin-top:16px">

          <div class="muted" id="guest-search-empty">
            Start typing to search guests
          </div>

          <!-- Dummy result (UX only) -->
          <!--
          <div class="search-row">
            <div>
              <strong>Ahmed Hassan</strong>
              <div class="muted">01012345678</div>
            </div>
            <button class="btn btn-sm">View Profile</button>
          </div>
          -->

        </div>

      </div>

    </div>
  </div>
</div>
<script>
const guestSearchModal = new bootstrap.Modal(
  document.getElementById('guestSearchModal')
);

function openGuestSearchModal() {
  document.getElementById('guest-search-input').value = '';
  document.getElementById('guest-search-results').innerHTML =
    '<div class="muted">Start typing to search guests</div>';

  guestSearchModal.show();
}
</script>

<script>
const searchInput  = document.getElementById('guest-search-input');
const resultsBox  = document.getElementById('guest-search-results');

let searchTimeout = null;

searchInput.addEventListener('input', function () {

  const q = this.value.trim();

  clearTimeout(searchTimeout);

  if (q.length < 3) {
    resultsBox.innerHTML =
      '<div class="muted">Type at least 3 characters</div>';
    return;
  }

  resultsBox.innerHTML = '<div class="muted">Searching...</div>';

  searchTimeout = setTimeout(() => {
    fetch(`/admin/guests/search?q=${encodeURIComponent(q)}`)
      .then(res => res.json())
      .then(renderGuestResults)
      .catch(() => {
        resultsBox.innerHTML =
          '<div class="muted">Search failed</div>';
      });
  }, 300); // debounce
});

function renderGuestResults(guests) {

  if (!guests.length) {
    resultsBox.innerHTML =
      '<div class="muted">No guests found</div>';
    return;
  }

  resultsBox.innerHTML = '';

  guests.forEach(g => {
    const row = document.createElement('div');
    row.className = 'search-row';

    row.innerHTML = `
      <div>
        <strong>${g.fullname}</strong>
        <div class="muted">${g.phone ?? ''}</div>
      </div>
      <a class="btn btn-sm" href="/profile/${g.id}">
        View Profile
      </a>
    `;

    resultsBox.appendChild(row);
  });
}
</script>

<div class="modal fade" id="holdSessionsModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">🕓 Hold Sessions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Empty state -->
        <div class="muted" id="hold-empty">
          No hold sessions yet
        </div>

        <!-- Dummy Hold Session (UX only) -->
        <!--
        <div class="search-row">
          <div>
            <strong>Ahmed Hassan</strong>
            <div class="muted">Requested at: 14:32</div>
          </div>

          <div style="display:flex;gap:6px;">
            <button class="btn btn-sm">Accept</button>
            <button class="btn btn-sm ghost">Reject</button>
          </div>
        </div>
        -->

      </div>

    </div>
  </div>
</div>
<script>
const holdSessionsModal = new bootstrap.Modal(
  document.getElementById('holdSessionsModal')
);

function openHoldSessionsModal() {
  holdSessionsModal.show();
}
</script>

<script>
function loadHoldSessions() {
  fetch('/admin/hold-sessions')
    .then(res => res.json())
    .then(data => {
      const body = document.querySelector('#holdSessionsModal .modal-body');

      if (!data.length) {
        body.innerHTML = '<div class="muted">No hold sessions yet</div>';
        return;
      }

      body.innerHTML = '';

      data.forEach(h => {
        body.innerHTML += `
          <div class="search-row">
            <div>
              <strong>${h.guest_name}</strong>
              <div class="muted">Requested at: ${h.requested_at}</div>
            </div>
            <div style="display:flex;gap:6px;">
              <button class="btn btn-sm" onclick="acceptHold(${h.guest_id})">Accept</button>
              <button class="btn btn-sm ghost" onclick="rejectHold(${h.guest_id})">Reject</button>
            </div>
          </div>
        `;
      });
    });
}

function acceptHold(guestId) {
  if (!confirm('Accept this session and start it now?')) return;

  fetch('/admin/hold-sessions/accept', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ guest_id: guestId })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      // نفس منطق Start Session
      window.location.href = '/admin/dashboard';
    } else {
      alert(data.message || 'Failed to start session');
    }
  })
  .catch(() => {
    alert('Failed to accept hold session');
  });
}


function rejectHold(guestId) {
  fetch('/admin/hold-sessions/reject', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ guest_id: guestId })
  }).then(() => loadHoldSessions());
}

function openHoldSessionsModal() {
  loadHoldSessions();
  holdSessionsModal.show();
}
</script>


</body>
</html>