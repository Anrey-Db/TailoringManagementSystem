<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize existing values first
        DB::table('measurement_items')
            ->whereIn('item_type', ['Coat Up', 'Coat Down'])
            ->update(['item_type' => 'Coat']);
        DB::table('measurement_items')
            ->whereIn('item_type', ['PE Uniform Up', 'PE Uniform Down'])
            ->update(['item_type' => 'PE Uniform']);
        DB::table('measurement_items')
            ->whereIn('item_type', ['Jersey Short'])
            ->update(['item_type' => 'Jersey']);

        // Alter enum to the simplified set
        Schema::table('measurement_items', function (Blueprint $table) {
            $table->enum('item_type', ['Shirt', 'Uniform', 'PE Uniform', 'Jersey', 'Coat'])
                  ->default('Shirt')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum to the previous extended set
        Schema::table('measurement_items', function (Blueprint $table) {
            $table->enum('item_type', [
                'Shirt', 'Jersey', 'Jersey Short', 'Coat Up', 'Coat Down', 'Uniform', 'PE Uniform Up', 'PE Uniform Down'
            ])->change();
        });
    }
};
