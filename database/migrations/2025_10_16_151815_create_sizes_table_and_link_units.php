<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Shirt, Uniform, PE Uniform, Jersey, Coat
            $table->string('label');    // e.g., XS, S, 34, etc.
            $table->timestamps();
            $table->unique(['category', 'label']);
        });

        // Seed default sizes
        $seed = [
            'Shirt' => ['XS','S','M','L','XL','2XL','3XL'],
            'Uniform' => ['XS','S','M','L','XL','2XL','3XL'],
            'PE Uniform' => ['XS','S','M','L','XL','2XL','3XL'],
            'Jersey' => ['XS','S','M','L','XL','2XL'],
            'Coat' => ['34','36','38','40','42','44','46'],
        ];
        foreach ($seed as $category => $labels) {
            foreach ($labels as $label) {
                DB::table('sizes')->insert(['category' => $category, 'label' => $label, 'created_at' => now(), 'updated_at' => now()]);
            }
        }

        Schema::table('measurement_item_units', function (Blueprint $table) {
            $table->foreignId('size_id')->nullable()->after('size_label')->constrained('sizes')->nullOnDelete();
        });

        // Backfill size_id using size_label and the parent measurement item's type
        DB::statement(
            "UPDATE measurement_item_units mu
             JOIN measurement_items mi ON mi.id = mu.measurement_item_id
             LEFT JOIN sizes s ON s.category = mi.item_type AND s.label = mu.size_label
             SET mu.size_id = s.id"
        );
    }

    public function down(): void
    {
        // Drop FK column first
        Schema::table('measurement_item_units', function (Blueprint $table) {
            if (Schema::hasColumn('measurement_item_units', 'size_id')) {
                $table->dropForeign(['size_id']);
                $table->dropColumn('size_id');
            }
        });
        Schema::dropIfExists('sizes');
    }
};
