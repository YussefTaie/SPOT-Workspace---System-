@forelse($receivedOrders as $order)
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
@empty
<tr>
  <td colspan="4" style="text-align:center;color:#888;">
    No received orders
  </td>
</tr>
@endforelse
