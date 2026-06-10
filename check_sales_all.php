<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sales = DB::table('sales')->get();

$mismatches = [];
$total_net_sum = 0;
$calc_sum = 0;

foreach ($sales as $s) {
    $total_net_sum += $s->total_net;
    $calc = floatval($s->cash) + floatval($s->card) - floatval($s->change);
    $calc_sum += $calc;
    
    // allow a small floating point difference
    if (abs($s->total_net - $calc) > 0.1) {
        $mismatches[] = [
            'id' => $s->id,
            'invoice_no' => $s->invoice_no,
            'total_net' => $s->total_net,
            'cash' => $s->cash,
            'card' => $s->card,
            'change' => $s->change,
            'diff' => $calc - $s->total_net,
            'order_type' => $s->order_type ?? null,
            'booking_id' => $s->booking_id ?? null
        ];
    }
}

echo "Found " . count($mismatches) . " mismatches.\n";
echo "First 10 mismatches:\n";
print_r(array_slice($mismatches, 0, 10));
