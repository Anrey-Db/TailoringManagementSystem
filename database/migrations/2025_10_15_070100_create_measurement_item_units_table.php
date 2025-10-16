<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurement_item_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('measurement_item_id')->constrained('measurement_items')->onDelete('cascade');
            $table->string('person_name')->nullable();
            $table->string('size_label')->nullable();
            $table->decimal('chest', 5, 2)->nullable();
            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hip', 5, 2)->nullable();
            $table->decimal('length', 5, 2)->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurement_item_units');
    }
};


