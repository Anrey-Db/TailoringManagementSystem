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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Foreign key reference to customers
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');

            // Foreign key to measurement (optional, if order is linked to specific measurements)
            $table->foreignId('measurement_id')->nullable()->constrained('measurements')->onDelete('set null');

            // Order details
            $table->string('order_number')->unique();
            $table->enum('order_type', ['Shirt', 'Jersey', 'Coat Up', 'Coat Down', 'Uniform', 'PE Uniform Up', 'PE Uniform Down'])->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price_per_item', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            // Status and dates
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            $table->date('order_date')->default(now());
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();

            // Payment info
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('payment_status', ['Unpaid', 'Partial', 'Paid'])->default('Unpaid');

            // Notes for tailor or cashier
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
