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
        Schema::table('orders', function (Blueprint $table) {
            // Update status enum to include 'Delivered'
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Delivered'])->default('Pending')->change();
            
            // Remove unused fields that are now handled by measurement_items
            $table->dropColumn(['order_type', 'quantity', 'price_per_item', 'completed_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert status enum
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending')->change();
            
            // Add back the removed columns
            $table->enum('order_type', ['Shirt', 'Jersey', 'Coat Up', 'Coat Down', 'Uniform', 'PE Uniform Up', 'PE Uniform Down'])->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_item', 10, 2)->default(0);
            $table->date('completed_date')->nullable();
        });
    }
};