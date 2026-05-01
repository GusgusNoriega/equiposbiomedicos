<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->is_active, Response::HTTP_NOT_FOUND);

        $product->load([
            'attachments',
            'category:id,name,description',
            'images',
            'parameterValues.parameter:id,name,code,value_type',
            'parameterValues.unit:id,name,symbol',
            'productBrand:id,name,description',
            'specifications',
        ]);

        $gallerySlides = collect();

        if ($product->hasFeaturedImage()) {
            $gallerySlides->push([
                'src' => route('site.products.featured-image', $product),
                'alt' => 'Imagen principal de ' . $product->name,
                'label' => 'Principal',
            ]);
        }

        foreach ($product->images as $image) {
            $gallerySlides->push([
                'src' => route('site.product-images.show', $image),
                'alt' => $image->original_name ?: 'Imagen de ' . $product->name,
                'label' => 'Galeria',
            ]);
        }

        if ($gallerySlides->isEmpty()) {
            $gallerySlides->push([
                'src' => asset('branding/site/hero-monitor.svg'),
                'alt' => 'Ilustracion de equipo biomedico',
                'label' => 'Referencia',
            ]);
        }

        $relatedProducts = Product::query()
            ->where('is_active', true)
            ->whereKeyNot($product->id)
            ->where(function ($query) use ($product): void {
                $query->where('category_id', $product->category_id);

                if ($product->brand_id) {
                    $query->orWhere('brand_id', $product->brand_id);
                }
            })
            ->with(['category:id,name', 'productBrand:id,name'])
            ->latest('id')
            ->limit(4)
            ->get();

        return view('products.show', [
            'companyName' => 'Equipos Biomedicos y Servicios',
            'gallerySlides' => $gallerySlides,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
