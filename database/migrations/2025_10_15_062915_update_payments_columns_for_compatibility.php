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
        Schema::table('payments', function (Blueprint $table) {
            // Add new columns expected by the application if they don't exist
            if (!Schema::hasColumn('payments', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->enum('payment_method', ['Cash', 'GCash'])->nullable()->after('method');
            }
            if (!Schema::hasColumn('payments', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('payment_reference');
            }
        });

        // Backfill from existing columns if present
        if (Schema::hasColumn('payments', 'amount')) {
            DB::statement('UPDATE payments SET amount_paid = amount WHERE amount_paid IS NULL');
        }
        if (Schema::hasColumn('payments', 'method')) {
            DB::statement("UPDATE payments SET payment_method = method WHERE payment_method IS NULL");
        }
        if (Schema::hasColumn('payments', 'payment_reference')) {
            DB::statement('UPDATE payments SET reference_number = payment_reference WHERE reference_number IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'amount_paid')) {
                $table->dropColumn('amount_paid');
            }
            if (Schema::hasColumn('payments', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('payments', 'reference_number')) {
                $table->dropColumn('reference_number');
            }
        });
    }
};
