<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductDiscount;
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
// use App\Models\Size;
use Carbon\Carbon;
use Milon\Barcode\DNS1D;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function searchProducts(Request $request)
    {
        $q = $request->get('q');

        $products = Product::with('brand')->where(function ($query) use ($q) {
            $query->where('item_name', 'like', "%{$q}%")
                ->orWhere('item_code', 'like', "%{$q}%")
                ->orWhere('barcode_path', 'like', "%{$q}%");
        })->get();

        return response()->json($products);
    }
    // public function searchProducts(Request $request)
    // {
    //     $q = $request->get('q');

    //     $products = Product::with(['brand', 'activeDiscount'])
    //         ->whereHas('activeDiscount') // only products with active discount
    //         ->where(function ($query) use ($q) {
    //             $query->where('item_name', 'like', "%{$q}%")
    //                   ->orWhere('item_code', 'like', "%{$q}%")
    //                   ->orWhere('barcode_path', 'like', "%{$q}%");
    //         })
    //         ->get();

    //     return response()->json($products);
    // }


    public function product(Request $request)
    {
        $search = $request->search;

        $products = Product::with([
            'category_relation',
            'sub_category_relation',
            'unit',
            'brand',
            'stock',
            'discountProduct'
        ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('item_name', 'like', "%{$search}%")
                        ->orWhere('item_code', 'like', "%{$search}%")
                        ->orWhere('barcode_path', 'like', "%{$search}%")
                        ->orWhereHas('brand', function ($b) use ($search) {
                            $b->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('category_relation', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('item_code', 'desc')
            ->paginate(100);


        // 🔧 THIS LINE FIXES EVERYTHING
        $categories = Category::orderBy('id', 'desc')->get();
        if ($request->ajax()) {
            return view('admin_panel.product.index', compact('products', 'categories'))->render();
        }
        return view('admin_panel.product.index', compact('products', 'categories'));
    }





    public function view_store()
    {
        $categories = Category::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        return view('admin_panel.product.create', compact('categories', 'units', 'brands'));
    }

    public function getSubcategories($category_id)
    {
        $subcategories = SubCategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }
    public function generateBarcode(Request $request)
    {
        // normalize to exactly 6 digits if provided
        $candidate = null;
        if ($request->filled('code')) {
            $digits   = preg_replace('/\D+/', '', $request->query('code')); // keep only digits
            $digits   = substr($digits, 0, 6);
            $candidate = str_pad($digits, 6, '0', STR_PAD_LEFT);             // ensure 6 digits
        }

        $maxRetries = 10;
        $code = $candidate;

        for ($i = 0; $i < $maxRetries; $i++) {
            if (!$code || $this->codeExists($code)) {
                // either not provided OR collision found → generate new
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                if ($this->codeExists($code)) {
                    $code = null; // loop again
                    continue;
                }
            }
            // unique mil gaya
            break;
        }

        if (!$code || $this->codeExists($code)) {
            return response()->json([
                'message' => 'Could not generate a unique 6-digit barcode. Please try again.'
            ], 409);
        }

        // Barcode image (CODE128 recommended; C39 bhi chalega)
        $png = (new \Milon\Barcode\DNS1D)->getBarcodePNG($code, 'C128', 2, 50);
        $barcodeImage = 'data:image/png;base64,' . $png;

        return response()->json([
            'barcode_number' => $code,
            'barcode_image'  => $barcodeImage,
        ]);
    }

    /** Check uniqueness across products & discounts */
    private function codeExists(string $code): bool
    {
        return Product::where('barcode_path', $code)->exists()
            || ProductDiscount::where('discount_code', $code)->exists();
    }





    public function store_product(Request $request)
    {
        if (!Auth::id()) {
            return redirect()->back();
        }
        $userId = Auth::id();

        // basic validation
        $request->validate([
            'product_name'   => 'required|string|max:255|unique:products,item_name',
            'category_id'    => 'nullable|integer',
            'barcode_path'    => 'nullable|unique:products,barcode_path',
            'sub_category_id' => 'nullable|integer',
            'unit_type'      => 'required|string|in:kg,piece,pound',
        ]);

        // Generate next item code
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $nextCode = 'ITEM-0001';
        if ($lastProduct) {
            $lastId = $lastProduct->id + 1;
            $nextCode = 'ITEM-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        }

        // Image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $imagePath = $filename;
        }

        // Normalize fields
        $categoryId = $request->input('category_id') ? (int)$request->input('category_id') : null;
        $subCategoryId = $request->input('sub_category_id') ? (int)$request->input('sub_category_id') : null;

        $brandInput = $request->input('brand_id');
        if (is_array($brandInput)) {
            $brandInput = reset($brandInput);
        }
        $brandId = $brandInput !== null ? (int)$brandInput : null;

        try {
            DB::beginTransaction();

            $product = Product::create([
                'creater_id'      => $userId,
                'category_id'     => $categoryId,
                'sub_category_id' => $subCategoryId,
                'item_code'       => $nextCode,
                'item_name'       => $request->input('product_name'),
                'barcode_path'    => $request->input('barcode_path') ?? rand(100000000000, 999999999999),
                'unit_id'         => $request->input('unit'),
                'unit_type'       => $request->input('unit_type'),
                'brand_id'        => $brandId,
                'wholesale_price' => 0,
                'price'           => 0,
                'initial_stock'   => 0,
                'alert_quantity'  => $request->input('alert_quantity') ? (int)$request->input('alert_quantity') : 0,
                'note'            => $request->input('note'),
                'image'           => $imagePath,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // --- Save Product Variants ---
            $variantNames = $request->input('variant_name', []);
            $variantSizeValues = $request->input('variant_size_value', []);
            $variantSizeUnits = $request->input('variant_size_unit', []);
            $variantPrices = $request->input('variant_price', []);
            $variantWholesalePrices = $request->input('variant_wholesale_price', []);
            $variantCostPrices = $request->input('variant_cost_price', []);
            $variantStocks = $request->input('variant_stock', []);
            $variantDefault = $request->input('variant_default', 0); // index of default variant

            $totalStock = 0;
            $defaultPrice = 0;
            $defaultWholesale = 0;

            if (!empty($variantNames)) {
                foreach ($variantNames as $index => $vName) {
                    if (empty($vName)) continue;

                    $sizeValue = floatval($variantSizeValues[$index] ?? 0);
                    $sizeUnit = $variantSizeUnits[$index] ?? $request->input('unit_type');

                    // If unit is KG, user enters Grams in form, so convert to KG for DB
                    $dbSizeValue = $sizeValue;
                    $sizeLabel = $vName;
                    
                    if ($sizeUnit === 'kg') {
                        $dbSizeValue = $sizeValue / 1000; // Convert Grams to KG for storage
                        $sizeLabel = $vName . ' (' . number_format($dbSizeValue, 3) . ' KG)';
                    }

                    $vPrice = floatval($variantPrices[$index] ?? 0);
                    $vCost = floatval($variantCostPrices[$index] ?? 0);
                    $vStock = floatval($variantStocks[$index] ?? 0);
                    $isDefault = ((int)$variantDefault === $index);

                    $variant = ProductVariant::create([
                        'product_id'      => $product->id,
                        'variant_name'    => $vName,
                        'size_label'      => $sizeLabel,
                        'size_value'      => $dbSizeValue,
                        'size_unit'       => $sizeUnit,
                        'price'           => $vPrice,
                        'wholesale_price' => 0, // Field removed from form
                        'cost_price'      => $vCost,
                        'stock_qty'       => $vStock,
                        'alert_quantity'  => 0,
                        'is_default'      => $isDefault,
                        'is_active'       => true,
                    ]);

                    // Single variant stock entry
                    DB::table('stocks')->insert([
                        'branch_id'    => 1,
                        'warehouse_id' => 1,
                        'product_id'   => $product->id,
                        'variant_id'   => $variant->id,
                        'qty'          => $vStock,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);

                    $totalStock += $vStock;
                    if ($isDefault) {
                        $defaultPrice = $vPrice;
                    }
                }

                // Update product with default variant price
                $product->update([
                    'price'           => $defaultPrice,
                    'wholesale_price' => 0,
                    'initial_stock'   => $totalStock,
                ]);
            }

            // Normal product without variants
            if (empty($variantNames) && $product->initial_stock > 0) {
                DB::table('stocks')->insert([
                    'branch_id'    => 1,
                    'warehouse_id' => 1,
                    'product_id'   => $product->id,
                    'variant_id'   => null,
                    'qty'          => $product->initial_stock,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            DB::commit();
            return redirect('Product')->with('success', 'Product created successfully with variants!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }





    public function update(Request $request, $id)
    {
        $product_id = $id;
        $userId = auth()->id();
        $imageFilename = null;

        // Image handling (store only filename to stay consistent with create)
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $imageFilename = $imageName; // ONLY filename
        } else {
            $imageFilename = Product::where('id', $product_id)->value('image');
        }

        // Update product table
        Product::where('id', $product_id)->update([
            'creater_id'      => $userId,
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'item_code'       => $request->item_code,
            'item_name'       => $request->product_name,
            'barcode_path'    => $request->barcode_path ?? rand(100000000000, 999999999999),
            'unit_id'         => $request->unit,
            'initial_stock'   => $request->Stock,
            'brand_id'        => $request->brand_id,
            'wholesale_price' => $request->wholesale_price,
            'price'           => $request->retail_price,
            'note'            => $request->note,
            'alert_quantity'  => $request->alert_quantity,
            'image'           => $imageFilename,
            'updated_at'      => now(),
        ]);

        // ===== Update or Insert to stocks table =====
        // Determine branch & warehouse (use request or defaults)
        $branchId = $request->branch_id ?? 1;
        $warehouseId = $request->warehouse_id ?? 1;
        $newQty = (int) $request->Stock; // sanitize

        // Try to update existing stock row for this product + branch + warehouse
        $updated = DB::table('stocks')
            ->where('product_id', $product_id)
            ->where('branch_id', $branchId)
            ->where('warehouse_id', $warehouseId)
            ->update([
                'qty' => $newQty,
                'updated_at' => now(),
            ]);

        // If update affected 0 rows, insert a new stock row (only if qty > 0 or if you want to keep zeros too)
        if (!$updated) {
            DB::table('stocks')->insert([
                'branch_id'    => $branchId,
                'warehouse_id' => $warehouseId,
                'product_id'   => $product_id,
                'qty'          => $newQty,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function edit($id)
    {

        $product = Product::with('category_relation', 'sub_category_relation', 'unit', 'brand')->findOrFail($id);

        // dd($product->toArray());
        $categories = Category::all();


        $subcategories = SubCategory::all();
        $brands = Brand::all();
        return view('admin_panel.product.edit', compact('product', 'categories', 'subcategories', 'brands'));
    }

    // Add function in ProductController.php
    public function barcode($id)
    {
        $product = Product::with('activeDiscount')->findOrFail($id);
        return view('admin_panel.product.barcode', compact('product'));
    }

    // public function searchProducts(Request $request)
    // {
    //     $query = $request->get('q');

    //     \Log::info("Search query: " . $query); // Debug log

    //     $products = Product::where('item_name', 'like', '%' . $query . '%')
    //         ->get(['id', 'item_name', 'item_code', 'retail_price', 'uom', 'measurement', 'unit']);

    //     if ($products->isEmpty()) {
    //         return response()->json(['message' => 'Product not found'], 404);
    //     }

    //     $products = $products->map(function ($product) {
    //         return [
    //             'id' => $product->id,
    //             'name' => $product->item_name,
    //             'code' => $product->item_code,
    //             'price' => $product->retail_price,
    //             'uom' => $product->uom,
    //             'measurement' => $product->measurement,
    //             'unit' => $product->unit,
    //         ];
    //     });

    //     return response()->json($products);
    // }


}
