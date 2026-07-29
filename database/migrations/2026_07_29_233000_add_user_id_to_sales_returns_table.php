<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('sales_returns') && !Schema::hasColumn('sales_returns', 'user_id')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('sale_id');
            });

            // Backfill user_id from sales table if sales table exists
            if (Schema::hasTable('sales') && Schema::hasColumn('sales', 'user_id')) {
                DB::statement('UPDATE sales_returns sr JOIN sales s ON sr.sale_id = s.id SET sr.user_id = s.user_id');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sales_returns') && Schema::hasColumn('sales_returns', 'user_id')) {
            Schema::table('sales_returns', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
