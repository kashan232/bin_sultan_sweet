<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Reports</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 20px; }
        .header { text-align: center; padding-bottom: 15px; border-bottom: 3px solid #4f46e5; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #4f46e5; margin: 0 0 4px; }
        .header p { font-size: 12px; color: #64748b; margin: 0; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 14px; color: #4f46e5; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin: 0 0 10px; }
        .stats { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 16px; }
        .stat-box { flex: 1; min-width: 100px; background: #f8fafc; border-left: 4px solid #4f46e5; padding: 10px 12px; border-radius: 4px; }
        .stat-box .label { font-size: 9px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px; }
        .stat-box .value { font-size: 16px; font-weight: 900; color: #1e293b; }
        .stat-box.green { border-left-color: #10b981; }
        .stat-box.red { border-left-color: #ef4444; }
        .stat-box.orange { border-left-color: #f59e0b; }
        .stat-box.purple { border-left-color: #8b5cf6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table th { background: #4f46e5; color: #fff; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        table td { padding: 6px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        table tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .footer { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0; }
        .row { display: flex; gap: 20px; }
        .col { flex: 1; }
    </style>
</head>
<body>
    <div class="header">
        <h1>System Reports</h1>
        <p>{{ $startDate && $endDate ? \Carbon\Carbon::parse($startDate)->format('M Y') . ' — ' . \Carbon\Carbon::parse($endDate)->format('M Y') : 'All Time Overview' }} | Generated: {{ now()->format('d-M-Y h:i A') }}</p>
    </div>

    {{-- Master Stats --}}
    <div class="section">
        <h2>Master Data</h2>
        <div class="stats">
            <div class="stat-box"><div class="label">Categories</div><div class="value">{{ $categoryCount }}</div></div>
            <div class="stat-box green"><div class="label">Subcategories</div><div class="value">{{ $subcategoryCount }}</div></div>
            <div class="stat-box red"><div class="label">Products</div><div class="value">{{ $productCount }}</div></div>
            <div class="stat-box orange"><div class="label">Customers</div><div class="value">{{ $customerscount }}</div></div>
        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="section">
        <h2>Financial Summary</h2>
        <div class="stats">
            <div class="stat-box"><div class="label">Total Purchases</div><div class="value">Rs {{ number_format($totalPurchases, 0) }}</div></div>
            <div class="stat-box red"><div class="label">Purchase Returns</div><div class="value">Rs {{ number_format($totalPurchaseReturns, 0) }}</div></div>
            <div class="stat-box green"><div class="label">Total Sales</div><div class="value">Rs {{ number_format($totalSales, 0) }}</div></div>
            <div class="stat-box orange"><div class="label">Sales Returns</div><div class="value">Rs {{ number_format($totalSalesReturns, 0) }}</div></div>
        </div>
    </div>

    {{-- Sales vs Purchase Table --}}
    @if(count($labels))
    <div class="section">
        <h2>Sales &amp; Purchase Breakdown</h2>
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th class="text-right">Sales (Rs)</th>
                    <th class="text-right">Purchases (Rs)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labels as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-right">{{ number_format($salesData[$i] ?? 0, 0) }}</td>
                    <td class="text-right">{{ number_format($purchaseData[$i] ?? 0, 0) }}</td>
                </tr>
                @endforeach
                <tr style="font-weight:900;background:#eef2ff;">
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ number_format(array_sum($salesData), 0) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format(array_sum($purchaseData), 0) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Category Products --}}
    <div class="section">
        <h2>Category Wise Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Products</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryProductChart['categories'] as $i => $cat)
                <tr>
                    <td>{{ $cat }}</td>
                    <td class="text-right">{{ $categoryProductChart['series'][0]['data'][$i] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Low Stock --}}
    @if(count($lowStockChart['categories']))
    <div class="section">
        <h2>Low Stock Alerts</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Stock</th>
                    <th class="text-right">Alert Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockChart['categories'] as $i => $name)
                <tr>
                    <td>{{ $name }}</td>
                    <td class="text-right">{{ $lowStockChart['series'][0]['data'][$i] ?? 0 }}</td>
                    <td class="text-right">{{ $lowStockChart['series'][1]['data'][$i] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Category Sub table --}}
    @if(count($categorySubChart['categories']))
    <div class="section">
        <h2>Category — Subcategories &amp; Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Subcategories</th>
                    <th class="text-right">Products</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorySubChart['categories'] as $i => $cat)
                <tr>
                    <td>{{ $cat }}</td>
                    <td class="text-right">{{ $categorySubChart['series'][0]['data'][$i] ?? 0 }}</td>
                    <td class="text-right">{{ $categorySubChart['series'][1]['data'][$i] ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Expense --}}
    @if(count($expenseChartData))
    <div class="section">
        <h2>Expense Distribution</h2>
        @foreach($expenseChartData as $head)
        <div style="margin-bottom:12px;">
            <strong style="font-size:11px;color:#4f46e5;">{{ $head['head_name'] }}</strong>
            <table>
                <thead>
                    <tr>
                        <th>Account</th>
                        <th class="text-right">Amount (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($head['categories'] as $j => $acct)
                    <tr>
                        <td>{{ $acct }}</td>
                        <td class="text-right">{{ number_format($head['series'][0]['data'][$j] ?? 0, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endif

    <div class="footer">
        Bin Sultan Sweet — System Reports &bull; Page 1 of 1
    </div>
</body>
</html>