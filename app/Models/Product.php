<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'price',
        'stock',
        'status',
        'description',
        'rating_avg',
        'sold_count',
        'image_url',
    ];

    protected $casts = [
        'id' => 'integer',
        'seller_id' => 'integer',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'rating_avg' => 'decimal:2',
        'sold_count' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
