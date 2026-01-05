<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Spot | Drinks Menu</title>
  <style>
    :root{
      --bg:#f3f4f6;
      --card:#ffffff;
      --muted:#6b7280;
      --accent:#E0AA3E;
      --accent-2:#a78bfa;
      --glass: rgba(0,0,0,0.04);
      --glass-2: rgba(0,0,0,0.02);
      --success:#10b981;
      --danger:#ef4444;
      --card-radius:14px;
      --max-width:1100px;
      font-family: Inter, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      color-scheme: light;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background:linear-gradient(180deg,#e9eefc, var(--bg));
      color:#0f1724;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:22px;
      direction: ltr;
    }
    .wrap{max-width:var(--max-width);margin:0 auto;display:grid;grid-template-columns:1fr;gap:18px}
    .topbar{display:flex;gap:12px;align-items:center;justify-content:space-between;background:var(--glass);padding:12px;border-radius:12px}
    .brand{display:flex;align-items:center;gap:12px}
    .logo{width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,var(--accent));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:20px}
    .brand h1{margin:0;font-size:18px}
    .brand p{margin:0;color:var(--muted);font-size:13px}
    .controls{display:flex;gap:10px;align-items:center}
    .search{display:flex;align-items:center;gap:8px;background:var(--card);padding:8px;border-radius:12px;box-shadow:0 6px 18px rgba(15,23,36,0.06)}
    .search input{border:0;outline:0;font-size:14px;background:transparent}
    .filter{background:transparent;border:1px solid rgba(0,0,0,0.06);padding:8px 12px;border-radius:10px;font-size:14px;cursor:pointer}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:10px;border:0;background:linear-gradient(90deg,var(--accent));color:white;cursor:pointer}
    .content{display:grid;grid-template-columns:300px 1fr;gap:18px;margin-top:6px}
    .side{position:sticky;top:22px;align-self:start;display:flex;flex-direction:column;gap:12px}
    .card{background:var(--card);padding:14px;border-radius:var(--card-radius);box-shadow:0 8px 24px rgba(15,23,36,0.06); color: #0f1724;}
    .card h3{margin:0 0 10px 0;font-size:15px}
    .muted{color:var(--muted);font-size:13px}
    .categories{display:flex;flex-direction:column;gap:8px}
    .cat{padding:10px;border-radius:10px;cursor:pointer;border:1px solid rgba(0,0,0,0.04);display:flex;justify-content:space-between;align-items:center}
    .cat.active{background:linear-gradient(90deg, rgba(124,58,237,0.08), rgba(167,139,250,0.06));border-color:rgba(124,58,237,0.18)}
    .cart-list{display:flex;flex-direction:column;gap:8px}
    .cart-item{display:flex;justify-content:space-between;gap:8px;align-items:center;padding:8px;border-radius:10px;background:rgba(0,0,0,0.02)}
    .menu-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
    .drink-card{background:linear-gradient(180deg, rgba(255,255,255,0.9), var(--card));border-radius:12px;padding:12px;display:flex;flex-direction:column;gap:10px;box-shadow:0 10px 30px rgba(2,6,23,0.06);transition:transform .14s ease, box-shadow .14s ease;}
    .thumb{width:72px;height:72px;border-radius:12px;background:linear-gradient(135deg,#fb7185,#f97316);display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:20px}
    .drink-head{display:flex;justify-content:space-between;align-items:start;gap:8px}
    .price{font-weight:800}
    .tags{display:flex;gap:8px;flex-wrap:wrap}
    .tag{padding:6px 8px;border-radius:999px;font-size:12px;background:var(--glass-2);color:var(--muted)}
    .drink-desc{font-size:13px;color:var(--muted);min-height:38px}
    .card-actions{display:flex;justify-content:space-between;align-items:center;gap:8px}
    .btn.small{padding:8px 10px;font-size:14px;border-radius:10px}
    .quantity{display:flex;gap:6px;align-items:center;font-weight:700}
    .qty-btn{width:30px;height:30px;border-radius:8px;border:1px solid rgba(0,0,0,0.06);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;background:white}
    .empty{padding:28px;text-align:center;color:var(--muted);border-radius:12px;background:linear-gradient(180deg, rgba(0,0,0,0.02), rgba(0,0,0,0.01));}
    .cart-controls{display:flex;gap:6px;align-items:center}
    .cart-remove{background:transparent;border:0;color:var(--danger);cursor:pointer;font-weight:700}
    @media(max-width:1000px){ .menu-grid{grid-template-columns:repeat(2,1fr)} .content{grid-template-columns:1fr} .side{position:static} }
    @media(max-width:560px){ .menu-grid{grid-template-columns:1fr} .logo{width:48px;height:48px;font-size:18px} .thumb{width:56px;height:56px} }
  </style>
</head>
<body>
  {{-- important hidden guest id (will be set by BaristaController::menu) --}}
  @if(isset($guest) && $guest->id)
    <input type="hidden" id="guestId" value="{{ $guest->id }}">
  @else
    <input type="hidden" id="guestId" value="">
    <script>console.warn('No $guest passed to view — guestId is empty');</script>
  @endif

  <div class="wrap">
    <div class="topbar card">
      <div class="brand">
        <div>
          <h1>Spot Drinks Menu</h1>
        </div>
      </div>
      <div class="controls">
        <div class="search" title="Search">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" style="opacity:0.7"><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="2"/></svg>
          <input id="searchInput" placeholder="Search for a drink..." />
        </div>
        <select id="categorySelect" class="filter" title="Filter by category">
          <option value="all">All Categories</option>
          @php
            $cats = $menuItems->pluck('category')->filter()->unique()->values();
          @endphp
          @foreach($cats as $c)
            <option value="{{ $c }}">{{ ucfirst($c) }}</option>
          @endforeach
        </select>
        <button class="btn" id="viewCartBtn">Cart <span id="cartCount">0</span></button>
      </div>
    </div>

    <div class="content">

      <aside class="side">
        
        <!-- <div class="card">
          <h3>Categories</h3>
          <div class="categories" id="categories">
            <div class="cat active" data-cat="all">All <span class="muted">{{ $menuItems->count() }}</span></div>
            @foreach($cats as $c)
              <div class="cat" data-cat="{{ $c }}">{{ ucfirst($c) }} <span class="muted">{{ $menuItems->where('category',$c)->count() }}</span></div>
            @endforeach
          </div>
        </div> -->

        <div class="card">
          <h3>Cart</h3>
          <div id="cartArea">
            <div class="empty" id="cartEmpty">No items yet — start adding!</div>
            <div class="cart-list" id="cartList" style="display:none"></div>
            <div style="margin-top:12px; display:flex;justify-content:space-between;align-items:center">
              <div class="muted">Total:</div>
              <div style="font-weight:800" id="cartTotal">0 EGP</div>
            </div>
            <div style="margin-top:10px;text-align:left">
              <button class="btn" id="checkoutBtn" style="width:100%">Checkout</button>
            </div>
            <label style="display:block;margin-top:8px;">
            <input type="checkbox" id="takeawayCheckbox" /> Takeaway
            </label>

          </div>
        </div>

        <div class="card">
          <h3>Guest Information</h3>
          <p class="muted">Name: {{ $guest->fullname ?? 'Guest' }}</p>
          <p class="muted">Phone: {{ $guest->phone ?? '-' }}</p>
        </div>
      </aside>

      <main>
        <div class="card">
          <h3>Drinks Menu</h3>
          <p class="muted" style="margin-top:6px">Click Add to include the drink in your cart.</p>

          <div style="margin-top:12px" id="menuGrid" class="menu-grid">

            @forelse($menuItems as $item)
              <div class="drink-card" data-cat="{{ $item->category ?? 'none' }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}" data-id="{{ $item->id }}">
                <div class="drink-head">
                  <div style="display:flex;gap:12px;align-items:center">
                    <div class="thumb">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($item->name,0,2)) }}</div>
                    <div>
                      <div class="title">{{ $item->name }}</div>
                      <div class="muted" style="font-size:13px">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</div>
                    </div>
                  </div>
                  <div><div class="price">{{ number_format($item->price, 0) }} EGP</div></div>
                </div>

                <div class="drink-desc">{{ $item->description }}</div>
                <div class="tags">
                  <span class="tag">{{ $item->category ?? 'General' }}</span>
                  @if(!$item->available)
                    <span class="tag">Unavailable</span>
                  @endif
                </div>

                <div class="card-actions">
                  <div class="quantity">
                    <button class="qty-btn" data-op="-">-</button>
                    <span class="qty">1</span>
                    <button class="qty-btn" data-op="+">+</button>
                  </div>

                  @if($item->available)
                    <button class="btn small addBtn" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}">Add</button>
                  @else
                    <button class="btn small" disabled>Unavailable</button>
                  @endif
                </div>
              </div>
            @empty
              <div class="empty">No menu items yet.</div>
            @endforelse

          </div>
        </div>
      </main>

    </div>
  </div>

  <script>
    // Client-side: search, filter, cart (uses data attributes)
    const menuGrid = document.getElementById('menuGrid');
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const cats = document.querySelectorAll('.cat');
    const cartListEl = document.getElementById('cartList');
    const cartEmpty = document.getElementById('cartEmpty');
    const cartTotalEl = document.getElementById('cartTotal');
    const cartCountEl = document.getElementById('cartCount');
    let cart = [];

    function formatPrice(n){ return n + ' EGP'; }
    function getPriceFromCard(card){
      const p = card.dataset.price || card.querySelector('.price').textContent.trim().split(' ')[0];
      return parseFloat(p) || 0;
    }

    // quantity buttons inside menu cards
    function initQtyButtons(scope=document){
      scope.querySelectorAll('.qty-btn').forEach(btn=>{
        btn.addEventListener('click', ()=> {
          const op = btn.dataset.op;
          const card = btn.closest('.drink-card');
          const qtyEl = card.querySelector('.qty');
          let qty = parseInt(qtyEl.textContent);
          qty = op === '+' ? qty + 1 : Math.max(1, qty - 1);
          qtyEl.textContent = qty;
        });
      });
    }

    // add to cart
    function initAddButtons(scope=document){
      scope.querySelectorAll('.addBtn').forEach(btn=>{
        btn.addEventListener('click', ()=> {
          const card = btn.closest('.drink-card');
          const name = card.dataset.name || card.querySelector('.title').textContent.trim();
          const price = parseFloat(card.dataset.price) || getPriceFromCard(card);
          const qty = parseInt(card.querySelector('.qty').textContent);
          const id = card.dataset.id || name.replace(/\s+/g,'_').toLowerCase();
          const existing = cart.find(x=>x.id===id);
          if(existing){ existing.qty += qty; }
          else cart.push({ id, name, price, qty });
          renderCart();
        });
      });
    }

    // remove item from cart
    function removeFromCart(id){
      cart = cart.filter(i=>i.id !== id);
      renderCart();
    }

    // change qty for an item in cart (set absolute) — if qty <= 0 remove
    function setCartItemQty(id, newQty){
      const it = cart.find(x=>x.id===id);
      if(!it) return;
      it.qty = Math.max(0, parseInt(newQty) || 0);
      if(it.qty === 0) removeFromCart(id);
      else renderCart();
    }

    function renderCart(){
      cartListEl.innerHTML = '';
      if(cart.length === 0){
        cartEmpty.style.display = 'block';
        cartListEl.style.display = 'none';
      } else {
        cartEmpty.style.display = 'none';
        cartListEl.style.display = 'flex';
        cart.forEach(item=>{
          const row = document.createElement('div');
          row.className = 'cart-item';
          // Build inner HTML with qty controls + remove button
          row.innerHTML = `
            <div style="display:flex;flex-direction:column;">
              <div style="font-weight:700">${item.name}</div>
              <div class="muted" style="font-size:12px">${formatPrice(item.price)} each</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
              <div class="cart-controls">
                <button class="qty-btn cart-decr" data-id="${item.id}">-</button>
                <input class="cart-qty-input" data-id="${item.id}" value="${item.qty}" style="width:40px;text-align:center;border-radius:6px;border:1px solid rgba(0,0,0,0.06);padding:6px" />
                <button class="qty-btn cart-incr" data-id="${item.id}">+</button>
                <button class="cart-remove" data-id="${item.id}">Remove</button>
              </div>
              <div style="font-weight:800">${formatPrice(item.price * item.qty)}</div>
            </div>
          `;
          cartListEl.appendChild(row);
        });

        // attach listeners for newly created controls
        cartListEl.querySelectorAll('.cart-remove').forEach(b=>{
          b.addEventListener('click', ()=> removeFromCart(b.dataset.id));
        });
        cartListEl.querySelectorAll('.cart-decr').forEach(b=>{
          b.addEventListener('click', ()=>{
            const id = b.dataset.id;
            const it = cart.find(x=>x.id===id);
            if(!it) return;
            setCartItemQty(id, it.qty - 1);
          });
        });
        cartListEl.querySelectorAll('.cart-incr').forEach(b=>{
          b.addEventListener('click', ()=>{
            const id = b.dataset.id;
            const it = cart.find(x=>x.id===id);
            if(!it) return;
            setCartItemQty(id, it.qty + 1);
          });
        });
        cartListEl.querySelectorAll('.cart-qty-input').forEach(inp=>{
          inp.addEventListener('change', ()=>{
            const id = inp.dataset.id;
            let v = parseInt(inp.value) || 0;
            if(v < 0) v = 0;
            setCartItemQty(id, v);
          });
          // allow arrow/keypress changes (optional)
          inp.addEventListener('keydown', (e)=>{
            if(e.key === 'Enter') inp.dispatchEvent(new Event('change'));
          });
        });
      }
      const total = cart.reduce((s,i)=>s + i.price * i.qty, 0);
      cartTotalEl.textContent = formatPrice(total);
      cartCountEl.textContent = cart.reduce((s,i)=>s + i.qty, 0);
    }

    // search + filter
    function applyFilters(){
      const q = (searchInput.value || '').trim().toLowerCase();
      const cat = categorySelect.value;
      document.querySelectorAll('.drink-card').forEach(card=>{
        const name = (card.dataset.name || '').toLowerCase();
        const matchesQ = !q || name.includes(q);
        const matchesCat = (cat === 'all') || (card.dataset.cat === cat);
        card.style.display = (matchesQ && matchesCat) ? 'flex' : 'none';
      });
    }
    searchInput.addEventListener('input', applyFilters);
    categorySelect.addEventListener('change', applyFilters);

    // categories clickable on left
    cats.forEach(el=>{
      el.addEventListener('click', ()=>{
        cats.forEach(c=>c.classList.remove('active'));
        el.classList.add('active');
        const code = el.dataset.cat;
        categorySelect.value = code === 'all' ? 'all' : code;
        applyFilters();
      });
    });

    // checkout (improved debug)
    document.getElementById('checkoutBtn').addEventListener('click', async () => {
      if (cart.length === 0) {
        alert('Your cart is empty');
        return;
      }

      const guestEl = document.getElementById('guestId');
      const guestId = guestEl ? guestEl.value : null;
      if (!guestId) {
        alert('Error: guestId missing. Make sure the page includes <input id="guestId"> with a value.');
        console.error('guestId missing — #guestId element:', guestEl);
        return;
      }

      try {
        const res = await fetch("{{ route('orders.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({
            cart,
            guest_id: guestId,
            takeaway: document.getElementById('takeawayCheckbox')?.checked || false
          })
        });

        const text = await res.text();
        let data = null;
        try { data = JSON.parse(text); } catch(e) { /* not JSON */ }

        console.log('Order request -> HTTP', res.status);
        console.log('Order response raw:', text);
        if (data) console.log('Order response json:', data);

        if (!res.ok) {
          if (res.status === 419) {
            alert('Session expired (419). Try logging in again.');
          } else if (res.status === 403) {
            alert('Forbidden (403). You may need to login as staff/guest.');
          } else {
            alert(`Order failed (HTTP ${res.status}). Check console for details.`);
          }
          return;
        }

        if (data && data.status === 'success') {
          alert('✅ ' + data.message);
          cart = [];
          renderCart();
          return;
        }

        if (data && data.message) {
          alert('⚠️ ' + data.message);
          return;
        }

        alert('Something went wrong while placing your order — check console/network for details.');
      } catch (err) {
        console.error('Fetch error placing order:', err);
        alert('Network error while placing order — check console.');
      }
    });

    // view cart button focus
    document.getElementById('viewCartBtn').addEventListener('click', ()=>{
      document.getElementById('cartArea').scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    // init
    initQtyButtons();
    initAddButtons();
    renderCart();

  </script>
</body>
</html>
