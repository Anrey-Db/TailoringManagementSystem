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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Link to the orders table
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Optional link to customer
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');

            // Payment details
            $table->string('payment_reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('method', ['Cash', 'GCash'])->default('Cash');
            $table->date('payment_date')->default(now());

            // Status
            $table->enum('status', ['Pending', 'Completed', 'Refunded'])->default('Completed');

            // Remarks or notes
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
