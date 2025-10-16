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
        Schema::table('payments', function (Blueprint $table) {
            // Make amount field nullable since we're using amount_paid
            $table->decimal('amount', 10, 2)->nullable()->change();
            
            // Also make method field nullable since we're using payment_method
            $table->enum('method', ['Cash', 'GCash'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert amount field to not nullable
            $table->decimal('amount', 10, 2)->nullable(false)->change();
            
            // Revert method field to not nullable
            $table->enum('method', ['Cash', 'GCash'])->nullable(false)->change();
        });
    }
};