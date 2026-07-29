<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('purchase_return_items') && !Schema::hasColumn('purchase_return_items', 'variant_id')) {
            Schema::table('purchase_return_items', function (Blueprint $table) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('purchase_return_items') && Schema::hasColumn('purchase_return_items', 'variant_id')) {
            Schema::table('purchase_return_items', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }
    }
};
