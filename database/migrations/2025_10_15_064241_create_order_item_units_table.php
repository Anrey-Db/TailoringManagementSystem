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
        Schema::create('order_item_units', function (Blueprint $table) {
            $table->id();

            // Link to order item
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');

            // Optional link to measurement baseline
            $table->foreignId('measurement_id')->nullable()->constrained('measurements')->onDelete('set null');

            // Per person info
            $table->string('person_name')->nullable();
            $table->string('size_label')->nullable(); // e.g., S, M, L or numeric

            // Key jersey measurements (extendable)
            $table->decimal('chest', 5, 2)->nullable();
            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hip', 5, 2)->nullable();
            $table->decimal('length', 5, 2)->nullable();

            // Unit price override (optional) and computed total
            $table->decimal('unit_price', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_units');
    }
};
