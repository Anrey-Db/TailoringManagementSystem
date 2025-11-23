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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign key reference to orders table
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Item details
            $table->enum('item_type', [
                'Shirt',
                'Jersey',
                'Jersey Short',
                'Coat Up',
                'Coat Down',
                'Uniform',
                'PE Uniform Up',
                'PE Uniform Down'
            ]);
            
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_item', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            
            // Optional: Link to specific measurement
            $table->foreignId('measurement_id')->nullable()->constrained('measurements')->onDelete('set null');
            
            // Additional details
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
