<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'category_id',
    'brand_id',
    'name',
    'code',
    'sku',
    'stock_actual',
    'brand',
    'model',
    'manufacturer',
    'short_description',
    'description',
    'featured_image_disk',
    'featured_image_path',
    'featured_image_name',
    'featured_image_mime_type',
    'featured_image_size',
    'is_active',
])]
class Product extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'stock_actual' => 'integer',
            'featured_image_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Product $product): void {
            if ($product->featured_image_path) {
                Storage::disk($product->featured_image_disk ?: 'local')->delete($product->featured_image_path);
            }

            $product->images()->each->delete();
            $product->attachments()->each->delete();
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function productBrand(): BelongsTo
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id');
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ProductAttachment::class)
            ->orderBy('id');
    }

    public function parameterValues(): HasMany
    {
        return $this->hasMany(ProductParameterValue::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function hasFeaturedImage(): bool
    {
        return filled($this->featured_image_path);
    }
}
