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
        // 1. Raw Materials Table
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->nullable();
            $table->string('name');
            $table->string('unit')->default('KG');
            $table->decimal('stock_qty', 12, 3)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('alert_qty', 12, 3)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Raw Material Vendors Table
        Schema::create('raw_material_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('closing_balance', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Raw Material Purchases Table
        Schema::create('raw_material_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_no')->unique();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->date('purchase_date');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('extra_cost', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('raw_material_vendors')->onDelete('set null');
        });

        // 4. Raw Material Purchase Items Table
        Schema::create('raw_material_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_material_purchase_id');
            $table->unsignedBigInteger('raw_material_id');
            $table->string('unit')->nullable();
            $table->decimal('qty', 12, 3)->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('raw_material_purchase_id', 'rm_pi_purchase_fk')->references('id')->on('raw_material_purchases')->onDelete('cascade');
            $table->foreign('raw_material_id', 'rm_pi_material_fk')->references('id')->on('raw_materials')->onDelete('cascade');
        });

        // 5. Raw Material Vendor Ledgers Table
        Schema::create('raw_material_vendor_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->date('date');
            $table->string('description');
            $table->string('reference_no')->nullable();
            $table->string('type')->default('purchase'); // opening_balance, purchase, payment
            $table->decimal('credit', 12, 2)->default(0); // Bill amount (+)
            $table->decimal('debit', 12, 2)->default(0);  // Payment amount (-)
            $table->decimal('running_balance', 12, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('raw_material_vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_vendor_ledgers');
        Schema::dropIfExists('raw_material_purchase_items');
        Schema::dropIfExists('raw_material_purchases');
        Schema::dropIfExists('raw_material_vendors');
        Schema::dropIfExists('raw_materials');
    }
};
