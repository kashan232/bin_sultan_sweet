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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'initial_stock')) {
                $table->text('initial_stock')->nullable();
            }
            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->text('wholesale_price')->nullable();
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->text('price')->nullable();
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image', 255)->nullable();
            }
            if (!Schema::hasColumn('products', 'unit_type')) {
                $table->string('unit_type')->nullable();
            }
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->unsignedBigInteger('brand_id')->nullable();
            }
            if (!Schema::hasColumn('products', 'barcode_path')) {
                $table->string('barcode_path', 255)->nullable();
            }
            if (!Schema::hasColumn('products', 'note')) {
                $table->text('note')->nullable();
            }
            if (!Schema::hasColumn('products', 'color')) {
                $table->text('color')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['wholesale_price', 'initial_stock', 'price', 'image', 'unit_type', 'brand_id', 'barcode_path', 'note', 'color'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
