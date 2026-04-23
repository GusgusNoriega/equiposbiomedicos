<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'product_parameter_id',
    'product_parameter_unit_id',
    'value_text',
    'value_number',
    'sort_order',
])]
class ProductParameterValue extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'value_number' => 'decimal:4',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(ProductParameter::class, 'product_parameter_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(ProductParameterUnit::class, 'product_parameter_unit_id');
    }
}
