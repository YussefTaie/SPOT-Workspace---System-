<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Check - {{ $session->guest->fullname ?? 'Guest' }}</title>
  <style>
  /* إعدادات الطباعة لحرارة 80mm */
  @media print {
    @page {
      size: 80mm auto;
      margin: 0;
    }

    body {
      width: 80mm;
      margin: 0;
      padding: 0;
    }

    .container {
      width: 72mm; /* سيب هامش بسيط */
      margin: 0 auto;
    }

    .print-btn {
      display: none; /* اخفي زرار الطباعة */
    }
  }

  /* ستايل العرض العادي */
  body {
    font-family: Arial, Helvetica, sans-serif;
    color: #111;
  }

  .container {
    max-width: 420px;
    margin: auto;
    padding: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }

  th, td {
    padding: 4px 0;
    border-bottom: 1px dashed #ccc;
  }

  .right {
    text-align: right;
  }

  .label {
    font-weight: bold;
  }

  h2, h3 {
    text-align: center;
    margin: 6px 0;
  }

  .total {
    font-size: 14px;
    font-weight: bold;
  }

  .muted {
    font-size: 11px;
    color: #666;
  }

  .print-btn {
    margin-top: 10px;
    width: 100%;
    padding: 8px;
    border-radius: 6px;
    background: #000;
    color: #fff;
    border: none;
    cursor: pointer;
  }
</style>

  <style>
    /* بسيط علشان الطباعه تكون كويسة — اضف ستايل الموقع لو حابب */
    body{font-family:Arial, Helvetica, sans-serif;color:#111;}
    .info{margin-bottom:6px}
    .label{font-weight:700;margin-right:6px}
    table{width:100%;border-collapse:collapse;margin-top:12px}
    th,td{padding:8px;border-bottom:1px solid #eee;text-align:left}
    tfoot td{font-weight:700}
    .right{text-align:right}
    .total{margin-top:14px;font-size:18px;font-weight:800;text-align:right}
    .muted{color:#6b7280;font-size:13px}
    .print-btn{margin-top:12px;padding:8px 12px;border-radius:8px;background:#7c3aed;color:#fff;border:0;cursor:pointer}
  </style>
</head>
<body>
  <div class="container">
    <h2>Client Receipt</h2>

    <div class="info"><span class="label">Name:</span> {{ $session->guest->fullname }}</div>
    <div class="info"><span class="label">Check-in Time:</span> {{ \Carbon\Carbon::parse($session->check_in)->format('d/m/Y H:i') }}</div>
    <div class="info"><span class="label">Check-out Time:</span> {{ $session->check_out ? \Carbon\Carbon::parse($session->check_out)->format('d/m/Y H:i') : \Carbon\Carbon::now()->format('d/m/Y H:i') }}</div>
    <div class="info"><span class="label">Duration:</span> {{ $duration->h }}h {{ $duration->i }}m</div>

    <h3 style="margin-top:16px">Session Breakdown</h3>

@if(!empty($subGuestsBreakdown))
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th class="right">Time</th>
      <th class="right">Fee</th>
    </tr>
  </thead>
  <tbody>
    @foreach($subGuestsBreakdown as $sg)
      <tr>
        <td>{{ $sg['name'] }}</td>
        <td class="right">{{ $sg['duration'] }}</td>
        <td class="right">{{ $sg['price'] }} EGP</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endif


    <h3 style="margin-top:16px">Drinks</h3>

{{-- احسب إجمالي المشروبات اللي خلصت فعلاً (status = Done) --}}
@php
  $drinksTotal = $session->orders
      ->where('status', 'Done')
      ->sum(function($o) {
          return $o->total_price ?? ($o->unit_price * ($o->quantity ?? 1));
      });
@endphp

@if(!empty($drinksDetails) && count($drinksDetails) > 0)
  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th class="right">Price</th>
        <th class="right">Qty</th>
        <th class="right">Subtotal</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($drinksDetails as $d)
        <tr>
          <td>{{ $d['name'] }}</td>
          <td class="right">{{ number_format($d['price'], 2) }} EGP</td>
          <td class="right">{{ $d['qty'] }}</td>
          <td class="right">{{ number_format($d['subtotal'], 2) }} EGP</td>
          <td>{{ $d['status'] }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3" class="right">Drinks Total:</td>
        <td class="right">{{ number_format($drinksTotal, 2) }} EGP</td>
        <td></td>
      </tr>
    </tfoot>
  </table>
@else
  <p class="muted">No drinks ordered.</p>
@endif


    <div style="margin-top:18px">
      <table style="width:100%">
        <tbody>
          <tr>
            <td class="right muted" style="width:85%">Session Fee:</td>
            <td class="right">{{ number_format($bill, 2) }} EGP</td>
          </tr>
          <tr>
            <td class="right muted">Drinks Total:</td>
            <td class="right">{{ number_format($drinksTotal, 2) }} EGP</td>
          </tr>
          <tr>
            <td class="right" style="font-weight:800">Grand Total:</td>
            <td class="right" style="font-weight:800">{{ number_format($grandTotal, 2) }} EGP</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div style="text-align:right">
      <button class="print-btn" onclick="window.print()">Print Receipt</button>
    </div>
  </div>
</body>
</html>
