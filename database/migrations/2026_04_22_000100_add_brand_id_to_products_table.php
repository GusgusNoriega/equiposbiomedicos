<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')
                ->nullable()
                ->after('sku')
                ->constrained('product_brands')
                ->restrictOnDelete();
        });

        $products = DB::table('products')
            ->whereNull('brand_id')
            ->whereNotNull('brand')
            ->orderBy('id')
            ->get(['id', 'brand']);

        $knownBrands = DB::table('product_brands')
            ->get(['id', 'name', 'code'])
            ->mapWithKeys(fn ($brand) => [Str::lower(trim((string) $brand->name)) => (int) $brand->id])
            ->all();

        $usedCodes = DB::table('product_brands')
            ->pluck('code')
            ->filter()
            ->values()
            ->all();

        foreach ($products as $product) {
            $name = trim((string) $product->brand);

            if ($name === '') {
                continue;
            }

            $key = Str::lower($name);

            if (! array_key_exists($key, $knownBrands)) {
                $baseCode = Str::slug($name);
                $baseCode = $baseCode !== '' ? $baseCode : 'marca';
                $code = $baseCode;
                $suffix = 2;

                while (in_array($code, $usedCodes, true)) {
                    $code = $baseCode . '-' . $suffix;
                    $suffix++;
                }

                $brandId = DB::table('product_brands')->insertGetId([
                    'name' => $name,
                    'code' => $code,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $knownBrands[$key] = $brandId;
                $usedCodes[] = $code;
            }

            DB::table('products')
                ->where('id', $product->id)
                ->update(['brand_id' => $knownBrands[$key]]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('brand_id');
        });
    }
};
