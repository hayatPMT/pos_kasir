<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'branch_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'buy_price',
        'sell_price',
        'member_price',
        'wholesale_price',
        'stock',
        'min_stock',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'buy_price' => 'decimal:2',
            'sell_price' => 'decimal:2',
            'member_price' => 'decimal:2',
            'wholesale_price' => 'decimal:2',
        ];
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function transactionDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockMovements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
