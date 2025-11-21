@php
// This is a single Blade file that contains the Barista Dashboard view.
// It expects three collections passed from controller:
// $pendingOrders, $inProgressOrders, $doneOrders
@endphp

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Spot | Barista Dashboard</title>
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
      background: #e5e7eb;
      color:#111827;
      -webkit-font-smoothing:antialiased;
      padding:18px;
    }
    .wrap{max-width:1100px;margin:0 auto;display:grid;gap:16px}
    .card{background:var(--card);padding:16px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.1);}
    h2{margin:0 0 10px 0;font-size:18px}
    .muted{color:var(--muted);font-size:13px}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent),#a78bfa);color:white;text-decoration:none;cursor:pointer;font-size:13px;transition:0.2s;}
    .btn:hover{opacity:0.9}
    .btn.ghost{background:transparent;border:1px solid rgba(0,0,0,0.1);color:#374151;}
    table{width:100%;border-collapse:collapse;font-size:14px;margin-top:10px;}
    th,td{padding:10px;text-align:left;}
    th{color:var(--muted);border-bottom:1px solid rgba(0,0,0,0.1);}
    tr:nth-child(even){background:rgba(0,0,0,0.02);}
    .status{padding:4px 8px;border-radius:999px;font-size:13px;font-weight:500;}
    .status.active{background:rgba(16,185,129,0.12);color:var(--success);}
    .status.checkout{background:rgba(239,68,68,0.12);color:var(--danger);}
    .status.pending{background:rgba(245,158,11,0.12);color:#f59e0b;}
    .status.progress{background:rgba(59,130,246,0.08);color:#2563eb;}
    .status.done{background:rgba(16,185,129,0.08);color:var(--success);}
    @media(max-width:640px){
      table, thead, tbody, th, td, tr{display:block;}
      th{display:none;}
      td{padding:8px;border-bottom:1px solid rgba(0,0,0,0.05);}
      td::before{content:attr(data-label);display:block;color:var(--muted);font-size:12px;margin-bottom:4px;}
    }
    #accept:hover { background-color: #10b981; }
    #print:hover { background-color: #00A300; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
          <h2>Barista Dashboard</h2>
          <p class="muted" id="subtitle">Current Orders</p>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn" id="showCurrent">Current Orders</button>
          <button class="btn ghost" id="showPast">Past Orders</button>
          <a href="{{ route('barista.menu') }}" class="btn ghost">Open Menu</a>
        </div>
      </div>

      {{-- SECTION: Current Orders --}}
      <div id="sectionCurrent">
        {{-- Orders: Pending --}}
        <hr style="margin:30px 0;opacity:0.2;">
        <h2 style="margin-top:0;">Orders — Pending Requests</h2>
        <p class="muted">Orders that were requested but not accepted yet</p>

        <table aria-label="Pending Orders">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Order</th>
              <th>Requested At</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="pendingTbody">
          @forelse($pendingOrders as $order)
<tr>
  <td data-label="Customer">{{ optional($order->session->guest)->fullname ?? 'N/A' }}</td>
  <td data-label="Order">
    {{ optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id }} ×{{ $order->quantity ?? 1 }}
    <br><small class="muted">{{ number_format($order->unit_price ?? 0, 2) }} EGP each</small>
  </td>
  <td data-label="Requested At">{{ $order->created_at->format('H:i') }}</td>
  <td data-label="Status"><span class="status pending">Pending</span></td>
  <td data-label="Actions" style="text-align:right;">
    <form class="ajax-action" data-action="accept" action="{{ route('orders.accept', $order->id) }}" method="POST" style="display:inline;">@csrf<button class="btn">Accept</button></form>
    <form class="ajax-action" data-action="cancel" action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;">@csrf<button class="btn ghost">Cancel</button></form>
  </td>
</tr>
@empty
<tr><td colspan="5">No pending orders.</td></tr>
@endforelse


          </tbody>
        </table>

        {{-- Orders: In Progress --}}
        <hr style="margin:30px 0;opacity:0.12;">
        <h2 style="margin-top:0;">Orders — In Progress</h2>
        <p class="muted">Orders that were accepted and are being prepared</p>

        <table aria-label="In Progress Orders">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Order</th>
              <th>Accepted At</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="inProgressTbody">
          @forelse($inProgressOrders as $order)
<tr>
  <td data-label="Customer">{{ optional($order->session->guest)->fullname ?? 'N/A' }}</td>
  <td data-label="Order">
    {{ optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id }} ×{{ $order->quantity ?? 1 }}
    <br><small class="muted">{{ number_format($order->unit_price ?? 0, 2) }} EGP each</small>
  </td>
  <td data-label="Accepted At">{{ $order->accepted_at ? $order->accepted_at->format('H:i') : $order->updated_at->format('H:i') }}</td>
  <td data-label="Status"><span class="status progress">In Progress</span></td>
  <td data-label="Actions" style="text-align:right;">
    <form class="ajax-action" data-action="done" action="{{ route('orders.markDone', $order->id) }}" method="POST" style="display:inline;">@csrf<button class="btn">Done</button></form>
    <form class="ajax-action" data-action="cancel" action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline;">@csrf<button class="btn ghost">Cancel</button></form>
  </td>
</tr>
@empty
<tr><td colspan="5">No in-progress orders.</td></tr>
@endforelse

          </tbody>
        </table>
      </div>

      {{-- SECTION: Past Orders --}}
      <div id="sectionPast" style="display:none;">
        <hr style="margin:30px 0;opacity:0.12;">
        <h2 style="margin-top:0;">Orders — Done</h2>
        <p class="muted">Completed orders</p>

        <table aria-label="Done Orders">
          <thead>
            <tr>
              <th>Customer</th>
              <th>Order</th>
              <th>Served At</th>
              <th>Bill</th>
              <th>Status</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody id="doneTbody">
          @forelse($doneOrders as $order)
<tr>
  <td data-label="Customer">{{ optional($order->session->guest)->fullname ?? $order->ordered_by ?? 'N/A' }}</td>
  <td data-label="Order">
    {{ optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id }} ×{{ $order->quantity ?? 1 }}
    <br><small class="muted">{{ number_format($order->unit_price ?? 0, 2) }} EGP each</small>
  </td>
  <td data-label="Served At">{{ optional($order->served_at)->format('H:i') ?? $order->updated_at->format('H:i') }}</td>
  <td data-label="Bill">{{ number_format($order->total_price ?? ($order->unit_price * ($order->quantity ?? 1)), 2) }} EGP</td>
  <td data-label="Status"><span class="status done">Done</span></td>
  <td data-label="Actions" style="text-align:right;">
    <button class="btn" onclick="window.location.href='{{ route('profile.user', optional($order->session->guest)->id ?? '') }}'">View Profile</button>
    <a class="btn ghost" href="#" onclick="alert('Print static')">Print The Check</a>
  </td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center;color:#888;">No done orders</td></tr>
@endforelse

          </tbody>
        </table>
      </div>

    </div>
  </div>
  
  <script>
/* ======= UI refs ======= */
const showCurrentBtn = document.getElementById('showCurrent');
const showPastBtn = document.getElementById('showPast');
const sectionCurrent = document.getElementById('sectionCurrent');
const sectionPast = document.getElementById('sectionPast');
const subtitle = document.getElementById('subtitle');

showCurrentBtn.addEventListener('click', () => {
  sectionCurrent.style.display = 'block'; sectionPast.style.display = 'none';
  subtitle.textContent = 'Current Orders';
  showCurrentBtn.classList.remove('ghost'); showPastBtn.classList.add('ghost');
});
showPastBtn.addEventListener('click', () => {
  sectionCurrent.style.display = 'none'; sectionPast.style.display = 'block';
  subtitle.textContent = 'Past Orders';
  showPastBtn.classList.remove('ghost'); showCurrentBtn.classList.add('ghost');
});
(function init(){ sectionCurrent.style.display='block'; sectionPast.style.display='none'; showCurrentBtn.classList.remove('ghost'); showPastBtn.classList.add('ghost'); })();

const PARTIALS_URL_BASE = '{{ route("barista.orders.partials") }}';
const pendingTbody = document.getElementById('pendingTbody');
const inProgressTbody = document.getElementById('inProgressTbody');
const doneTbody = document.getElementById('doneTbody');

/* ======= helpers ======= */
function getCsrfTokenFromDocumentOrForm(form = null) {
  const meta = document.querySelector('meta[name="csrf-token"]');
  if (meta && meta.getAttribute('content')) return meta.getAttribute('content');
  if (form) {
    const q = form.querySelector('input[name="_token"]');
    if (q && q.value) return q.value;
  }
  const anyToken = document.querySelector('input[name="_token"]');
  if (anyToken && anyToken.value) return anyToken.value;
  return null;
}

function nowTimeHHMM() {
  const d = new Date();
  return d.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function cloneRowAndSetOrderId(tr, orderId) {
  const cloned = tr.cloneNode(true);
  if (orderId) cloned.dataset.orderId = String(orderId);
  return cloned;
}

/* create action forms html for an order id (keeps same structure used in blade) */
function makeActionFormsForOrder(orderId) {
  // uses same routes pattern: /orders/{id}/mark-done  and /orders/{id}/cancel
  const csrf = '<input type="hidden" name="_token" value="' + (document.querySelector('meta[name="csrf-token"]')?.content || '') + '">';
  // Accept is not needed here; for pending rows we want Accept button; but when moving to inProgress we create Done + Cancel
  const doneForm =
    `<form class="ajax-action" data-action="done" action="/orders/${orderId}/mark-done" method="POST" style="display:inline;">${csrf}<button class="btn">Done</button></form>`;
  const cancelForm =
    `<form class="ajax-action" data-action="cancel" action="/orders/${orderId}/cancel" method="POST" style="display:inline;">${csrf}<button class="btn ghost">Cancel</button></form>`;
  return doneForm + cancelForm;
}

// Replace existing getOrderIdFromTr with this more robust version
function getOrderIdFromTr(tr) {
  if (!tr) return null;

  // 1) direct dataset attribute (best)
  if (tr.dataset && tr.dataset.orderId) return String(tr.dataset.orderId);

  // 2) data-order-id attribute (sometimes added without dataset)
  const dataAttr = tr.getAttribute && tr.getAttribute('data-order-id');
  if (dataAttr) return String(dataAttr);

  // 3) hidden input fields like <input name="order_id" value="123">
  const hid = tr.querySelector && (tr.querySelector('input[name="order_id"]') || tr.querySelector('input[name="orderId"]') || tr.querySelector('input[name="id"]'));
  if (hid && hid.value) return String(hid.value);

  // 4) any form action inside the tr: look for /orders/{id} or /barista/orders/{id}
  const form = tr.querySelector && tr.querySelector('form[action]');
  if (form) {
    const action = form.getAttribute('action') || '';
    let m = action.match(/\/orders\/(\d+)/i);
    if (m) return String(m[1]);
    m = action.match(/\/barista\/orders\/(\d+)/i);
    if (m) return String(m[1]);
  }

  // 5) look anywhere in the tr.innerHTML for /orders/{id} pattern (covers cases where action uses full URL or different prefix)
  try {
    const raw = tr.innerHTML || '';
    let m = raw.match(/\/orders\/(\d+)/i);
    if (m) return String(m[1]);
    m = raw.match(/orders[^\d]{0,4}(\d{3,8})/i); // loose: "orders 123" or "orders:123"
    if (m) return String(m[1]);
  } catch (e) {
    // ignore
  }

  // 6) fallback: try to extract number from first link that looks like it contains an id
  const a = tr.querySelector && tr.querySelector('a[href]');
  if (a) {
    const href = a.getAttribute('href') || '';
    const mm = href.match(/\/orders\/(\d+)/i);
    if (mm) return String(mm[1]);
  }

  // no id found
  return null;
}


/* safe insert row at top of tbody */
function insertRowAtTop(tbody, tr) {
  if (!tbody) return;
  // if firstChild is a text node or null, insertBefore handles it
  tbody.insertBefore(tr, tbody.firstChild);
  tr.style.transition = 'background 0.3s';
  tr.style.background = 'rgba(124,58,237,0.06)';
  setTimeout(()=> tr.style.background = '', 450);
}

/* ======= smart sync (lighter than before but effective) ======= */
async function syncOrders() {
  const url = PARTIALS_URL_BASE + '?t=' + Date.now();
  try {
    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json,text/html' }, cache: 'no-store', credentials: 'same-origin' });
    // diagnostics
    console.log('[syncOrders] status', res.status, 'content-type', res.headers.get('content-type'));

    const ct = (res.headers.get('content-type') || '').toLowerCase();
    if (ct.includes('application/json')) {
      const data = await res.json().catch(()=>null);
      if (!data) return;
      // data.pending etc expected as HTML strings (<tr>...</tr> rows or a full tbody)
      const applyFragment = (html, targetTbody) => {
        if (!html || !targetTbody) return;
        // if html contains <tbody id="...">, extract inner <tr> list
        let tmp = document.createElement('table');
        tmp.innerHTML = html;
        const trs = tmp.querySelectorAll('tr');
        // build map of existing ids
        const existingIds = new Set(Array.from(targetTbody.querySelectorAll('tr')).map(t => getOrderIdFromTr(t)).filter(Boolean));
        // add new rows
        Array.from(trs).reverse().forEach(tr => {
          const id = getOrderIdFromTr(tr) || null;
          if (id && existingIds.has(String(id))) {
            // update existing row if different
            const existing = targetTbody.querySelector(`tr[data-order-id="${id}"]`) || targetTbody.querySelector(`tr:has(form[action*="/orders/${id}"])`);
            if (existing && existing.innerText !== tr.innerText) existing.replaceWith(tr.cloneNode(true));
          } else {
            // insert new on top
            insertRowAtTop(targetTbody, tr.cloneNode(true));
          }
        });
        // remove rows that are no longer present in server fragment
        const incomingIds = new Set(Array.from(trs).map(t => getOrderIdFromTr(t)).filter(Boolean));
        Array.from(targetTbody.querySelectorAll('tr')).forEach(curr => {
          const cid = getOrderIdFromTr(curr);
          if (cid && !incomingIds.has(String(cid))) {
            curr.style.transition = 'opacity 0.2s';
            curr.style.opacity = '0.4';
            setTimeout(()=> curr.remove(), 160);
          }
        });
      };

      applyFragment(data.pending || '', pendingTbody);
      applyFragment(data.inProgress || data.in_progress || '', inProgressTbody);
      applyFragment(data.done || '', doneTbody);
      return;
    }

    // fallback to HTML/text
    const text = await res.text().catch(()=>null);
    if (!text) return;

    // try to extract tbody fragments
    const extract = (which) => {
      const re = new RegExp(`<tbody\\s+id="${which}"[\\s\\S]*?>([\\s\\S]*?)<\\/tbody>`, 'i');
      const m = text.match(re);
      return m ? `<tbody id="${which}">` + m[1] + `</tbody>` : null;
    };
    const pendingHtml = extract('pendingTbody');
    const inProgressHtml = extract('inProgressTbody');
    const doneHtml = extract('doneTbody');

    // reuse simple apply: create table tmp and move trs
    const applyHtml = (h, tb) => {
      if (!h || !tb) return;
      let tmp = document.createElement('table');
      tmp.innerHTML = h;
      const trs = tmp.querySelectorAll('tr');
      Array.from(trs).reverse().forEach(tr => insertRowAtTop(tb, tr.cloneNode(true)));
    };

    if (pendingHtml) applyHtml(pendingHtml, pendingTbody);
    if (inProgressHtml) applyHtml(inProgressHtml, inProgressTbody);
    if (doneHtml) applyHtml(doneHtml, doneTbody);

  } catch (err) {
    console.error('[syncOrders] error', err);
  }
}

/* ======= delegated submit handler (handles accept/done/cancel) ======= */
document.addEventListener('submit', async function (e) {
  const form = e.target;
  if (!form || !form.matches || !form.matches('form.ajax-action')) return;
  e.preventDefault();

  if (form.dataset.action === 'cancel' && !confirm('Are you sure you want to cancel this order?')) return;

  const action = form.getAttribute('action');
  const method = (form.getAttribute('method') || 'POST').toUpperCase();
  const formData = new FormData(form);
  const token = getCsrfTokenFromDocumentOrForm(form);
  if (!token) console.warn('[AJAX] no csrf token found');

  try {
    const res = await fetch(action, {
      method,
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json,text/html', ...(token ? {'X-CSRF-TOKEN': token} : {}) },
      body: formData,
      credentials: 'same-origin',
      cache: 'no-store',
      redirect: 'follow'
    });

    console.log('[AJAX] action', form.dataset.action, 'status', res.status, 'url', res.url);
    const ct = (res.headers.get('content-type') || '').toLowerCase();

    let json = null;
    if (ct.includes('application/json')) {
      json = await res.json().catch(()=>null);
      console.log('[AJAX] json response', json);
    } else {
      const txt = await res.text().catch(()=>null);
      console.log('[AJAX] text response (first200):', txt ? txt.slice(0,200) : null);
    }

    // Immediate DOM handling based on action
    const act = (form.dataset.action || '').toLowerCase();
    const tr = form.closest('tr');
    const orderId = getOrderIdFromTr(tr) || (action && (action.match(/\/orders\/(\d+)/)||[])[1]) || null;

    // If server indicates success (json.status ok) OR response status 200-204, proceed to mutate DOM
    const serverOk = (json && (json.status === 'ok' || json.status === 'success')) || (res.status >= 200 && res.status < 300);

    if (serverOk) {
      if (act === 'cancel') {
        if (tr) {
          tr.style.transition = 'opacity 0.2s'; tr.style.opacity = '0.4';
          setTimeout(()=> tr.remove(), 160);
        } else if (orderId) {
          const maybe = document.querySelector(`tr[data-order-id="${orderId}"]`);
          if (maybe) maybe.remove();
        }
      } else if (act === 'accept') {
        // move tr from pending to inProgress, update badge and actions
        if (tr) {
          // clone and modify
          const newTr = cloneRowAndSetOrderId(tr, orderId);
          // update status badge cell (look for .status.pending)
          const statusEl = newTr.querySelector('.status');
          if (statusEl) {
            statusEl.className = 'status progress';
            statusEl.textContent = 'In Progress';
          }
          // update accepted at cell (assume 3rd column) - try to find by data-label or index
          const acceptedAtCell = newTr.querySelector('td[data-label="Accepted At"]') || newTr.children[2];
          if (acceptedAtCell) acceptedAtCell.textContent = nowTimeHHMM();
          // replace actions: make Done + Cancel forms
          const actionsCell = newTr.querySelector('td[data-label="Actions"]') || newTr.querySelector('td:last-child');
          if (actionsCell) actionsCell.innerHTML = makeActionFormsForOrder(orderId);
          // remove original tr and insert newTr on top of inProgress
          tr.remove();
          insertRowAtTop(inProgressTbody, newTr);
        } else {
          // fallback: if no tr, just sync
          console.warn('[AJAX] accept: no tr found locally, will sync');
        }
      } else if (act === 'done' || act === 'mark-done') {
        // move to doneTbody
        if (tr) {
          const newTr = cloneRowAndSetOrderId(tr, orderId);
          // update status
          const statusEl = newTr.querySelector('.status');
          if (statusEl) { statusEl.className = 'status done'; statusEl.textContent = 'Done'; }
          // update served at column
          const servedAtCell = newTr.querySelector('td[data-label="Served At"]') || newTr.children[2];
          if (servedAtCell) servedAtCell.textContent = nowTimeHHMM();
          // compute simple bill if possible
          const priceText = newTr.querySelector('.muted') ? newTr.querySelector('.muted').textContent : null;
          // replace actions to View Profile + Print (keep existing links if present)
          const actionsCell = newTr.querySelector('td[data-label="Actions"]') || newTr.querySelector('td:last-child');
          if (actionsCell) actionsCell.innerHTML = `<button class="btn" onclick="/* view profile fallback */">View Profile</button> <a class="btn ghost" href="#" onclick="alert('Print static')">Print The Check</a>`;
          tr.remove();
          insertRowAtTop(doneTbody, newTr);
        } else {
          console.warn('[AJAX] done: no tr found locally, will sync');
        }
      } else {
        // other actions: just remove row
        if (tr) tr.remove();
      }

      // after local mutation, force a full sync after tiny delay to reflect canonical server state
      setTimeout(()=> syncOrders().catch(e=>console.warn('sync after action failed', e)), 350);
    } else {
      console.warn('[AJAX] server did not return ok - will sync for safety');
      // do a full sync to reconcile
      setTimeout(()=> syncOrders().catch(e=>console.warn('sync after non-ok failed', e)), 200);
    }

  } catch (err) {
    console.error('[AJAX] request failed', err);
    alert('Operation failed. Check console for details.');
  }
});

/* ======= start polling ======= */
syncOrders();
const SYNC_INTERVAL = 3000; // poll every 3s for faster new-orders
setInterval(syncOrders, SYNC_INTERVAL);

setInterval(() => {
    location.reload();
  }, 60 * 1000);
</script>


</body>
</html>