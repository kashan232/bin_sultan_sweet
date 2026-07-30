<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $usertype = Auth::user()->usertype;
        $userId = Auth::id();

        if ($usertype == 'user') {
            return view('user_panel.dashboard', compact('userId'));
        } elseif ($usertype == 'admin') {
            return view('admin_panel.dashboard');
        } else {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
    }

    private function getReportData($startDate, $endDate)
    {
        $categoryCount = DB::table('categories')->count();
        $subcategoryCount = DB::table('subcategories')->count();
        $productCount = DB::table('products')->count();
        $customerscount = DB::table('customers')->count();

        $totalPurchases = 0;
        $totalPurchaseReturns = 0;
        $totalSales = 0;
        $totalSalesReturns = 0;

        $salesChartStats = [
            'daily' => ['categories' => [], 'series' => []],
            'weekly' => ['categories' => [], 'series' => []],
            'monthly' => ['categories' => [], 'series' => []]
        ];
        $purchaseChartStats = [
            'daily' => ['categories' => [], 'series' => []],
            'weekly' => ['categories' => [], 'series' => []],
            'monthly' => ['categories' => [], 'series' => []]
        ];

        $salesData = [];
        $purchaseData = [];
        $labels = [];

        if ($startDate && $endDate) {
            $startObj = Carbon::parse($startDate)->startOfMonth();
            $endObj   = Carbon::parse($endDate)->endOfMonth();

            $start = $startObj->format('Y-m-d 00:00:00');
            $end   = $endObj->format('Y-m-d 23:59:59');

            $purchasesQuery = DB::table('purchases')
                ->whereBetween('purchase_date', [$startObj->format('Y-m-d'), $endObj->format('Y-m-d')]);
            $purchaseReturnsQuery = DB::table('purchase_returns')
                ->whereBetween('return_date', [$startObj->format('Y-m-d'), $endObj->format('Y-m-d')]);

            $salesQuery = DB::table('sales')
                ->leftJoin('customers', 'sales.customer', '=', 'customers.id')
                ->whereBetween('sales.created_at', [$start, $end])
                ->where(function ($q) {
                    $q->where('sales.customer', 'Walk-in Customer')
                        ->orWhere('customers.customer_category', 'Walking Customer')
                        ->orWhere('customers.customer_category', 'Retailer');
                });

            $salesReturnsQuery = DB::table('sales_returns')
                ->leftJoin('customers', 'sales_returns.customer', '=', 'customers.id')
                ->whereBetween('sales_returns.created_at', [$start, $end])
                ->where(function ($q) {
                    $q->where('sales_returns.customer', 'Walk-in Customer')
                        ->orWhere('customers.customer_category', 'Walking Customer')
                        ->orWhere('customers.customer_category', 'Retailer');
                });

            $totalPurchases = $purchasesQuery->sum('net_amount');
            $totalPurchaseReturns = $purchaseReturnsQuery->sum('net_amount');
            $totalSales = $salesQuery->sum('sales.total_net');
            $totalSalesReturns = $salesReturnsQuery->sum('sales_returns.total_net');

            $chartStart = $startObj;
            $chartEnd   = $endObj;
            $diffInDays = $chartStart->diffInDays($chartEnd);

            $granularity = 'daily';
            if ($diffInDays > 90) {
                $granularity = 'monthly';
            } elseif ($diffInDays > 14) {
                $granularity = 'weekly';
            }

            $getSalesData = function($selectRaw) use ($start, $end) {
                return DB::table('sales')
                    ->leftJoin('customers', 'sales.customer', '=', 'customers.id')
                    ->whereBetween('sales.created_at', [$start, $end])
                    ->where(function ($sub) {
                        $sub->where('sales.customer', 'Walk-in Customer')
                            ->orWhere('customers.customer_category', 'Walking Customer')
                            ->orWhere('customers.customer_category', 'Retailer');
                    })
                    ->select(DB::raw("$selectRaw as label_key"), DB::raw('SUM(sales.total_net) as total'))
                    ->groupBy('label_key')->orderBy('label_key')->pluck('total', 'label_key');
            };

            $getPurchaseData = function($selectRaw) use ($startObj, $endObj) {
                 return DB::table('purchases')
                    ->whereBetween('purchase_date', [$startObj->format('Y-m-d'), $endObj->format('Y-m-d')])
                    ->select(DB::raw("$selectRaw as label_key"), DB::raw('SUM(net_amount) as total'))
                    ->groupBy('label_key')->orderBy('label_key')->pluck('total', 'label_key');
            };

            if ($granularity === 'daily') {
                $period = \Carbon\CarbonPeriod::create($chartStart, $chartEnd);
                $salesMap = $getSalesData('DATE(sales.created_at)');
                $purchaseMap = $getPurchaseData('DATE(purchase_date)');
                foreach ($period as $dt) {
                    $key = $dt->format('Y-m-d');
                    $labels[] = $dt->format('d M (D)');
                    $salesData[] = $salesMap[$key] ?? 0;
                    $purchaseData[] = $purchaseMap[$key] ?? 0;
                }
            } elseif ($granularity === 'weekly') {
                $salesMap = $getSalesData('YEARWEEK(sales.created_at, 1)');
                $purchaseMap = $getPurchaseData('YEARWEEK(purchase_date, 1)');
                $current = $chartStart->copy()->startOfWeek();
                $endWeek = $chartEnd->copy()->endOfWeek();
                while ($current <= $endWeek) {
                    $dbKey = $current->format('oW');
                    $labels[] = "Week " . $current->weekOfYear . " - " . $current->format('M Y');
                    $salesData[] = $salesMap[$dbKey] ?? 0;
                    $purchaseData[] = $purchaseMap[$dbKey] ?? 0;
                    $current->addWeek();
                }
            } else {
                $salesMap = $getSalesData("DATE_FORMAT(sales.created_at, '%Y-%m')");
                $purchaseMap = $getPurchaseData("DATE_FORMAT(purchase_date, '%Y-%m')");
                $current = $chartStart->copy()->startOfMonth();
                $endMonth = $chartEnd->copy()->endOfMonth();
                while ($current <= $endMonth) {
                    $key = $current->format('Y-m');
                    $labels[] = $current->format('F Y');
                    $salesData[] = $salesMap[$key] ?? 0;
                    $purchaseData[] = $purchaseMap[$key] ?? 0;
                    $current->addMonth();
                }
            }

            $salesChartStats['daily'] = ['categories' => $labels, 'series' => [['name' => 'Sales', 'data' => $salesData]]];
            $purchaseChartStats['daily'] = ['categories' => $labels, 'series' => [['name' => 'Purchases', 'data' => $purchaseData]]];
            $salesChartStats['weekly'] = $salesChartStats['daily'];
            $salesChartStats['monthly'] = $salesChartStats['daily'];
            $purchaseChartStats['weekly'] = $purchaseChartStats['daily'];
            $purchaseChartStats['monthly'] = $purchaseChartStats['daily'];
        }

        $categoryProductData = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.id', 'categories.name as category_name', DB::raw('COUNT(products.id) as total_products'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_products')
            ->get();

        $categoryProductChart = [
            'categories'   => $categoryProductData->pluck('category_name'),
            'category_ids' => $categoryProductData->pluck('id'),
            'series' => [['name' => 'Total Products', 'data' => $categoryProductData->pluck('total_products')]]
        ];

        $lowStockData = DB::table('products')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select('products.id', 'products.item_code', 'products.item_name', DB::raw('COALESCE(stocks.qty, 0) as qty'), 'products.alert_quantity')
            ->whereRaw('COALESCE(stocks.qty, 0) <= products.alert_quantity')
            ->get();
        $lowStockChart = [
            'categories' => $lowStockData->pluck('item_name'),
            'series' => [
                ['name' => 'Current Stock', 'data' => $lowStockData->pluck('qty')],
                ['name' => 'Alert Level', 'data' => $lowStockData->pluck('alert_quantity')],
            ]
        ];

        $categorySubData = DB::table('categories')
            ->leftJoin('subcategories', 'categories.id', '=', 'subcategories.category_id')
            ->leftJoin('products', 'subcategories.id', '=', 'products.sub_category_id')
            ->select('categories.name as category_name', DB::raw('COUNT(DISTINCT subcategories.id) as sub_count'), DB::raw('COUNT(DISTINCT products.id) as product_count'))
            ->groupBy('categories.name')
            ->get();
        $categorySubChart = [
            'categories' => $categorySubData->pluck('category_name'),
            'series' => [
                ['name' => 'Subcategories', 'data' => $categorySubData->pluck('sub_count')],
                ['name' => 'Products', 'data' => $categorySubData->pluck('product_count')],
            ]
        ];

        $expenseChartData = [];
        if ($startDate && $endDate) {
             $startObj = Carbon::parse($startDate)->startOfMonth();
             $endObj   = Carbon::parse($endDate)->endOfMonth();
             $expenseRaw = DB::table('expense_vouchers')
                ->join('accounts', 'expense_vouchers.party_id', '=', 'accounts.id')
                ->join('account_heads', 'accounts.head_id', '=', 'account_heads.id')
                ->whereBetween('expense_vouchers.created_at', [$startObj->format('Y-m-d 00:00:00'), $endObj->format('Y-m-d 23:59:59')])
                ->select('account_heads.id as head_id', 'account_heads.name as head_name', 'accounts.title as account_name', DB::raw('SUM(expense_vouchers.total_amount) as total_expense'))
                ->groupBy('account_heads.id', 'account_heads.name', 'accounts.title')
                ->get()
                ->groupBy('head_id');

            foreach ($expenseRaw as $headId => $rows) {
                $expenseChartData[$headId] = [
                    'head_name' => $rows->first()->head_name,
                    'categories' => $rows->pluck('account_name'),
                    'series' => [['name' => 'Expense', 'data' => $rows->pluck('total_expense')]]
                ];
            }
        }

        // ── Inventory Valuation ────────────────────────────────────────────────
        // Products with variants: sum(stock.qty * variant.cost_price / variant.price)
        $variantInventoryPurchase = DB::table('stocks')
            ->join('product_variants', 'stocks.variant_id', '=', 'product_variants.id')
            ->whereNotNull('stocks.variant_id')
            ->sum(DB::raw('stocks.qty * product_variants.cost_price'));

        $variantInventoryRetail = DB::table('stocks')
            ->join('product_variants', 'stocks.variant_id', '=', 'product_variants.id')
            ->whereNotNull('stocks.variant_id')
            ->sum(DB::raw('stocks.qty * product_variants.price'));

        // Products without variants: stock.qty * price (KG: qty in grams, price per kg → /1000)
        $simpleInventoryPurchase = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->whereNull('stocks.variant_id')
            ->sum(DB::raw('CASE WHEN products.unit_type = "kg" THEN (stocks.qty / 1000.0) * COALESCE(products.wholesale_price, 0) ELSE stocks.qty * COALESCE(products.wholesale_price, 0) END'));

        $simpleInventoryRetail = DB::table('stocks')
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->whereNull('stocks.variant_id')
            ->sum(DB::raw('CASE WHEN products.unit_type = "kg" THEN (stocks.qty / 1000.0) * COALESCE(products.price, 0) ELSE stocks.qty * COALESCE(products.price, 0) END'));

        $inventoryPurchaseValue = (float)$variantInventoryPurchase + (float)$simpleInventoryPurchase;
        $inventoryRetailValue   = (float)$variantInventoryRetail   + (float)$simpleInventoryRetail;

        return compact(
            'categoryCount', 'subcategoryCount', 'productCount', 'customerscount',
            'totalPurchases', 'totalPurchaseReturns', 'totalSales', 'totalSalesReturns',
            'salesChartStats', 'purchaseChartStats',
            'categoryProductChart', 'lowStockChart', 'categorySubChart', 'expenseChartData',
            'labels', 'salesData', 'purchaseData',
            'inventoryPurchaseValue', 'inventoryRetailValue'
        );
    }

    public function System_Reports(Request $request)
    {
        $data = $this->getReportData($request->start_date, $request->end_date);
        return view('admin_panel.system_reports', $data);
    }

    public function System_Reports_PDF(Request $request)
    {
        $data = $this->getReportData($request->start_date, $request->end_date);
        $data['startDate'] = $request->start_date;
        $data['endDate'] = $request->end_date;
        $pdf = Pdf::loadView('admin_panel.system_reports_pdf', $data);
        return $pdf->download('System_Reports_' . date('Y-m-d') . '.pdf');
    }

    public function System_Reports_PDF_BW(Request $request)
    {
        $data = $this->getReportData($request->start_date, $request->end_date);
        $data['startDate'] = $request->start_date;
        $data['endDate'] = $request->end_date;
        $pdf = Pdf::loadView('admin_panel.system_reports_pdf_bw', $data);
        return $pdf->download('System_Reports_BW_' . date('Y-m-d') . '.pdf');
    }

    public function categoryProducts(Request $request, $id)
    {
        $search = $request->search;

        $products = DB::table('products')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(
                'products.id',
                'products.item_name',
                DB::raw('COALESCE(SUM(stocks.qty),0) as stock')
            )
            ->where('products.category_id', $id)
            ->when($search, function ($q) use ($search) {
                $q->where('products.item_name', 'like', "%{$search}%");
            })
            ->groupBy('products.id', 'products.item_name')
            ->orderByDesc('stock')
            ->paginate(100);

        return response()->json($products);
    }
}
