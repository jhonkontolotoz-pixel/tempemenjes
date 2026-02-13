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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('restrict');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('payment_method', ['cash', 'card', 'transfer', 'check'])->default('cash');
            $table->decimal('payment_amount', 12, 2);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'completed', 'canceled'])->default('draft');
            $table->text('notes')->nullable();
            $table->json('draft_data')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_number');
            $table->index('customer_id');
            $table->index('cashier_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
