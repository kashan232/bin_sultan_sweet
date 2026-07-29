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
        if (Schema::hasTable('sales_returns') && !Schema::hasColumn('sales_returns', 'variant_id')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->text('variant_id')->nullable()->after('product_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sales_returns') && Schema::hasColumn('sales_returns', 'variant_id')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }
    }
};
