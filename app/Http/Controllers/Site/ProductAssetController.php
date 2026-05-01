<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductAttachment;
use App\Models\ProductImage;
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

    public function image(ProductImage $productImage): StreamedResponse
    {
        abort_unless($productImage->product?->is_active && $productImage->path, Response::HTTP_NOT_FOUND);

        return Storage::disk($productImage->disk ?: 'local')->response(
            $productImage->path,
            $productImage->original_name ?: basename($productImage->path),
            [
                'Content-Type' => $productImage->mime_type ?: 'application/octet-stream',
                'Cache-Control' => 'public, max-age=86400',
            ],
        );
    }

    public function attachment(ProductAttachment $productAttachment): StreamedResponse
    {
        abort_unless($productAttachment->product?->is_active && $productAttachment->path, Response::HTTP_NOT_FOUND);

        return Storage::disk($productAttachment->disk ?: 'local')->download(
            $productAttachment->path,
            $productAttachment->original_name ?: basename($productAttachment->path),
            [
                'Content-Type' => $productAttachment->mime_type ?: 'application/octet-stream',
            ],
        );
    }
}
