<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductAssetController extends Controller
{
    public function featured(Product $product): StreamedResponse
    {
        abort_unless($product->is_active && $product->featured_image_path, Response::HTTP_NOT_FOUND);

        return Storage::disk($product->featured_image_disk ?: 'local')->response(
            $product->featured_image_path,
            $product->featured_image_name ?: basename($product->featured_image_path),
            [
                'Content-Type' => $product->featured_image_mime_type ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ],
        );
    }
}
