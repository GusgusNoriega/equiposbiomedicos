<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'description', 'value_type', 'is_filterable', 'is_active', 'sort_order'])]
class ProductParameter extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_filterable' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductParameterUnit::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductParameterValue::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
