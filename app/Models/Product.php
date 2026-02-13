<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'description',
        'price',
        'cost',
        'stock',
        'min_stock',
        'status',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock')
                     ->where('stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', 0);
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= ($this->min_stock ?? 10);
    }

    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) return 'out_of_stock';
        if ($this->isLowStock()) return 'low_stock';
        return 'in_stock';
    }
}