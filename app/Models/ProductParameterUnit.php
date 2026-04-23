<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['product_parameter_id', 'name', 'symbol', 'code', 'sort_order'])]
class ProductParameterUnit extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(ProductParameter::class, 'product_parameter_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductParameterValue::class);
    }
}
