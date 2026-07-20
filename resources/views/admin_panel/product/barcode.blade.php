<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Barcode - {{ $product->item_name }}</title>
<style>
:root {
  --label-w: 42mm;
  --barcode-w: 58mm;
  --barcode-h: 5mm;
}

*, *::before, *::after { box-sizing: border-box; }

body {
  font-family: 'Inter', 'Arial', sans-serif;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 0;
  padding: 15px;
  background: #fff;
}

.btn-print {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 15px;
  padding: 10px 28px;
  background: linear-gradient(135deg, #0b1a33, #1a4d8c);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all .25s ease;
}

.btn-print:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(43,127,255,.3); }

.label {
  border: 1px dashed #999;
  width: var(--label-w);
  text-align: center;
  page-break-inside: avoid;
}

.brand-name {
  font-size: 15px;
  font-weight: 800;
  letter-spacing: 2px;
  margin: 6px 0 4px;
  text-transform: uppercase;
  color: #000;
}

.barcode-block {
  width: var(--barcode-w);
  margin: 4px auto 0;
}

.barcode-block svg {
  display: block;
  width: var(--barcode-w) !important;
  height: var(--barcode-h) !important;
  margin: 8px auto;
  shape-rendering: crispEdges;
  overflow: visible;
}

.barcode-text {
  text-align: center;
  font-size: 11px;
  font-weight: 600;
  line-height: 1.3;
  margin-top: 3px;
  overflow-wrap: anywhere;
  word-break: break-word;
  color: #000;
}

.price {
  font-size: 13px;
  font-weight: 800;
  margin: 4px 0 6px;
  color: #000;
}

@media print {
  @page { margin: 0mm; }
  .label { border: none; }
  .btn-print { display: none !important; }
  * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
</head>
<body>

<button class="btn-print no-print" onclick="window.print()">
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
  Print Label
</button>

<div class="label">
  <div class="brand-name">Bin Sultan</div>
  <div class="barcode-block">
    {!! DNS1D::getBarcodeSVG($product->barcode_path, 'C128', 1.6, 23, 'black', false) !!}
  </div>
  <div class="barcode-text">
    {{ $product->barcode_path }}<br>
    {{ $product->item_name }}
    @if($product->item_code)<br><span style="font-size:10px;color:#555;">[{{ $product->item_code }}]</span>@endif
  </div>
  <div class="price">
    PKR {{ number_format(
      $product->activeDiscount
        ? $product->activeDiscount->final_price
        : $product->price
    ) }}
  </div>
</div>

<script>window.onload = function() { if (!window.matchMedia('print').matches) { window.print(); } };</script>
</body>
</html>
