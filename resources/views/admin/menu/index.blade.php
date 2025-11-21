<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin — Manage Menu</title>
  <style>
    /* استخدمت نفس ستايل المشروع باختصار */
    :root{ --card-radius:12px; --muted:#6b7280; --accent:#7c3aed; }
    body{font-family:Inter,Arial;margin:18px;background:#f3f4f6;color:#111}
    .wrap{max-width:1100px;margin:0 auto}
    .card{background:#fff;padding:16px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.06)}
    .btn{background:linear-gradient(90deg,var(--accent),#a78bfa);color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;text-decoration: none;}
    .btn.ghost{background:transparent;border:1px solid rgba(0,0,0,0.08);color:#333;text-decoration: none;}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:10px;text-align:left;border-bottom:1px solid rgba(0,0,0,0.04)}
    .muted{color:var(--muted);font-size:13px}
    form.inline{display:inline}
  </style>
</head>
<body>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2>Manage Menu</h2>
      <div>
        <a href="{{ route('admin.menu.create') }}" class="btn">New Item</a>
        <a href="{{ route('admin.dashboard') }}" class="btn ghost" style="margin-left:8px">Back</a>
      </div>
    </div>

    @if(session('success'))
      <div style="background:#ecfdf5;padding:10px;border-radius:8px;margin-bottom:10px;color:#065f46;">
        {{ session('success') }}
      </div>
    @endif

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Available</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $it)
            <tr>
              <td>{{ $it->name }}</td>
              <td>{{ $it->category ?? '-' }}</td>
              <td>{{ number_format($it->price,2) }} EGP</td>
              <td>{{ $it->available ? 'Yes' : 'No' }}</td>
              <td style="text-align:right">
                <a href="{{ route('admin.menu.edit', $it->id) }}" class="btn">Edit</a>

                <form onclick="return confirm('Delete this item?')" class="inline" method="POST" action="{{ route('admin.menu.destroy', $it->id) }}">
                  @csrf
                  @method('DELETE')
                  <!-- <button class="btn ghost" type="submit">Delete</button> -->
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;color:#777;padding:20px">No items yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
