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
    ];

    protected $casts = [
        'id' => 'integer',
        'seller_id' => 'integer',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
