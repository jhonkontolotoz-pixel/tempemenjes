<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for product_reviews table
 * 
 * Run: php artisan make:migration create_product_reviews_table
 * Then replace with this code
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null');
            
            // Review data
            $table->integer('rating'); // 1-5 stars
            $table->string('title', 100);
            $table->text('comment');
            
            // Status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Helpful votes
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('product_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('rating');
            $table->index('created_at');
            
            // Prevent duplicate reviews from same customer
            $table->unique(['product_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};