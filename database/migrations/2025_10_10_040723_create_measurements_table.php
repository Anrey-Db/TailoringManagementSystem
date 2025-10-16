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
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();

            // Foreign key reference to customers table
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Type of clothing
            $table->enum('measurement_type', [
                'Shirt',
                'Jersey',
                'Jersey Short',
                'Coat Up',
                'Coat Down',
                'Uniform',
                'PE Uniform Up',
                'PE Uniform Down'
            ]);

            // ====== Shirt Measurements ======
            $table->decimal('shirt_neck', 5, 2)->nullable();
            $table->decimal('shirt_shoulder', 5, 2)->nullable();
            $table->decimal('shirt_chest', 5, 2)->nullable();
            $table->decimal('shirt_waist', 5, 2)->nullable();
            $table->decimal('shirt_sleeve_length', 5, 2)->nullable();
            $table->decimal('shirt_length', 5, 2)->nullable();

            // ====== Jersey Measurements ======
            $table->decimal('jersey_neck', 5, 2)->nullable();
            $table->decimal('jersey_shoulder', 5, 2)->nullable();
            $table->decimal('jersey_chest', 5, 2)->nullable();
            $table->decimal('jersey_waist', 5, 2)->nullable();
            $table->decimal('jersey_length', 5, 2)->nullable();

            // ====== Jersey Short Measurements ======
            $table->decimal('jersey_short_waist', 5, 2)->nullable();
            $table->decimal('jersey_short_hip', 5, 2)->nullable();
            $table->decimal('jersey_short_length', 5, 2)->nullable();
            $table->decimal('jersey_short_bottom', 5, 2)->nullable();

            // ====== Coat (Up) Measurements ======
            $table->decimal('coat_shoulder', 5, 2)->nullable();
            $table->decimal('coat_body', 5, 2)->nullable();
            $table->decimal('coat_waist', 5, 2)->nullable();
            $table->decimal('coat_armhole', 5, 2)->nullable();
            $table->decimal('coat_sleeve_length', 5, 2)->nullable();
            $table->decimal('coat_length', 5, 2)->nullable();

            // ====== Coat (Down) Measurements ======
            $table->decimal('coat_pants_waist', 5, 2)->nullable();
            $table->decimal('coat_pants_hip', 5, 2)->nullable();
            $table->decimal('coat_pants_legs', 5, 2)->nullable();
            $table->decimal('coat_pants_length', 5, 2)->nullable();
            $table->decimal('coat_pants_bottom', 5, 2)->nullable();

            // ====== Uniform Measurements ======
            $table->decimal('uniform_shoulder', 5, 2)->nullable();
            $table->decimal('uniform_chest', 5, 2)->nullable();
            $table->decimal('uniform_waist', 5, 2)->nullable();
            $table->decimal('uniform_hip', 5, 2)->nullable();
            $table->decimal('uniform_length', 5, 2)->nullable();
            $table->decimal('uniform_sleeve_length', 5, 2)->nullable();
            $table->decimal('uniform_pants_length', 5, 2)->nullable();
            $table->decimal('uniform_pants_waist', 5, 2)->nullable();
            $table->decimal('uniform_pants_hip', 5, 2)->nullable();
            $table->decimal('uniform_pants_bottom', 5, 2)->nullable();

            // ====== PE Uniform (Up) Measurements ======
            $table->decimal('pe_shoulder', 5, 2)->nullable();
            $table->decimal('pe_chest', 5, 2)->nullable();
            $table->decimal('pe_waist', 5, 2)->nullable();
            $table->decimal('pe_sleeve_length', 5, 2)->nullable();
            $table->decimal('pe_length', 5, 2)->nullable();

            // ====== PE Uniform (Down) Measurements ======
            $table->decimal('pe_pants_waist', 5, 2)->nullable();
            $table->decimal('pe_pants_hip', 5, 2)->nullable();
            $table->decimal('pe_pants_length', 5, 2)->nullable();
            $table->decimal('pe_pants_bottom', 5, 2)->nullable();

            // Optional remarks
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
