<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Admin — Manage Courses</title>
  <style>
    :root{ --card-radius:12px; --muted:#6b7280; --accent:#E0AA3E; }
    body{font-family:Inter,Arial;margin:18px;background:#f3f4f6;color:#111}
    .wrap{max-width:1100px;margin:0 auto}
    .card{background:#fff;padding:16px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.06)}
    .btn{background:linear-gradient(90deg,var(--accent));color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;text-decoration: none;}
    .btn.ghost{background:transparent;border:1px solid rgba(0,0,0,0.08);color:#333;text-decoration: none;}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:10px;text-align:left;border-bottom:1px solid rgba(0,0,0,0.04)}
    .muted{color:var(--muted);font-size:13px}
  </style>
</head>
<body>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2>Manage Courses</h2>
      <div>
        <button class="btn" data-bs-toggle="modal" data-bs-target="#createCourseModal">Create Course</button>
        <a href="{{ route('admin.dashboard') }}" class="btn ghost" style="margin-left:8px">Back</a>
      </div>
    </div>

    @if(session('success'))
      <div style="background:#ecfdf5;padding:10px;border-radius:8px;margin-bottom:10px;color:#065f46;">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div style="background:#fef2f2;padding:10px;border-radius:8px;margin-bottom:10px;color:#991b1b;">
        {{ session('error') }}
      </div>
    @endif

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Course Name</th>
            <th>Full Price</th>
            <th>Session Price</th>
            <th>Enrolled Students</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($courses as $course)
            <tr>
              <td>{{ $course->name }}</td>
              <td>{{ number_format($course->full_price, 2) }} EGP</td>
              <td>{{ number_format($course->session_price, 2) }} EGP</td>
              <td>{{ $course->enrollments_count }}</td>
              <td style="text-align:right">
                <a href="{{ route('admin.courses.show', $course->id) }}" class="btn">View Details</a>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" style="text-align:center;color:#777;padding:20px">No courses yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Create Course Modal -->
  <div class="modal fade" id="createCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create New Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.courses.store') }}">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Course Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Full Course Price (EGP)</label>
              <input type="number" step="0.01" name="full_price" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Per Session Price (EGP)</label>
              <input type="number" step="0.01" name="session_price" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn">Create Course</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
