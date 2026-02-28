<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Admin — {{ $course->name }}</title>
  <style>
    :root{ --card-radius:12px; --muted:#6b7280; --accent:#E0AA3E; }
    body{font-family:Inter,Arial;margin:18px;background:#f3f4f6;color:#111}
    .wrap{max-width:1200px;margin:0 auto}
    .card{background:#fff;padding:16px;border-radius:var(--card-radius);box-shadow:0 6px 20px rgba(0,0,0,0.06);margin-bottom:16px}
    .btn{background:linear-gradient(90deg,var(--accent));color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;text-decoration:none;font-size:14px;}
    .btn.ghost{background:transparent;border:1px solid rgba(0,0,0,0.08);color:#333;}
    .btn.danger{background:#ef4444;}
    table{width:100%;border-collapse:collapse;margin-top:12px;font-size:14px}
    th,td{padding:10px;text-align:left;border-bottom:1px solid rgba(0,0,0,0.04)}
    .muted{color:var(--muted);font-size:13px}
    .badge{padding:4px 8px;border-radius:999px;font-size:12px;font-weight:500}
    .badge.paid{background:#ecfdf5;color:#065f46}
    .badge.partial{background:#fef3c7;color:#92400e}
    .badge.unpaid{background:#fee2e2;color:#991b1b}
    .info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
    .info-item label{display:block;color:var(--muted);font-size:13px;margin-bottom:4px}
    .info-item span{font-size:16px;font-weight:600}
  </style>
</head>
<body>
  <div class="wrap">
    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2>{{ $course->name }}</h2>
      <div>
        <button class="btn" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add Student</button>
        <button class="btn ghost" data-bs-toggle="modal" data-bs-target="#editCourseModal" style="margin-left:8px">Edit Course</button>
        <button class="btn danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal" style="margin-left:8px">Delete Course</button>
        <a href="{{ route('admin.courses.index') }}" class="btn ghost" style="margin-left:8px">Back to Courses</a>
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

    <!-- Course Info -->
    <div class="card">
      <h3 style="font-size:16px;margin-bottom:12px">Course Information</h3>
      <div class="info-grid">
        <div class="info-item">
          <label>Full Course Price</label>
          <span>{{ number_format($course->full_price, 2) }} EGP</span>
        </div>
        <div class="info-item">
          <label>Per Session Price</label>
          <span>{{ number_format($course->session_price, 2) }} EGP</span>
        </div>
        <div class="info-item">
          <label>Total Enrolled</label>
          <span>{{ $course->enrollments()->count() }} Students</span>
        </div>
      </div>
    </div>

    <!-- Enrolled Students -->
    <div class="card">
      <h3 style="font-size:16px;margin-bottom:12px">Enrolled Students</h3>
      
      <!-- Search and Filter -->
      <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap">
        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.courses.show', $course->id) }}" style="flex:1;min-width:250px">
          @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
          @endif
          <div style="position:relative">
            <input 
              type="text" 
              name="search" 
              class="form-control" 
              placeholder="Search by name or phone..." 
              value="{{ request('search') }}"
              style="padding-right:80px"
            >
            @if(request('search'))
              <a href="{{ route('admin.courses.show', $course->id) }}{{ request('status') ? '?status=' . request('status') : '' }}" 
                 class="btn ghost" 
                 style="position:absolute;right:50px;top:50%;transform:translateY(-50%);padding:4px 8px;font-size:12px">
                Clear
              </a>
            @endif
            <button type="submit" class="btn" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);padding:4px 12px;font-size:12px">
              Search
            </button>
          </div>
        </form>

        <!-- Filter Buttons -->
        <div style="display:flex;gap:8px;align-items:center">
          <span style="color:var(--muted);font-size:13px;margin-right:4px">Filter:</span>
          <a href="{{ route('admin.courses.show', $course->id) }}{{ request('search') ? '?search=' . request('search') : '' }}" 
             class="btn {{ !request('status') ? '' : 'ghost' }}" 
             style="padding:6px 12px;font-size:13px">
            All
          </a>
          <a href="{{ route('admin.courses.show', $course->id) }}?status=paid{{ request('search') ? '&search=' . request('search') : '' }}" 
             class="btn {{ request('status') === 'paid' ? '' : 'ghost' }}" 
             style="padding:6px 12px;font-size:13px">
            Paid
          </a>
          <a href="{{ route('admin.courses.show', $course->id) }}?status=partial{{ request('search') ? '&search=' . request('search') : '' }}" 
             class="btn {{ request('status') === 'partial' ? '' : 'ghost' }}" 
             style="padding:6px 12px;font-size:13px">
            Partial
          </a>
          <a href="{{ route('admin.courses.show', $course->id) }}?status=unpaid{{ request('search') ? '&search=' . request('search') : '' }}" 
             class="btn {{ request('status') === 'unpaid' ? '' : 'ghost' }}" 
             style="padding:6px 12px;font-size:13px">
            Unpaid
          </a>
        </div>
      </div>

      @if(request('status') || request('search'))
        <div style="background:#f9fafb;padding:8px 12px;border-radius:8px;margin-bottom:12px;font-size:13px;color:#374151">
          <strong>Filters active:</strong>
          @if(request('status'))
            <span class="badge {{ request('status') }}" style="margin-left:4px">{{ ucfirst(request('status')) }}</span>
          @endif
          @if(request('search'))
            <span style="margin-left:8px">Search: "{{ request('search') }}"</span>
          @endif
          <span style="margin-left:8px">—</span>
          <span style="margin-left:4px">{{ $enrollments->count() }} result(s)</span>
        </div>
      @endif

      <table>
        <thead>
          <tr>
            <th>Student Name</th>
            <th>Enrollment Type</th>
            <th>Total Amount</th>
            <th>Paid Amount</th>
            <th>Remaining</th>
            <th>Status</th>
            <th style="text-align:right">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($enrollments as $enrollment)
            <tr>
              <td>{{ $enrollment->guest->fullname }}</td>
              <td>{{ $enrollment->enrollment_type === 'full' ? 'Full Course' : 'Per Session' }}</td>
              <td>{{ number_format($enrollment->total_amount, 2) }} EGP</td>
              <td>{{ number_format($enrollment->paid_amount, 2) }} EGP</td>
              <td>{{ number_format($enrollment->remaining_amount, 2) }} EGP</td>
              <td>
                <span class="badge {{ $enrollment->payment_status }}">
                  {{ ucfirst($enrollment->payment_status) }}
                </span>
              </td>
              <td style="text-align:right">
                <button class="btn ghost" onclick="openEditPaymentModal({{ $enrollment->id }}, {{ $enrollment->total_amount }}, {{ $enrollment->paid_amount }}, {{ $enrollment->remaining_amount }}, '{{ $enrollment->payment_status }}', '{{ $enrollment->guest->fullname }}')">
                  Edit Payment
                </button>
                <form method="POST" action="{{ route('admin.courses.enrollments.destroy', $enrollment->id) }}" style="display:inline" onsubmit="return confirm('Remove this student from the course?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn danger">Remove</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" style="text-align:center;color:#777;padding:20px">No enrollments yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Student Modal -->
  <div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Student to Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.courses.enrollments.store', $course->id) }}">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Search Guest</label>
              <input type="text" id="guestSearch" class="form-control" placeholder="Type name or phone..." autocomplete="off">
              <div id="guestResults" style="position:relative"></div>
              <input type="hidden" name="guest_id" id="selectedGuestId" required>
              <div id="selectedGuest" style="margin-top:8px;color:#065f46"></div>
            </div>

            <div class="mb-3">
              <label class="form-label">Enrollment Type</label>
              <div>
                <input type="radio" name="enrollment_type" value="full" id="enrollFull" required onchange="updateTotalAmount()">
                <label for="enrollFull" style="margin-left:4px;margin-right:16px">Full Course ({{ number_format($course->full_price, 2) }} EGP)</label>
                
                <input type="radio" name="enrollment_type" value="per_session" id="enrollSession" required onchange="updateTotalAmount()">
                <label for="enrollSession" style="margin-left:4px">Per Session ({{ number_format($course->session_price, 2) }} EGP)</label>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Paid Amount (EGP)</label>
              <input type="number" step="0.01" name="paid_amount" id="paidAmount" class="form-control" value="0" required oninput="calculateRemaining()">
            </div>

            <div id="calculationPreview" style="background:#f9fafb;padding:12px;border-radius:8px;display:none">
              <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                <span>Total Amount:</span>
                <strong id="previewTotal">0.00 EGP</strong>
              </div>
              <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                <span>Paid Amount:</span>
                <strong id="previewPaid">0.00 EGP</strong>
              </div>
              <div style="display:flex;justify-content:space-between;margin-bottom:8px">
                <span>Remaining:</span>
                <strong id="previewRemaining">0.00 EGP</strong>
              </div>
              <div style="display:flex;justify-content:space-between">
                <span>Status:</span>
                <span class="badge" id="previewStatus">unpaid</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn">Add Student</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Payment Modal -->
  <div class="modal fade" id="editPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" id="editPaymentForm">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <p><strong>Student:</strong> <span id="editStudentName"></span></p>
            <p><strong>Total Amount:</strong> <span id="editTotalAmount"></span> EGP</p>
            
            <div class="mb-3">
              <label class="form-label">Paid Amount (EGP)</label>
              <input type="number" step="0.01" name="paid_amount" id="editPaidAmount" class="form-control" required oninput="calculateEditRemaining()">
            </div>

            <div style="background:#f9fafb;padding:12px;border-radius:8px">
              <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                <span>Remaining:</span>
                <strong id="editPreviewRemaining">0.00 EGP</strong>
              </div>
              <div style="display:flex;justify-content:space-between">
                <span>Status:</span>
                <span class="badge" id="editPreviewStatus">unpaid</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn">Update Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Course Modal -->
  <div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.courses.update', $course->id) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Course Name</label>
              <input type="text" name="name" class="form-control" value="{{ $course->name }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Full Course Price (EGP)</label>
              <input type="number" step="0.01" name="full_price" class="form-control" value="{{ $course->full_price }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Per Session Price (EGP)</label>
              <input type="number" step="0.01" name="session_price" class="form-control" value="{{ $course->session_price }}" required>
            </div>
            <div style="background:#fef3c7;padding:10px;border-radius:8px;font-size:13px;color:#92400e">
              <strong>Note:</strong> Updating prices will NOT affect existing enrollments. Only new enrollments will use the updated prices.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn">Update Course</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Course Modal -->
  <div class="modal fade" id="deleteCourseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Delete Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form method="POST" action="{{ route('admin.courses.destroy', $course->id) }}">
          @csrf
          @method('DELETE')
          <div class="modal-body">
            <div style="background:#fef2f2;padding:12px;border-radius:8px;margin-bottom:16px">
              <p style="color:#991b1b;margin:0;font-weight:600">⚠️ Warning: This action cannot be undone!</p>
            </div>
            <p style="margin-bottom:12px">Are you sure you want to delete this course?</p>
            <div style="background:#f9fafb;padding:10px;border-radius:8px">
              <p style="margin:0;font-weight:600">{{ $course->name }}</p>
              <p style="margin:4px 0 0 0;color:#6b7280;font-size:13px">
                This will also remove <strong>{{ $course->enrollments()->count() }} enrollment(s)</strong>
              </p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn danger">Delete Course</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const fullPrice = {{ $course->full_price }};
    const sessionPrice = {{ $course->session_price }};

    // Guest Search
    let searchTimeout;
    document.getElementById('guestSearch').addEventListener('input', function(e) {
      clearTimeout(searchTimeout);
      const query = e.target.value;
      
      if (query.length < 3) {
        document.getElementById('guestResults').innerHTML = '';
        return;
      }

      searchTimeout = setTimeout(() => {
        fetch(`/admin/guests/search?q=${encodeURIComponent(query)}`)
          .then(res => res.json())
          .then(guests => {
            let html = '<div style="border:1px solid #ddd;border-radius:8px;margin-top:8px;max-height:200px;overflow-y:auto">';
            guests.forEach(g => {
              html += `<div style="padding:8px;cursor:pointer;border-bottom:1px solid #eee" onclick="selectGuest(${g.id}, '${g.fullname}', '${g.phone}')">${g.fullname} - ${g.phone}</div>`;
            });
            html += '</div>';
            document.getElementById('guestResults').innerHTML = html;
          });
      }, 300);
    });

    function selectGuest(id, name, phone) {
      document.getElementById('selectedGuestId').value = id;
      document.getElementById('guestSearch').value = '';
      document.getElementById('guestResults').innerHTML = '';
      document.getElementById('selectedGuest').innerHTML = `✓ Selected: ${name} (${phone})`;
    }

    function updateTotalAmount() {
      const type = document.querySelector('input[name="enrollment_type"]:checked')?.value;
      if (!type) return;

      const total = type === 'full' ? fullPrice : sessionPrice;
      document.getElementById('previewTotal').textContent = total.toFixed(2) + ' EGP';
      document.getElementById('calculationPreview').style.display = 'block';
      calculateRemaining();
    }

    function calculateRemaining() {
      const type = document.querySelector('input[name="enrollment_type"]:checked')?.value;
      if (!type) return;

      const total = type === 'full' ? fullPrice : sessionPrice;
      const paid = parseFloat(document.getElementById('paidAmount').value) || 0;
      const remaining = total - paid;

      document.getElementById('previewPaid').textContent = paid.toFixed(2) + ' EGP';
      document.getElementById('previewRemaining').textContent = remaining.toFixed(2) + ' EGP';

      let status = 'unpaid';
      let statusClass = 'unpaid';
      if (remaining === 0) {
        status = 'paid';
        statusClass = 'paid';
      } else if (paid > 0 && remaining > 0) {
        status = 'partial';
        statusClass = 'partial';
      }

      const statusBadge = document.getElementById('previewStatus');
      statusBadge.textContent = status;
      statusBadge.className = 'badge ' + statusClass;
    }

    // Edit Payment Modal
    let editEnrollmentId;
    let editTotalAmount;

    function openEditPaymentModal(id, total, paid, remaining, status, studentName) {
      editEnrollmentId = id;
      editTotalAmount = total;

      document.getElementById('editStudentName').textContent = studentName;
      document.getElementById('editTotalAmount').textContent = total.toFixed(2);
      document.getElementById('editPaidAmount').value = paid.toFixed(2);
      document.getElementById('editPaymentForm').action = `/admin/enrollments/${id}`;

      calculateEditRemaining();

      new bootstrap.Modal(document.getElementById('editPaymentModal')).show();
    }

    function calculateEditRemaining() {
      const paid = parseFloat(document.getElementById('editPaidAmount').value) || 0;
      const remaining = editTotalAmount - paid;

      document.getElementById('editPreviewRemaining').textContent = remaining.toFixed(2) + ' EGP';

      let status = 'unpaid';
      let statusClass = 'unpaid';
      if (remaining === 0) {
        status = 'paid';
        statusClass = 'paid';
      } else if (paid > 0 && remaining > 0) {
        status = 'partial';
        statusClass = 'partial';
      }

      const statusBadge = document.getElementById('editPreviewStatus');
      statusBadge.textContent = status;
      statusBadge.className = 'badge ' + statusClass;
    }
  </script>
</body>
</html>
