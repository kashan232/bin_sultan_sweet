<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductionController extends Controller
{
    public function index()
    {
        $entries = DB::table('production_entries as pe')
            ->leftJoin('users as u', 'u.id', '=', 'pe.created_by')
            ->select('pe.*', 'u.name as user_name', 
                DB::raw('(SELECT COUNT(*) FROM production_entry_items WHERE production_entry_id = pe.id) as items_count')
            )
            ->orderBy('pe.created_at', 'desc')
            ->paginate(15);

        return view('admin_panel.production.index', compact('entries'));
    }

    public function create()
    {
        $products = Product::with('unit')->orderBy('item_name')->get();
        return view('admin_panel.production.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_date' => 'required|date',
            'product_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $entryId = DB::table('production_entries')->insertGetId([
                'entry_no' => 'PROD-' . date('Ymd-His'),
                'production_date' => $request->production_date,
                'source' => $request->source ?? 'kitchen',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->product_id as $index => $productId) {
                $qtyTyped = (float)$request->qty[$index];
                if ($qtyTyped <= 0) continue;

                $variantId = $request->variant_id[$index] ?? null;
                // Treat empty string variant as null
                if ($variantId === '') $variantId = null;

                $product = Product::with('unit')->find($productId);
                if (!$product) continue;

                $unitName = strtolower($product->unit->name ?? '');
                $prodName = strtolower($product->item_name);
                
                // Determine if it's a gram item (1 KG input -> 1000 Stock units)
                $isGram = str_contains($unitName, 'gram') || str_contains($unitName, 'gm') || 
                          str_contains($prodName, 'gram') || str_contains($prodName, ' gm') || 
                          $product->unit_type === 'kg';
                
                $qtyStock = $isGram ? ($qtyTyped * 1000) : $qtyTyped;

                DB::table('production_entry_items')->insert([
                    'production_entry_id' => $entryId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'unit' => $product->unit->name ?? 'Pc',
                    'qty_entered' => $qtyTyped,
                    'qty_stock' => $qtyStock,
                    'notes' => $request->item_note[$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update Stock
                $stockQuery = Stock::where('product_id', $productId)
                    ->where('branch_id', 1); // Default branch
                
                // If it's a KG item, always manage stock on the main product, ignore any variant selected (if somehow passed)
                if ($isGram) {
                    $stockQuery->whereNull('variant_id');
                    $variantId = null; // force null for main product stock
                } elseif ($variantId) {
                    $stockQuery->where('variant_id', $variantId);
                } else {
                    $stockQuery->whereNull('variant_id');
                }
                
                $stock = $stockQuery->first();

                if ($stock) {
                    $stock->qty += $qtyStock;
                    $stock->save();
                } else {
                    Stock::create([
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'branch_id' => 1,
                        'qty' => $qtyStock,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('production.index')->with('success', 'Production entry saved and stock updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $entry = DB::table('production_entries')->where('id', $id)->first();
        if (!$entry) abort(404);

        $items = DB::table('production_entry_items as pei')
            ->leftJoin('products as p', 'p.id', '=', 'pei.product_id')
            ->where('pei.production_entry_id', $id)
            ->select('pei.*', 'p.item_name', 'p.item_code', 'p.unit_type')
            ->get();

        $products = Product::with('unit')->orderBy('item_name')->get();

        return view('admin_panel.production.edit', compact('entry', 'items', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'production_date' => 'required|date',
            'product_id' => 'required|array',
            'qty' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // 1. Reverse old stock
            $oldItems = DB::table('production_entry_items')->where('production_entry_id', $id)->get();
            foreach ($oldItems as $oi) {
                $stockQuery = Stock::where('product_id', $oi->product_id)->where('branch_id', 1);
                if ($oi->variant_id) {
                    $stockQuery->where('variant_id', $oi->variant_id);
                } else {
                    $stockQuery->whereNull('variant_id');
                }
                $stock = $stockQuery->first();
                if ($stock) {
                    $stock->qty -= $oi->qty_stock;
                    $stock->save();
                }
            }

            // 2. Delete old items
            DB::table('production_entry_items')->where('production_entry_id', $id)->delete();

            // 3. Update entry header
            DB::table('production_entries')->where('id', $id)->update([
                'production_date' => $request->production_date,
                'source' => $request->source ?? 'kitchen',
                'notes' => $request->notes,
                'updated_at' => now(),
            ]);

            // 4. Insert new items + update stock (same as store)
            foreach ($request->product_id as $index => $productId) {
                $qtyTyped = (float)$request->qty[$index];
                if ($qtyTyped <= 0) continue;

                $variantId = $request->variant_id[$index] ?? null;
                if ($variantId === '') $variantId = null;

                $product = Product::with('unit')->find($productId);
                if (!$product) continue;

                $unitName = strtolower($product->unit->name ?? '');
                $prodName = strtolower($product->item_name);

                $isGram = str_contains($unitName, 'gram') || str_contains($unitName, 'gm') ||
                          str_contains($prodName, 'gram') || str_contains($prodName, ' gm') ||
                          $product->unit_type === 'kg';

                $qtyStock = $isGram ? ($qtyTyped * 1000) : $qtyTyped;

                DB::table('production_entry_items')->insert([
                    'production_entry_id' => $id,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'unit' => $product->unit->name ?? 'Pc',
                    'qty_entered' => $qtyTyped,
                    'qty_stock' => $qtyStock,
                    'notes' => $request->item_note[$index] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update Stock
                $stockQuery = Stock::where('product_id', $productId)->where('branch_id', 1);
                if ($isGram) {
                    $stockQuery->whereNull('variant_id');
                    $variantId = null;
                } elseif ($variantId) {
                    $stockQuery->where('variant_id', $variantId);
                } else {
                    $stockQuery->whereNull('variant_id');
                }

                $stock = $stockQuery->first();
                if ($stock) {
                    $stock->qty += $qtyStock;
                    $stock->save();
                } else {
                    Stock::create([
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'branch_id' => 1,
                        'qty' => $qtyStock,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('production.index')->with('success', 'Production entry updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    public function gatepass($id)
    {
        $entry = DB::table('production_entries as pe')
            ->leftJoin('users as u', 'u.id', '=', 'pe.created_by')
            ->where('pe.id', $id)
            ->select('pe.*', 'u.name as user_name')
            ->first();

        if (!$entry) abort(404);

        $items = DB::table('production_entry_items as pei')
            ->leftJoin('products as p', 'p.id', '=', 'pei.product_id')
            ->where('pei.production_entry_id', $id)
            ->select('pei.*', 'p.item_name', 'p.item_code', 'p.unit_type')
            ->get();

        return view('admin_panel.production.gatepass', compact('entry', 'items'));
    }
}
