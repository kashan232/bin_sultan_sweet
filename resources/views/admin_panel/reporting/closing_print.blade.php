<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Closing Report - {{ $dateLabel }}</title>
<style>
*, *::before, *::after { box-sizing: border-box; }

@media print {
  body { margin: 0; padding: 0; }
  .no-print { display: none !important; }
  @page { size: 58mm auto; margin: 0; }
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
  max-width: 210px;
  margin: 0 auto;
  padding: 6px 4px;
}

.center { text-align: center; }
.bold { font-weight: 700 !important; }
.line { border-top: 1px dashed #000; margin: 3px 0; }
.dbl-line { border-top: 1.5px solid #000; border-bottom: 1.5px solid #000; height: 2px; margin: 3px 0; }

.brand { font-size: 14px; font-weight: 700; margin-bottom: 1px; text-transform: uppercase; letter-spacing: 0.3px; }
.address { font-size: 9px; font-weight: 700; line-height: 1.2; }
.title { font-size: 11px; font-weight: 700; margin: 4px 0; text-transform: uppercase; letter-spacing: 0.5px; }

table { width: 100%; border-collapse: collapse; margin: 3px 0; }
th {
  text-align: left; font-size: 9px; font-weight: 700;
  border-bottom: 1.5px solid #000; padding: 2px 1px;
}
td {
  font-size: 9px; font-weight: 700;
  padding: 2px 1px; vertical-align: top;
  border-bottom: 1px dotted #000;
}
table tbody tr:last-child td { border-bottom: none; }
.r { text-align: right; }

.info-row { display: flex; justify-content: space-between; font-size: 9px; margin: 2px 0; font-weight: 700; }

.print-btn {
  display: block; margin: 10px auto; padding: 8px 20px;
  background: #000; color: #fff; border: none; border-radius: 4px;
  font-size: 12px; font-weight: 700; cursor: pointer; text-transform: uppercase;
}

.item-name { font-size: 9px; font-weight: 700; line-height: 1.1; word-break: break-word; }
.item-code { font-size: 8px; font-weight: 700; color: #333; }
</style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">Print Closing</button>

<div class="receipt">
  <div class="center">
    <div class="brand">Bin Sultan</div>
    <div class="address" style="font-size:11px;margin-bottom:1px;">Sweets & Bakers</div>
    <div class="address">Latifabad no 6 Near Shadman Hall Hyderabad</div>
    <div class="address">Ph: 022 2786661</div>
  </div>

  <div class="dbl-line" style="margin-top:5px;"></div>
  <div class="center title">Daily Closing Report</div>
  <div class="dbl-line" style="margin-bottom:5px;"></div>

  <div class="info-row"><span>Date:</span><span>{{ $dateLabel }}</span></div>
  <div class="info-row"><span>Shift:</span><span>{{ $timeLabel }}</span></div>
  <div class="info-row"><span>Printed:</span><span>{{ \Carbon\Carbon::now()->format('d-M-Y h:i A') }}</span></div>

  <div class="line"></div>

  <table>
    <thead>
      <tr>
        <th style="width:34%;">Item</th>
        <th style="width:22%;" class="r">Purch+Prod</th>
        <th style="width:22%;" class="r">Sold</th>
        <th style="width:22%;" class="r">Balance</th>
      </tr>
    </thead>
    <tbody>
      @php $tp=0; $ts=0; $ta=0; @endphp
      @forelse($rows as $r)
      @php
        $purchProd = ((float)($r->purchased ?? 0)) + ((float)($r->produced ?? 0));
        $sold      = (float)($r->sold ?? 0);
        $avail     = (float)($r->balance ?? 0);
        $isKg      = !empty($r->is_kg);
        $itemName  = $r->item_name ?? '';
        $itemCode  = $r->item_code ?? '';
        $tp += $purchProd; $ts += $sold; $ta += $avail;
      @endphp
      <tr>
        <td>
          <div class="item-name">{{ $itemName }} <span class="item-code">[{{ $itemCode }}]</span></div>
        </td>
        <td class="r">{{ $isKg ? number_format($purchProd/1000, 2) : number_format($purchProd) }}{{ $isKg ? 'kg' : '' }}</td>
        <td class="r">{{ $isKg ? number_format($sold/1000, 2) : number_format($sold) }}{{ $isKg ? 'kg' : '' }}</td>
        <td class="r">{{ $isKg ? number_format($avail/1000, 2) : number_format($avail) }}{{ $isKg ? 'kg' : '' }}</td>
      </tr>
      @empty
      <tr><td colspan="4" class="center" style="padding:8px 0;">No data for this period</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="line"></div>
  <div style="display:flex;justify-content:space-between;font-weight:700;font-size:9px;padding:2px 1px;">
    <span>TOTALS</span>
    <span>{{ number_format($tp) }}</span>
    <span>{{ number_format($ts) }}</span>
    <span>{{ number_format($ta) }}</span>
  </div>
  <div class="line" style="margin-bottom:5px;"></div>

  <div class="center bold" style="font-size:9px;margin-top:4px;">
    <div>Total Items: {{ $total }}</div>
    <div style="margin-top:3px;">— End of Report —</div>
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
