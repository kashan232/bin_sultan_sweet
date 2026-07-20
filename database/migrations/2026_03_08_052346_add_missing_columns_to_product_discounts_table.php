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
        Schema::table('product_discounts', function (Blueprint $table) {
            if (!Schema::hasColumn('product_discounts', 'discount_code')) {
                $table->string('discount_code', 20)->unique()->after('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_discounts', function (Blueprint $table) {
            if (Schema::hasColumn('product_discounts', 'discount_code')) {
                $table->dropColumn('discount_code');
            }
        });
    }
};
