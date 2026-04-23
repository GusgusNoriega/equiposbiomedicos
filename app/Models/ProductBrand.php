<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'code',
    'description',
    'logo_disk',
    'logo_path',
    'logo_name',
    'logo_mime_type',
    'logo_size',
    'is_active',
])]
class ProductBrand extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'logo_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (ProductBrand $productBrand): void {
            if ($productBrand->logo_path) {
                Storage::disk($productBrand->logo_disk ?: 'local')->delete($productBrand->logo_path);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function hasLogo(): bool
    {
        return filled($this->logo_path);
    }
}
