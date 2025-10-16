<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_id')->constrained('measurements')->onDelete('cascade');
            $table->enum('item_type', [
                'Shirt', 'Jersey', 'Jersey Short', 'Coat Up', 'Coat Down', 'Uniform', 'PE Uniform Up', 'PE Uniform Down'
            ]);
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_item', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurement_items');
    }
};


