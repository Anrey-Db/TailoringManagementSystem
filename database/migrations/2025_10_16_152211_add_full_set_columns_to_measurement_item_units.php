<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('measurement_item_units', function (Blueprint $table) {
            // Additional top-body details
            $table->decimal('shoulder', 5, 2)->nullable()->after('length');
            $table->decimal('sleeve', 5, 2)->nullable()->after('shoulder');
            // Lower-body details
            $table->decimal('inseam', 5, 2)->nullable()->after('sleeve');
            $table->decimal('outseam', 5, 2)->nullable()->after('inseam');
            $table->decimal('thigh', 5, 2)->nullable()->after('outseam');
            $table->decimal('leg_opening', 5, 2)->nullable()->after('thigh');
        });
    }

    public function down(): void
    {
        Schema::table('measurement_item_units', function (Blueprint $table) {
            $table->dropColumn(['shoulder','sleeve','inseam','outseam','thigh','leg_opening']);
        });
    }
};
