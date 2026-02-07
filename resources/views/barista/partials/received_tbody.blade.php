@forelse(
  $receivedOrders->groupBy(fn($o) => $o->updated_at->toDateString())
  as $date => $orders
)

  @php
    $dayTotal = $orders->sum(function ($o) {
      return ($o->unit_price ?? 0) * ($o->quantity ?? 1);
    });
  @endphp

  {{-- ===== Day Divider ===== --}}
  <tr style="background:#f3f4f6;">
    <td colspan="4" style="text-align:center;font-weight:600;">
      📅 {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
      — 💰 Total: {{ number_format($dayTotal, 2) }} EGP
    </td>
  </tr>

  @foreach($orders as $order)
    <tr data-order-id="{{ $order->id }}">
      <td data-label="Customer">
        {{ optional($order->session->guest)->fullname ?? 'N/A' }}
      </td>

      <td data-label="Order">
        {{ optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id }}
        ×{{ $order->quantity ?? 1 }}
        <br>
        <small class="muted">
          {{ number_format($order->unit_price ?? 0, 2) }} EGP
        </small>
      </td>

      <td data-label="Received At">
        {{ $order->updated_at->format('H:i') }}
      </td>

      <td data-label="Status">
        <span class="status done">Received</span>
      </td>
    </tr>
  @endforeach

@empty
<tr>
  <td colspan="4" style="text-align:center;color:#888;">
    No received orders
  </td>
</tr>
@endforelse
