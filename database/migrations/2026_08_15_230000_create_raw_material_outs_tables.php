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
        // 1. Raw Material Outs (Delivery Challans / Issuance)
        Schema::create('raw_material_outs', function (Blueprint $table) {
            $table->id();
            $table->string('issue_no')->unique();
            $table->date('out_date');
            $table->string('location'); // e.g. Kitchen, Bakery Section, Sweet Factory
            $table->string('taken_by'); // Kon le kr ja rha he (Staff / Chef name)
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // 2. Raw Material Out Items
        Schema::create('raw_material_out_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_material_out_id');
            $table->unsignedBigInteger('raw_material_id');
            $table->string('unit')->nullable();
            $table->decimal('qty', 12, 3)->default(0);
            $table->string('item_note')->nullable();
            $table->timestamps();

            $table->foreign('raw_material_out_id', 'rm_out_item_fk')->references('id')->on('raw_material_outs')->onDelete('cascade');
            $table->foreign('raw_material_id', 'rm_out_material_fk')->references('id')->on('raw_materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_out_items');
        Schema::dropIfExists('raw_material_outs');
    }
};
