@extends('admin_panel.layout.app')

@section('content')
<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #818cf8;
        --primary-soft: #eef2ff;
        --accent-1: #0ea5e9;
        --accent-2: #8b5cf6;
        --accent-3: #ec4899;
        --bg-page: #f0f2f5;
        --card-bg: #ffffff;
        --card-border: rgba(0,0,0,0.04);
        --card-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06);
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
    }

    .reports-page {
        background: var(--bg-page);
        min-height: 100vh;
        padding: 28px 24px;
        position: relative;
    }
    .reports-page::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 280px;
        background: linear-gradient(135deg, #4f46e5 0%, #0ea5e9 50%, #8b5cf6 100%);
        opacity: 0.08;
        pointer-events: none;
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06), 0 20px 40px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .stat-card {
        padding: 22px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px;
        height: 100%;
        border-radius: 0 4px 4px 0;
    }
    .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .stat-card .stat-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        margin-bottom: 2px;
    }
    .stat-card .stat-value {
        font-size: 28px;
        font-weight: 900;
        color: var(--text-primary);
        font-family: 'Inter', 'Segoe UI', sans-serif;
        line-height: 1.15;
    }
    .stat-card .stat-sub {
        font-size: 13px;
        color: var(--text-muted);
        margin-top: 1px;
        font-weight: 500;
    }

    .stat-card.border-accent-1::after { background: linear-gradient(180deg, #4f46e5, #818cf8); }
    .stat-card.border-accent-2::after { background: linear-gradient(180deg, #0ea5e9, #38bdf8); }
    .stat-card.border-accent-3::after { background: linear-gradient(180deg, #ef4444, #f87171); }
    .stat-card.border-accent-4::after { background: linear-gradient(180deg, #f59e0b, #fbbf24); }
    .stat-card.border-accent-5::after { background: linear-gradient(180deg, #10b981, #34d399); }
    .stat-card.border-accent-6::after { background: linear-gradient(180deg, #f97316, #fb923c); }
    .stat-card.border-accent-7::after { background: linear-gradient(180deg, #8b5cf6, #a78bfa); }
    .stat-card.border-accent-8::after { background: linear-gradient(180deg, #ec4899, #f472b6); }

    .filter-card {
        padding: 24px 28px;
        margin-bottom: 28px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(0,0,0,0.04);
    }
    .filter-card label {
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .filter-card .form-control, .filter-card .form-select {
        background: #f1f5f9;
        border: 2px solid transparent;
        color: var(--text-primary);
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.25s;
    }
    .filter-card .form-control:focus, .filter-card .form-select:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79,70,229,0.1);
    }
    .filter-card .btn-filter {
        background: linear-gradient(135deg, var(--primary), var(--accent-2));
        border: none;
        border-radius: 12px;
        padding: 10px 28px;
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        transition: all 0.3s;
        box-shadow: 0 4px 14px rgba(79,70,229,0.3);
    }
    .filter-card .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(79,70,229,0.35);
    }
    .filter-card .btn-reset {
        background: #f1f5f9;
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        font-size: 14px;
        color: var(--text-secondary);
        transition: all 0.25s;
    }
    .filter-card .btn-reset:hover {
        background: #e2e8f0;
        color: var(--text-primary);
    }

    .chart-container {
        padding: 20px 24px;
    }
    .chart-container .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .chart-container .chart-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 0;
    }
    .chart-container .chart-sub {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 500;
    }
    .chart-container .chart-filter-select {
        background: #f1f5f9;
        border: 2px solid transparent;
        color: var(--text-primary);
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .chart-container .chart-filter-select:focus {
        border-color: var(--primary);
        outline: none;
    }
    .chart-wrapper {
        border-radius: 12px;
        overflow: hidden;
        background: #fafbfc;
        border: 1px solid rgba(0,0,0,0.03);
    }

    .modal-custom {
        background: rgba(15,23,42,0.6);
        backdrop-filter: blur(8px);
    }
    .modal-custom .modal-content {
        background: #fff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 24px 48px rgba(0,0,0,0.15);
        color: var(--text-primary);
    }
    .modal-custom .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 24px;
    }
    .modal-custom .modal-body { padding: 24px; }
    .modal-custom .modal-title { font-weight: 800; color: var(--text-primary); }
    .modal-custom .btn-close { opacity: 0.5; }
    .modal-custom .table {
        color: var(--text-primary);
        border-color: #f1f5f9;
    }
    .modal-custom .table thead th {
        background: #f8fafc;
        border-color: #f1f5f9;
        color: var(--text-secondary);
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .modal-custom .table td { border-color: #f1f5f9; }
    .modal-custom .table-striped tbody tr:nth-of-type(odd) { background: #fafbfc; }
    .modal-custom .pagination .page-link {
        background: #f1f5f9;
        border: none;
        color: var(--text-secondary);
        border-radius: 8px !important;
        margin: 0 2px;
        font-weight: 600;
    }
    .modal-custom .pagination .page-item.active .page-link {
        background: var(--primary);
        color: #fff;
        box-shadow: 0 4px 12px rgba(79,70,229,0.3);
    }
    .modal-custom .form-control {
        background: #f1f5f9;
        border: 2px solid transparent;
        color: var(--text-primary);
        border-radius: 10px;
    }
    .modal-custom .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79,70,229,0.1);
    }

    @media (max-width: 768px) {
        .reports-page { padding: 14px; }
        .stat-card .stat-value { font-size: 22px; }
        .stat-card .stat-icon { width: 44px; height: 44px; font-size: 18px; }
    }
</style>

<div class="reports-page">
    {{-- FILTER SECTION --}}
    <div class="glass-card filter-card">
        <form action="{{ route('System.Reports') }}" method="GET" class="row g-3 align-items-end position-relative">
            <div class="col-md-4">
                <label>Start Month</label>
                <input type="month" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label>End Month</label>
                <input type="month" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-filter flex-grow-1">
                    <i class="fas fa-filter me-2"></i>Apply Filter
                </button>
                <a href="{{ route('System.Reports.PDF', request()->all()) }}" class="btn" style="background:#dc2626;border:none;border-radius:12px;padding:10px 20px;font-weight:700;font-size:14px;color:#fff;transition:all 0.25s;display:inline-flex;align-items:center;gap:8px;text-decoration:none;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(220,38,38,0.3)'" onmouseout="this.style.transform='';this.style.boxShadow=''" target="_blank">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
                <a href="{{ route('System.Reports.PDF.BW', request()->all()) }}" class="btn" style="background:#374151;border:none;border-radius:12px;padding:10px 20px;font-weight:700;font-size:14px;color:#fff;transition:all 0.25s;display:inline-flex;align-items:center;gap:8px;text-decoration:none;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(55,65,81,0.3)'" onmouseout="this.style.transform='';this.style.boxShadow=''" target="_blank">
                    <i class="fas fa-file-alt"></i> B&amp;W
                </a>
                <a href="{{ route('System.Reports') }}" class="btn btn-reset">
                    <i class="fas fa-undo me-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-1">
                <div>
                    <div class="stat-label">Categories</div>
                    <div class="stat-value">{{ $categoryCount }}</div>
                    <div class="stat-sub">Total categories</div>
                </div>
                <div class="stat-icon" style="background:#eef2ff;color:#4f46e5;">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-2">
                <div>
                    <div class="stat-label">Subcategories</div>
                    <div class="stat-value">{{ $subcategoryCount }}</div>
                    <div class="stat-sub">Total subcategories</div>
                </div>
                <div class="stat-icon" style="background:#f0f9ff;color:#0ea5e9;">
                    <i class="fas fa-sitemap"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-3">
                <div>
                    <div class="stat-label">Products</div>
                    <div class="stat-value">{{ $productCount }}</div>
                    <div class="stat-sub">Total products</div>
                </div>
                <div class="stat-icon" style="background:#fef2f2;color:#ef4444;">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-4">
                <div>
                    <div class="stat-label">Customers</div>
                    <div class="stat-value">{{ $customerscount }}</div>
                    <div class="stat-sub">Total customers</div>
                </div>
                <div class="stat-icon" style="background:#fffbeb;color:#f59e0b;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- FINANCIAL STAT CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-5">
                <div>
                    <div class="stat-label">Total Purchases</div>
                    <div class="stat-value" style="font-size:22px;">Rs {{ number_format($totalPurchases, 0) }}</div>
                    <div class="stat-sub">Purchase value</div>
                </div>
                <div class="stat-icon" style="background:#ecfdf5;color:#10b981;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-3">
                <div>
                    <div class="stat-label">Purchase Returns</div>
                    <div class="stat-value" style="font-size:22px;">Rs {{ number_format($totalPurchaseReturns, 0) }}</div>
                    <div class="stat-sub">Returned value</div>
                </div>
                <div class="stat-icon" style="background:#fef2f2;color:#ef4444;">
                    <i class="fas fa-undo-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-5">
                <div>
                    <div class="stat-label">Total Sales</div>
                    <div class="stat-value" style="font-size:22px;">Rs {{ number_format($totalSales, 0) }}</div>
                    <div class="stat-sub">Sale value</div>
                </div>
                <div class="stat-icon" style="background:#ecfdf5;color:#10b981;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="glass-card stat-card border-accent-6">
                <div>
                    <div class="stat-label">Sales Returns</div>
                    <div class="stat-value" style="font-size:22px;">Rs {{ number_format($totalSalesReturns, 0) }}</div>
                    <div class="stat-sub">Returned value</div>
                </div>
                <div class="stat-icon" style="background:#fff7ed;color:#f97316;">
                    <i class="fas fa-undo"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- INVENTORY VALUATION CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="glass-card stat-card border-accent-7" style="padding:28px 30px;">
                <div>
                    <div class="stat-label" style="font-size:13px;letter-spacing:0.7px;">
                        <i class="fas fa-tag me-1" style="color:#8b5cf6;"></i> Inventory Purchase Value
                    </div>
                    <div class="stat-value" style="font-size:30px;margin-top:6px;">
                        Rs {{ number_format($inventoryPurchaseValue, 0) }}
                    </div>
                    <div class="stat-sub" style="margin-top:4px;">
                        Total stock valued at <strong>purchase / cost price</strong>
                    </div>
                </div>
                <div class="stat-icon" style="width:64px;height:64px;border-radius:20px;font-size:26px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);color:#7c3aed;">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass-card stat-card border-accent-8" style="padding:28px 30px;">
                <div>
                    <div class="stat-label" style="font-size:13px;letter-spacing:0.7px;">
                        <i class="fas fa-store me-1" style="color:#ec4899;"></i> Inventory Retail Value
                    </div>
                    <div class="stat-value" style="font-size:30px;margin-top:6px;">
                        Rs {{ number_format($inventoryRetailValue, 0) }}
                    </div>
                    <div class="stat-sub" style="margin-top:4px;">
                        Total stock valued at <strong>retail / sale price</strong>
                    </div>
                </div>
                <div class="stat-icon" style="width:64px;height:64px;border-radius:20px;font-size:26px;background:linear-gradient(135deg,#fce7f3,#fbcfe8);color:#be185d;">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="glass-card chart-container">
                <div class="chart-header">
                    <div>
                        <div class="chart-title"><i class="fas fa-chart-line me-2" style="color:var(--primary);"></i>Sales Report</div>
                        <div class="chart-sub">Revenue overview for selected period</div>
                    </div>
                    <select id="salesFilter" class="chart-filter-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div class="chart-wrapper">
                    <div id="salesReportChart" style="height:380px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="glass-card chart-container">
                <div class="chart-header">
                    <div>
                        <div class="chart-title"><i class="fas fa-chart-bar me-2" style="color:#10b981;"></i>Purchase Report</div>
                        <div class="chart-sub">Procurement spending overview</div>
                    </div>
                    <select id="purchaseFilter" class="chart-filter-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div class="chart-wrapper">
                    <div id="purchaseReportChart" style="height:380px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="glass-card chart-container">
                <div class="chart-header">
                    <div>
                        <div class="chart-title"><i class="fas fa-cubes me-2" style="color:var(--accent-2);"></i>Category Wise Products</div>
                        <div class="chart-sub">Click on a bar to view products</div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div id="categoryStockChart" style="height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="glass-card chart-container">
                <div class="chart-header">
                    <div>
                        <div class="chart-title"><i class="fas fa-file-invoice me-2" style="color:#f59e0b;"></i>Account Wise Expense</div>
                        <div class="chart-sub">Expense distribution by account head</div>
                    </div>
                </div>
                <div>
                    <select id="expenseHeadSelect" class="chart-filter-select w-100 mb-3">
                        <option value="">Select Account Head</option>
                        @foreach($expenseChartData as $hid => $head)
                        <option value="{{ $hid }}">{{ $head['head_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="chart-wrapper">
                    <div id="expenseAccountChart" style="height:330px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORY PRODUCTS MODAL --}}
<div class="modal fade" id="categoryProductsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="productSearch" class="form-control" placeholder="Search product..." onkeyup="searchProducts()">
                    </div>
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody"></tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ===================== SALES CHART =====================
    const salesChartStats = @json($salesChartStats);
    let salesChart;

    function getSalesChartOpts(type = 'daily') {
        const data = salesChartStats[type];
        return {
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: true, tools: { download: true, zoom: false, pan: false } },
                foreColor: '#94a3b8',
                background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 800, animateGradually: { enabled: true, delay: 150 } }
            },
            series: data.series,
            xaxis: {
                categories: data.categories,
                labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 600 },
                    formatter: val => 'Rs ' + val.toLocaleString()
                }
            },
            dataLabels: { enabled: false },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 8,
                    distributed: false
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    gradientToColors: ['#818cf8'],
                    stops: [0, 100]
                }
            },
            colors: ['#4f46e5'],
            tooltip: {
                theme: 'light',
                y: { formatter: val => 'Rs ' + val.toLocaleString() }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            }
        };
    }

    function renderSalesChart(type = 'daily') {
        if (salesChart) salesChart.destroy();
        salesChart = new ApexCharts(document.querySelector("#salesReportChart"), getSalesChartOpts(type));
        salesChart.render();
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderSalesChart();
        document.getElementById('salesFilter')?.addEventListener('change', function() {
            renderSalesChart(this.value);
        });
    });

    // ===================== PURCHASE CHART =====================
    const purchaseChartStats = @json($purchaseChartStats);
    let purchaseChart;

    function getPurchaseChartOpts(type = 'daily') {
        const data = purchaseChartStats[type];
        return {
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: true, tools: { download: true, zoom: false, pan: false } },
                foreColor: '#94a3b8',
                background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            series: data.series,
            xaxis: {
                categories: data.categories,
                labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 600 },
                    formatter: val => 'Rs ' + val.toLocaleString()
                }
            },
            dataLabels: { enabled: false },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    borderRadius: 8
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    gradientToColors: ['#34d399'],
                    stops: [0, 100]
                }
            },
            colors: ['#10b981'],
            tooltip: {
                theme: 'light',
                y: { formatter: val => 'Rs ' + val.toLocaleString() }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            }
        };
    }

    function renderPurchaseChart(type = 'daily') {
        if (purchaseChart) purchaseChart.destroy();
        purchaseChart = new ApexCharts(document.querySelector("#purchaseReportChart"), getPurchaseChartOpts(type));
        purchaseChart.render();
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderPurchaseChart();
        document.getElementById('purchaseFilter')?.addEventListener('change', function() {
            renderPurchaseChart(this.value);
        });
    });

    // ===================== CATEGORY STOCK CHART =====================
    const categoryStockData = @json($categoryProductChart);

    document.addEventListener('DOMContentLoaded', function() {
        const options = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: { show: false },
                foreColor: '#94a3b8',
                background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 800 },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        const categoryId = categoryStockData.category_ids[config.dataPointIndex];
                        const categoryName = categoryStockData.categories[config.dataPointIndex];
                        loadCategoryProducts(categoryId, categoryName);
                    }
                }
            },
            series: categoryStockData.series,
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadius: 8,
                    distributed: true
                }
            },
            xaxis: {
                categories: categoryStockData.categories,
                labels: { style: { colors: '#64748b', fontSize: '12px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 600 },
                    formatter: val => val.toLocaleString()
                }
            },
            colors: ['#4f46e5', '#0ea5e9', '#ec4899', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#6366f1'],
            dataLabels: { enabled: false },
            tooltip: {
                theme: 'light',
                y: { formatter: val => val.toLocaleString() + ' Products' }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } }
            }
        };

        new ApexCharts(document.querySelector("#categoryStockChart"), options).render();
    });

    // ===================== CATEGORY PRODUCTS MODAL =====================
    let activeCategoryId = null;
    let activeCategoryName = '';

    function loadCategoryProducts(categoryId, categoryName, page = 1) {
        activeCategoryId = categoryId;
        activeCategoryName = categoryName;
        $('#modalTitle').text(categoryName + ' – Products');
        $('#categoryProductsModal').modal('show');
        let search = $('#productSearch').val();

        $.get(`/category-products/${categoryId}`, { page, search }, function(res) {
            let rows = '';
            res.data.forEach((item, index) => {
                rows += `<tr>
                    <td>${((res.current_page - 1) * 100) + index + 1}</td>
                    <td>${item.item_name}</td>
                    <td>${item.stock}</td>
                </tr>`;
            });
            $('#productsTableBody').html(rows);
            let pagination = '';
            for (let i = 1; i <= res.last_page; i++) {
                pagination += `<li class="page-item ${i === res.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="loadCategoryProducts(${categoryId}, '${categoryName}', ${i})">${i}</a>
                </li>`;
            }
            $('#pagination').html(pagination);
        });
    }

    function searchProducts() {
        loadCategoryProducts(activeCategoryId, activeCategoryName, 1);
    }

    // ===================== EXPENSE CHART =====================
    let expenseChart;
    const expenseData = @json($expenseChartData);

    document.getElementById('expenseHeadSelect')?.addEventListener('change', function() {
        const headId = this.value;
        if (!headId) return;
        const data = expenseData[headId];
        const options = {
            chart: {
                type: 'bar',
                height: 330,
                toolbar: { show: true, tools: { download: true } },
                foreColor: '#94a3b8',
                background: 'transparent',
                animations: { enabled: true, easing: 'easeinout', speed: 800 }
            },
            series: data.series,
            xaxis: {
                categories: data.categories,
                labels: { style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontWeight: 600 },
                    formatter: val => 'Rs ' + val.toLocaleString()
                }
            },
            dataLabels: { enabled: false },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: '55%'
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'horizontal',
                    gradientToColors: ['#fbbf24'],
                    stops: [0, 100]
                }
            },
            colors: ['#f59e0b'],
            tooltip: {
                theme: 'light',
                y: { formatter: val => 'Rs ' + val.toLocaleString() }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            }
        };
        if (expenseChart) expenseChart.destroy();
        expenseChart = new ApexCharts(document.querySelector("#expenseAccountChart"), options);
        expenseChart.render();
    });
</script>
@endsection