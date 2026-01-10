<div class="orders-list">
@forelse($orders as $order)
  <div class="order-item">
    <div class="order-left">
      <div class="drink-thumb">
        {{ strtoupper(substr($order['name'], 0, 2)) }}
      </div>
      <div>
        <div style="font-weight:700">{{ $order['name'] }}</div>
        <div class="muted" style="font-size:13px">
          Ordered {{ $order['time'] }}
        </div>
      </div>
    </div>
    <div style="text-align:right">
      <div class="status {{ strtolower($order['status']) }}">
        {{ $order['status'] }}
      </div>
      <div class="muted" style="margin-top:6px">
        By: {{ $order['by'] }}
      </div>
    </div>
  </div>
@empty
  <div class="muted">No current orders</div>
@endforelse
</div>
