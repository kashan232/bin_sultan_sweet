<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SALES INVOICE</title>
  <style>
    /* Reset & safety */
    * {
      box-sizing: border-box;
    }

    html,
    body {
      margin: 0;
      padding: 0;
      background: #fff;
      color: #000;
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
      line-height: 1.25;
      font-weight: 600;
    }

    /* Buttons row */
    .actions {
      max-width: 80mm;
      margin: 10px auto 0;
      display: flex;
      gap: 8px;
      justify-content: flex-end;
      padding: 0 5mm;
    }

    tbody td {
      padding: 3px 2px;
      vertical-align: top;
    }

    /* thead th,
    tbody td {
      white-space: nowrap;
      overflow: hidden;
    } */

    /* Column alignments */
    .col-item {
      text-align: left;
      white-space: normal !important;
      /* ✅ break allow */
      word-break: break-word;
      /* long words break */
      overflow-wrap: anywhere;
      /* receipt width safe */
      line-height: 1.3;
    }

    .col-qty,
    .col-unit,
    .col-price,
    .col-disc,
    .col-amount {
      white-space: nowrap;
    }

    .col-qty {
      text-align: center;
    }

    .col-unit {
      text-align: center;
    }

    .col-price {
      text-align: right;
    }

    .col-disc {
      text-align: right;
    }

    .col-amount {
      text-align: right;
    }

    .btn {
      border: 1px solid #000;
      background: #f5f5f5;
      padding: 6px 10px;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      border-radius: 6px;
    }

    .btn:active {
      transform: translateY(1px);
    }

    /* Container – sized for 80mm roll, but responsive on screen */
    .receipt-container {
      width: 100%;
      max-width: 80mm;
      margin: 0 auto;
      padding: 3mm 5mm 6mm;
    }

    .center {
      text-align: center;
    }

    .bold {
      font-weight: 700;
    }

    .line {
      border-top: 1px dashed #000;
      margin: 6px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    th,
    td {
      padding: 2px 0;
      vertical-align: top;
      word-wrap: break-word;
    }

    th {
      text-align: left;
      font-size: 11px;
      font-weight: 700;
    }

    td {
      font-size: 11px;
    }

    td:last-child,
    th:last-child {
      text-align: right;
    }

    /* Make headings & totals stand out */
    .title {
      margin: 0;
      font-size: 16px;
      font-weight: 800;
      letter-spacing: .5px;
    }

    .subtitle {
      margin: 0;
      font-size: 12px;
      font-weight: 700;
    }

    .totals th,
    .totals td {
      padding: 3px 0;
    }

    .items-unit {
      text-align: center;
      white-space: nowrap;
      padding-right: 6px;
    }

    .items-price {
      text-align: right;
      white-space: nowrap;
      padding-left: 6px;
    }

    .totals tr:last-child td,
    .totals tr:last-child th {
      font-weight: 800;
    }

    .footer {
      text-align: center;
      font-size: 11px;
      margin-top: 8px;
      border-top: 1px dashed #000;
      padding-top: 6px;
      font-weight: 600;
    }

    /* Print-safe margins so sides don’t cut */
    @media print {
      @page {
        size: 80mm auto;
        margin: 0mm 3mm;  /* added 3mm safe side margins to prevent cutting */
      }

      body {
        margin: 0;
        padding: 0;
      }

      .actions {
        display: none !important;
      }

      .receipt-container {
        max-width: none;
        width: 100%;
        padding: 2mm 3mm !important; /* side padding to prevent text cut */
        box-sizing: border-box;
      }
    }

    .items-summary {
      border: 1.5px solid #000;
      padding: 6px;
      margin-top: 6px;
      margin-bottom: 6px;
      background: #f8f8f8;
    }

    .items-summary table {
      margin: 0;
    }

    .items-summary th,
    .items-summary td {
      padding: 4px 0;
      font-size: 12px;
    }

    .total-units-row span {
      font-size: 12px;
      text-transform: uppercase;
    }

    /* Extra emphasis on Total Items */
    .total-items-row th,
    .total-items-row td {
      font-weight: 800;
      border-bottom: 1px dashed #000;
      padding-bottom: 6px;
    }

    /* Grand / Net emphasis */
    .final-amount th,
    .final-amount td {
      font-weight: 900;
      font-size: 13px;
    }
    .page-break {
        page-break-after: always;
    }
  </style>
</head>

<body>

  <!-- Action Buttons -->
  <div class="actions">
    <button class="btn" id="btnBack" type="button">Back</button>
    <button class="btn" id="btnPrint" type="button">Print</button>
  </div>

  @php
    $mode = $mode ?? 'invoice';
  @endphp

  {{-- Print KOTs / Tokens --}}
  @if(in_array($mode, ['token_only', 'token_and_invoice']))
      @php
          $categoryGroups = collect($saleItems)->groupBy('category');
      @endphp

      @foreach($categoryGroups as $categoryName => $groupItems)
      <div class="receipt-container @if(!$loop->last || $mode == 'token_and_invoice') page-break @endif" style="padding: 2mm 2mm;">
          <div class="center">
              <h2 class="title">Bin Sultan Sweets</h2>
              <h3 style="margin:5px 0; border:1px dashed #000; padding:4px;">TOKEN ({{ strtoupper($categoryName) }})</h3>
              <p style="margin:5px 0; font-size:14px; font-weight:bold;">Order: {{ $sale->order_type ?? 'Walk-in' }} @if($sale->table_id) | Table: {{ \App\Models\Table::find($sale->table_id)->table_name ?? '' }} @endif</p>
          </div>
          <div class="line"></div>
          <table>
              <tr>
                  <th>Invoice No</th>
                  <td>{{ $sale->invoice_no ?? 'N/A' }}</td>
                  <th>Date</th>
                  <td>{{ optional($sale->created_at)->format('d-m-Y h:i A') }}</td>
              </tr>
          </table>
          <div class="line"></div>
          <table style="table-layout: fixed; width: 100%;">
              <colgroup>
                  <col style="width:70%;"> <!-- Item Name -->
                  <col style="width:30%;"> <!-- Qty (right aligned) -->
              </colgroup>
              <thead>
                  <tr class="bold">
                      <th class="col-item" style="text-align:left;">Item</th>
                      <th style="text-align:right;">Qty</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($groupItems as $item)
                  <tr>
                      <td style="font-size:14px; font-weight:bold; word-break:break-word; padding-right:4px;">{{ $item['item_name'] }}</td>
                      <td style="font-size:14px; font-weight:bold; text-align:right;">{{ $item['qty'] }}</td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
          <div class="line"></div>
          <div class="center" style="margin-top:10px;">
              <p>*** Thank You ***</p>
          </div>
      </div>
      @endforeach
  @endif

  {{-- Print Main Invoice --}}
  @if(in_array($mode, ['invoice', 'token_and_invoice']))
  <div class="receipt-container">
    <!-- Header -->
    <div class="center">
      <h2 class="title">Bin Sultan</h2>
      <p class="subtitle">Sweet & Bakers</p>
      <p style="margin:0;">Latifabad no 6 Near Shadman Hall  Hyderabad</p>
      <p style="margin:0;">Phone: 022786661</p>
    </div>

    <div class="line"></div>
    @if($saleReturn)
    <div style="
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 10px;
    ">
      SALES INVOICE
    </div>
    @else
    <div style="
        text-align: center;
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 10px;
    ">
      SALES INVOICE
    </div>
    @endif

    <div class="line"></div>
    <!-- Details -->
    <table>
      <tr>
        <th>Invoice No</th>
        <td>{{ $sale->invoice_no ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Date</th>
        <td>{{ optional($sale->created_at)->format('d-m-Y h:i A') ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Customer</th>
        <td>{{ $sale->customer_relation->customer_name ?? 'N/A' }}</td>
      </tr>

      @if($sale->customer != 'Walk-in Customer')
      <tr>
        <th>Mobile</th>
        <td>{{ $sale->customer_relation->mobile ?? 'N/A' }}</td>
      </tr>
      <tr>
        <th>Email</th>
        <td>{{ $sale->customer_relation->email_address ?? 'N/A' }}</td>
      </tr>
      @endif

      <tr>
        <th>Reference</th>
        <td>{{ $sale->reference ?? '-' }}</td>
      </tr>
    </table>


    <div class="line"></div>

    <!-- Items -->
    <!-- Items -->
    <table>
      <colgroup>
        <col style="width:50%;"> <!-- Item -->
        <col style="width:12%;"> <!-- Qty -->
        <col style="width:18%;"> <!-- Price -->
        <col style="width:20%;"> <!-- Amount -->
      </colgroup>

      <thead>
        <tr class="bold">
          <th class="col-item">Item</th>
          <th class="col-qty">Qty</th>
          <th class="col-price">Price</th>
          <th class="col-amount">Amount</th>
        </tr>
      </thead>

      <tbody>
        @foreach($saleItems as $item)
        <tr>
          <td class="col-item">{{ $item['item_name'] }}</td>
          <td class="col-qty">{{ $item['qty'] }}</td>

          <td class="col-price">
            {{ rtrim(rtrim(number_format($item['price'], 2), '0'), '.') }}
          </td>

          <td class="col-amount bold">
            {{ rtrim(rtrim(number_format($item['total'], 2), '0'), '.') }}
          </td>
        </tr>
        @endforeach
      </tbody>


    </table>

    <div class="line"></div>
    @php
    /* ===============================
    SALE UNITS (Recalculated from Items)
    ================================ */
    $saleUnits = [
        'Pc' => 0,
        'Mtr' => 0,
        'Yd' => 0,
    ];

    foreach($saleItems as $item) {
        $qty = (float)$item['qty'];
        // $unitRaw is not defined here yet, use $item['unit'] and normalize same way
        $uRaw = is_array($item['unit']) ? ($item['unit'][0] ?? '') : $item['unit'];
        $u = strtolower(trim($uRaw));
        
        if (in_array($u, ['pc','pcs','piece','pieces'])) {
            $saleUnits['Pc'] += $qty;
        } elseif (in_array($u, ['mtr','meter','metre'])) {
            $saleUnits['Mtr'] += $qty;
        } elseif (in_array($u, ['yd','yard','yards'])) {
            $saleUnits['Yd'] += $qty;
        } else {
            $saleUnits['Pc'] += $qty; 
        }
    }

    /* ===============================
    RETURN UNITS (calculate live)
    ================================ */
    $returnUnits = [
    'Pc' => 0,
    'Mtr' => 0,
    'Yd' => 0,
    ];

    if ($saleReturn) {

    $rUnits = explode(',', $saleReturn->unit ?? '');
    $rQtys = explode(',', $saleReturn->qty ?? '');

    foreach ($rQtys as $i => $qty) {
    $qty = (float) $qty;
    if ($qty <= 0) continue;

      $unit=strtolower(trim($rUnits[$i] ?? '' ));

      if (in_array($unit, ['pc','pcs','piece','pieces'])) {
      $returnUnits['Pc'] +=$qty;
      } elseif (in_array($unit, ['mtr','meter','metre'])) {
      $returnUnits['Mtr'] +=$qty;
      } elseif (in_array($unit, ['yd','yard','yards'])) {
      $returnUnits['Yd'] +=$qty;
      }
      }
      }

      /*===============================FINAL UNITS=SALE - RETURN================================*/
      $finalUnits=[ 'Pc'=> max(0, $saleUnits['Pc'] - $returnUnits['Pc']),
      'Mtr' => max(0, $saleUnits['Mtr'] - $returnUnits['Mtr']),
      'Yd' => max(0, $saleUnits['Yd'] - $returnUnits['Yd']),
      ];
      @endphp
      <!-- Totals -->
      <div class="items-summary">
        <table class="totals">
          @php
          $units = [
          'Pc' => $saleUnits['Pc'],
          'Mtr' => $saleUnits['Mtr'],
          'Yd' => $saleUnits['Yd'],
          ];
          @endphp
          @foreach($finalUnits as $label => $value)
          @if($value > 0)
          <tr class="total-units-row total-items-row">
            <th>{{ $label }}</th>
            <td>{{ $value }}</td>
          </tr>
          @endif
          @endforeach
          <tr>
            <th>Grand Total</th>
            <td class="bold">
              {{ rtrim(rtrim(number_format($bill->total_bill_amount ?? 0, 2), '0'), '.') }}
            </td>
          </tr>

          @if(!empty($bill->total_extradiscount) && $bill->total_extradiscount > 0)
          <tr>
            <th>Extra Discount</th>
            <td>
              {{ rtrim(rtrim(number_format($bill->total_extradiscount, 2), '0'), '.') }}
            </td>
          </tr>
          @endif

          <tr>
            <th>Net Amount</th>
            <td class="bold">
              {{ rtrim(rtrim(number_format($bill->total_net ?? 0, 2), '0'), '.') }}
            </td>
          </tr>

          <tr>
            <th>Cash</th>
            <td>
              {{ rtrim(rtrim(number_format($bill->cash ?? 0, 2), '0'), '.') }}
            </td>
          </tr>

          <tr>
            <th>Change</th>
            <td>
              {{ rtrim(rtrim(number_format($bill->change ?? 0, 2), '0'), '.') }}
            </td>
          </tr>
        </table>
      </div>

      <div class="line"></div>

      <p class="bold" style="margin:0 0 4px 0;">Amount In Words:</p>
      <p id="amountInWords" style="margin:0;">Loading...</p>

      <!-- Footer -->
      <div class="footer">
        <p>Please check bakery items at the time of purchase</p>
        <p>Bakery & sweets items are non-returnable</p>
        <p>Develop By: ProWave Technologies</p>
        <p>+92 317 3836 223 | +92 317 3859 647</p>
        <p>*** Thank you for the visit ***</p>
      </div>
  </div>
  @endif

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Grab return_to and autoprint from query string (blade also provides server-side values)
      const query = new URLSearchParams(window.location.search);
      const returnTo = query.get('return_to') || "{{ route('sale.index') }}"; // fallback
      const autoprint = query.get('autoprint') === '1';

      // Show amount in words (your existing code)
      const amount = parseFloat(`{{ $bill->total_net ?? 0 }}`) || 0;
      const amountInWords = (function numberToWords(num) {
        const ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine",
          "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen",
          "Sixteen", "Seventeen", "Eighteen", "Nineteen"
        ];
        const tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
        if (num === 0) return "Zero";

        function convert_hundreds(n) {
          let str = "";
          if (n > 99) {
            str += ones[Math.floor(n / 100)] + " Hundred ";
            n %= 100;
          }
          if (n > 19) {
            str += tens[Math.floor(n / 10)] + " " + ones[n % 10];
          } else {
            str += ones[n];
          }
          return str.trim();
        }
        let crore = Math.floor(num / 10000000);
        let lakh = Math.floor((num % 10000000) / 100000);
        let thousand = Math.floor((num % 100000) / 1000);
        let hundred = num % 1000;
        let result = "";
        if (crore) result += convert_hundreds(crore) + " Crore ";
        if (lakh) result += convert_hundreds(lakh) + " Lakh ";
        if (thousand) result += convert_hundreds(thousand) + " Thousand ";
        if (hundred) result += convert_hundreds(hundred);
        return result.trim();
      })(Math.floor(amount));
      document.getElementById("amountInWords").innerText = "Rupees " + amountInWords + " Only";

      // Print button
      document.getElementById('btnPrint').addEventListener('click', () => window.print());

      // Back button: go to returnTo if present otherwise history.back
      document.getElementById('btnBack').addEventListener('click', () => {
        if (returnTo) {
          // If returnTo is same-origin, navigate
          try {
            window.location.href = decodeURIComponent(returnTo);
          } catch (e) {
            // fallback
            if (history.length > 1) history.back();
            else window.close();
          }
        } else {
          if (history.length > 1) history.back();
          else window.close();
        }
      });

      // Auto-print if requested, then redirect back when print finished.
      if (autoprint) {
        // small delay to let the page render
        setTimeout(() => {
          window.print();

          // best-effort: try onafterprint, fallback to timeout redirect after 1.5s
          const redirectAfterPrint = () => {
            try {
              const url = decodeURIComponent(returnTo);
              // if URL belongs to same origin navigate, else use history
              const a = document.createElement('a');
              a.href = url;
              if (a.origin === location.origin) {
                window.location.href = url;
              } else {
                // cross-origin: just go to sale.index
                window.location.href = "{{ route('sale.add') }}";
              }
            } catch (e) {
              if (history.length > 1) history.back();
              else window.location.href = "{{ route('sale.add') }}";
            }
          };

          if ('onafterprint' in window) {
            window.onafterprint = redirectAfterPrint;
          }
          // Fallback redirect after 1.5s in case onafterprint not supported
          setTimeout(redirectAfterPrint, 1500);
        }, 400);
      }

      // keyboard shortcut for back: Alt+B
      document.addEventListener('keydown', (e) => {
        if ((e.altKey || e.metaKey) && e.key.toLowerCase() === 'b') {
          e.preventDefault();
          document.getElementById('btnBack').click();
        }
      });
    });
  </script>
</body>

</html>