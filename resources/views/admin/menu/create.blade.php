<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>New Menu Item</title>
  <style>
    body{font-family:Inter,Arial;background:#f3f4f6;padding:22px}
    .card{max-width:700px;margin:0 auto;background:#fff;padding:18px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.06)}
    .form-row{margin-bottom:12px}
    .form-input{width:100%;padding:10px;border-radius:8px;border:1px solid #e5e7eb}
    .btn{background:linear-gradient(90deg,#7c3aed,#a78bfa);color:#fff;padding:10px 14px;border-radius:8px;border:0}
    .btn.ghost{background:transparent;border:1px solid rgba(0,0,0,0.08);color:#333}
    label{display:block;margin-bottom:6px;font-weight:600}
  </style>
</head>
<body>
  <div class="card">
    <h2>New Item</h2>

    @if($errors->any())
      <div style="background:#fff1f2;padding:10px;border-radius:8px;margin-bottom:10px;color:#9f1239">
        <ul style="margin:0;padding-left:18px">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.menu.store') }}">
      @csrf
      <div class="form-row">
        <label>Name</label>
        <input name="name" class="form-input" value="{{ old('name') }}" required>
      </div>

      <div class="form-row">
        <label>Description</label>
        <textarea name="description" class="form-input" rows="3">{{ old('description') }}</textarea>
      </div>

      <div style="display:flex;gap:12px">
        <div style="flex:1">
          <label>Price</label>
          <input name="price" type="number" step="0.01" class="form-input" value="{{ old('price') }}" required>
        </div>
        <div style="width:200px">
          <label>Category</label>
          <input name="category" class="form-input" value="{{ old('category') }}">
        </div>
      </div>

      <div style="margin-top:12px">
        <label style="display:flex;align-items:center;gap:8px">
        <input type="hidden" name="available" value="0">
        <input type="checkbox" name="available" value="1" {{ (isset($menuItem) && $menuItem->available) ? 'checked' : '' }}> Available
        </label>
      </div>

      <div style="margin-top:14px;display:flex;gap:8px">
        <button class="btn" type="submit">Create</button>
        <a href="{{ route('admin.menu.index') }}" class="btn ghost" style="padding:10px 14px;display:inline-block;text-decoration:none">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>
