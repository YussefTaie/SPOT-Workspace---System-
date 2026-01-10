@forelse($pendingOrders as $order)

<tr data-order-id="{{ $order->id }}">
  <td>{{ optional($order->session->guest)->fullname ?? 'N/A' }}</td>

  <td>
    {{ optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id }}
    ×{{ $order->quantity ?? 1 }}
  </td>

  <td>
    {{ optional($order->served_at)->format('H:i') ?? $order->updated_at->format('H:i') }}
  </td>

  <td>
    <span class="status pending">Pending</span>
  </td>

  <td style="text-align:right;">
    <form class="ajax-action"
          data-action="received"
          action="{{ route('orders.received', $order->id) }}"
          method="POST">
      @csrf
      <button class="btn">Received</button>
    </form>
  </td>
</tr>
@empty
<tr>
  <td colspan="5" style="text-align:center;color:#888;">
    No finished orders
  </td>
</tr>
@endforelse
