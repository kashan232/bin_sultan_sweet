<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Raw Material Stock Report - {{ date('d-M-Y') }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; }

@media print {
  body { margin: 0; padding: 0; }
  .no-print { display: none !important; }
  @page { size: 80mm auto; margin: 0; }
}

body {
  font-family: 'Arial', sans-serif;
  font-size: 10px;
  color: #000 !important;
  background: #fff;
  margin: 0;
  padding: 0;
}

.receipt {
  width: 100%;
  max-width: 260px;
  margin: 0 auto;
  padding: 6px 6px;
}

.center { text-align: center; }
.bold { font-weight: 700 !important; }
.line { border-top: 1px dashed #000; margin: 4px 0; }
.dbl-line { border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin: 4px 0; }

.brand { font-size: 15px; font-weight: 800; margin-bottom: 1px; text-transform: uppercase; letter-spacing: 0.3px; }
.address { font-size: 9.5px; font-weight: 700; line-height: 1.2; }
.title { font-size: 11px; font-weight: 800; margin: 4px 0; text-transform: uppercase; letter-spacing: 0.5px; }

table { width: 100%; border-collapse: collapse; margin: 4px 0; table-layout: fixed; }
th {
  text-align: left; font-size: 9px; font-weight: 800;
  border-bottom: 1.5px solid #000; padding: 3px 1px; text-transform: uppercase;
}
td {
  font-size: 9px; font-weight: 700;
  padding: 3px 1px; vertical-align: top;
  border-bottom: 1px dotted #000; word-wrap: break-word;
}
table tbody tr:last-child td { border-bottom: none; }
.r { text-align: right; }

.info-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; font-weight: 700; }

.print-btn {
  display: block; margin: 10px auto; padding: 8px 20px;
  background: #000; color: #fff; border: none; border-radius: 4px;
  font-size: 12px; font-weight: 700; cursor: pointer; text-transform: uppercase;
}

.item-name { font-size: 9.5px; font-weight: 800; line-height: 1.1; word-break: break-word; text-transform: uppercase; }
.item-code { font-size: 8px; font-weight: 700; color: #000; }
</style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">Print Stock Report</button>

<div class="receipt">
  <div class="center">
    <div class="brand">Bin Sultan</div>
    <div class="address" style="font-size:11px;margin-bottom:1px;">Sweets & Bakers</div>
    <div class="address">Latifabad no 6 Near Shadman Hall Hyderabad</div>
    <div class="address">Ph: 022 2786661</div>
  </div>

  <div class="dbl-line" style="margin-top:5px;"></div>
  <div class="center title">Raw Material Stock Report</div>
  <div class="dbl-line" style="margin-bottom:5px;"></div>

  <div class="info-row"><span>Date:</span><span>{{ date('d-M-Y') }}</span></div>
  <div class="info-row"><span>Printed Time:</span><span>{{ date('h:i A') }}</span></div>
  <div class="info-row"><span>Report By:</span><span>{{ auth()->user()->name ?? 'Admin' }}</span></div>

  <div class="line"></div>

  <table>
    <thead>
      <tr>
        <th style="width:36%;">Item</th>
        <th style="width:22%;" class="r">Init+In</th>
        <th style="width:20%;" class="r">Out</th>
        <th style="width:22%;" class="r">Balance</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reportData as $r)
      @php
        $initPurch = $r->initial_stock + $r->purchased_qty;
        $outQty    = $r->out_qty;
        $balance   = $r->stock_qty;
        $unit      = strtolower($r->unit) === 'kg' ? 'kg' : $r->unit;
      @endphp
      <tr>
        <td>
          <div class="item-name">{{ $r->name }} <span class="item-code">[{{ $r->item_code }}]</span></div>
        </td>
        <td class="r">{{ number_format($initPurch, 1) }}{{ $unit }}</td>
        <td class="r">-{{ number_format($outQty, 1) }}{{ $unit }}</td>
        <td class="r bold">{{ number_format($balance, 1) }}{{ $unit }}</td>
      </tr>
      <tr style="border-bottom:1px dotted #000;">
        <td colspan="2" style="font-size:8px; padding-bottom:3px;">Rate: Rs {{ number_format($r->unit_price, 0) }}</td>
        <td colspan="2" class="r bold" style="font-size:8px; padding-bottom:3px;">Val: Rs {{ number_format($r->stock_value, 0) }}</td>
      </tr>
      @empty
      <tr><td colspan="4" class="center" style="padding:8px 0;">No raw materials found</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="line"></div>
  
  <div class="info-row"><span>TOTAL INITIAL STOCK:</span><span>{{ number_format($totalInitial, 2) }}</span></div>
  <div class="info-row"><span>TOTAL PURCHASES (IN):</span><span>+{{ number_format($totalPurchased, 2) }}</span></div>
  <div class="info-row"><span>TOTAL ISSUED (OUT):</span><span>-{{ number_format($totalOut, 2) }}</span></div>
  
  <div class="line"></div>
  <div class="info-row bold" style="font-size:10px;">
    <span>TOTAL AVAIL STOCK:</span>
    <span>{{ number_format($totalStock, 2) }}</span>
  </div>
  <div class="info-row bold" style="font-size:10px;">
    <span>TOTAL STOCK VALUE:</span>
    <span>Rs {{ number_format($totalValue, 2) }}</span>
  </div>

  <div class="line" style="margin-bottom:5px;"></div>

  <div class="center bold" style="font-size:9px;margin-top:4px;">
    <div>Total Materials: {{ count($reportData) }}</div>
    <div style="margin-top:3px;">— End of Stock Report —</div>
  </div>
</div>

<script>
  window.onload = function() {
    if (!window.matchMedia('print').matches) {
      window.print();
    }
  };
</script>
</body>
</html>
