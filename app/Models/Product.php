<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public const VOUCHER = 'voucher';
    public const EMONEY = 'emoney';
    public const GAME = 'game';
    public const DATA = 'data';
    public const INTERNATIONAL = 'international';
    public const STREAMING = 'streaming';
    public const ETOLL = 'etoll';
    public const PULSA = 'pulsa';
    public const BICARA = 'bicara';
    public const PLN = 'pln';
    public const EMETERAI = 'emeterai';
    public const PGN = 'pgn';

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
