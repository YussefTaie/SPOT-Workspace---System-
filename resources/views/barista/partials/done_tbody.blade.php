@forelse($doneOrders as $order)
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

  <td data-label="Done At">
    {{ optional($order->updated_at)->format('H:i') }}
  </td>

  <td data-label="Status">
    <span class="status done">Done</span>
  </td>

  <td data-label="Actions" style="text-align:right;">
    <form class="ajax-action"
          data-action="received"
          action="{{ route('orders.received', $order->id) }}"
          method="POST"
          style="display:inline;">
      @csrf
      <button class="btn">Received</button>
    </form>
  </td>
</tr>
@empty
<tr>
  <td colspan="5" style="text-align:center;color:#888;">
    No done orders
  </td>
</tr>
@endforelse
