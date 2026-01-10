@forelse($inProgressOrders as $order)
<tr data-order-id="{{ $order->id }}">
  <td data-label="Customer">
    {{ optional($order->session->guest)->fullname ?? 'N/A' }}
  </td>

  <td data-label="Order">
    {{ optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id }}
    ×{{ $order->quantity ?? 1 }}
    <br>
    <small class="muted">
      {{ number_format($order->unit_price ?? 0, 2) }} EGP each
    </small>
  </td>

  <td data-label="Accepted At">
    {{ optional($order->accepted_at)->format('H:i') ?? $order->updated_at->format('H:i') }}
  </td>

  <td data-label="Status">
    <span class="status progress">In Progress</span>
  </td>

  <td data-label="Actions" style="text-align:right;">
    <form class="ajax-action"
          data-action="done"
          action="{{ route('orders.markDone', $order->id) }}"
          method="POST"
          style="display:inline;">
      @csrf
      <button class="btn">Done</button>
    </form>

    <form class="ajax-action"
          data-action="cancel"
          action="{{ route('orders.cancel', $order->id) }}"
          method="POST"
          style="display:inline;">
      @csrf
      <button class="btn ghost">Cancel</button>
    </form>
  </td>
</tr>
@empty
<tr>
  <td colspan="5" style="text-align:center;color:#888;">
    No in-progress orders
  </td>
</tr>
@endforelse
