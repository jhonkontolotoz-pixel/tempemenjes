<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ProductReview Model
 * 
 * Handles product reviews and ratings from customers.
 * 
 * Features:
 * - Customer reviews
 * - Star ratings (1-5)
 * - Review text
 * - Approved/pending status
 * - Helpful votes
 */
class ProductReview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'customer_id',
        'transaction_id',
        'rating',
        'title',
        'comment',
        'status',
        'helpful_count',
        'not_helpful_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that owns the review.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the customer that wrote the review.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the transaction related to this review.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Scope to get approved reviews.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get pending reviews.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get rejected reviews.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope to filter by rating.
     */
    public function scopeRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope to get reviews ordered by helpful count.
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * Check if review is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if review is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark review as helpful.
     */
    public function markHelpful(): void
    {
        $this->increment('helpful_count');
    }

    /**
     * Mark review as not helpful.
     */
    public function markNotHelpful(): void
    {
        $this->increment('not_helpful_count');
    }

    /**
     * Approve the review.
     */
    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Reject the review.
     */
    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Get rating as stars (for display).
     */
    public function getStarsAttribute(): string
    {
        return str_repeat('⭐', $this->rating);
    }

    /**
     * Get helpful percentage.
     */
    public function getHelpfulPercentageAttribute(): float
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        
        if ($total === 0) {
            return 0;
        }

        return round(($this->helpful_count / $total) * 100, 1);
    }
}