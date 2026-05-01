<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductParameter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_only_active_products_in_the_public_catalog(): void
    {
        $brand = $this->createBrand(['name' => 'Mindray', 'code' => 'mindray']);
        $category = $this->createCategory(['name' => 'Monitores', 'code' => 'monitores']);

        $this->createProduct($category, $brand, [
            'name' => 'Monitor actual',
            'code' => 'monitor-actual',
            'sku' => 'SKU-0001',
            'is_active' => true,
        ]);

        $this->createProduct($category, $brand, [
            'name' => 'Monitor oculto',
            'code' => 'monitor-oculto',
            'sku' => 'SKU-0002',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Monitor actual')
            ->assertDontSee('Monitor oculto')
            ->assertSee(route('site.products.show', ['product' => 'monitor-actual']), false)
            ->assertSee('name="marca"', false)
            ->assertSee('name="categoria"', false);
    }

    public function test_public_product_detail_shows_the_registered_product_file(): void
    {
        $brand = $this->createBrand(['name' => 'Drager', 'code' => 'drager']);
        $category = $this->createCategory(['name' => 'Ventiladores', 'code' => 'ventiladores']);
        $product = $this->createProduct($category, $brand, [
            'name' => 'Ventilador adulto neonatal',
            'code' => 'ventilador-adulto-neonatal',
            'sku' => 'VENT-1000',
            'model' => 'Savina 300',
            'manufacturer' => 'Dragerwerk',
            'short_description' => 'Equipo para soporte ventilatorio en areas criticas.',
            'description' => "Equipo biomedico con modos de ventilacion invasiva y no invasiva.\nIncluye ficha tecnica consultable.",
            'stock_actual' => 3,
        ]);

        $product->specifications()->create([
            'label' => 'Voltaje',
            'value' => '110',
            'unit' => 'V',
            'sort_order' => 10,
        ]);

        $parameter = ProductParameter::create([
            'name' => 'Peso',
            'code' => 'peso',
            'description' => 'Peso del equipo',
            'value_type' => 'number',
            'is_filterable' => true,
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $unit = $parameter->units()->create([
            'name' => 'Kilogramo',
            'symbol' => 'kg',
            'code' => 'kg',
            'sort_order' => 10,
        ]);

        $product->parameterValues()->create([
            'product_parameter_id' => $parameter->id,
            'product_parameter_unit_id' => $unit->id,
            'value_text' => '42',
            'value_number' => '42.0000',
            'sort_order' => 10,
        ]);

        $product->attachments()->create([
            'disk' => 'local',
            'path' => 'products/attachments/manual-ventilador.pdf',
            'original_name' => 'Manual ventilador.pdf',
            'mime_type' => 'application/pdf',
            'size' => 2048,
        ]);

        $response = $this->get(route('site.products.show', ['product' => $product->code]));

        $response
            ->assertOk()
            ->assertSee('Ventilador adulto neonatal')
            ->assertSee('Ventiladores')
            ->assertSee('Drager')
            ->assertSee('Savina 300')
            ->assertSee('Dragerwerk')
            ->assertSee('VENT-1000')
            ->assertSee('Equipo biomedico con modos de ventilacion invasiva y no invasiva.')
            ->assertSee('Voltaje')
            ->assertSee('110')
            ->assertSee('Peso')
            ->assertSee('42')
            ->assertSee('kg')
            ->assertSee('Manual ventilador.pdf');
    }

    public function test_public_product_detail_does_not_show_inactive_products(): void
    {
        $brand = $this->createBrand(['name' => 'Oculta', 'code' => 'oculta']);
        $category = $this->createCategory(['name' => 'Categoria oculta', 'code' => 'categoria-oculta']);
        $product = $this->createProduct($category, $brand, [
            'name' => 'Producto inactivo',
            'code' => 'producto-inactivo',
            'sku' => 'SKU-INACTIVO',
            'is_active' => false,
        ]);

        $this->get(route('site.products.show', ['product' => $product->code]))
            ->assertNotFound();
    }

    public function test_home_page_filters_products_by_brand_and_category(): void
    {
        $brandA = $this->createBrand(['name' => 'Brand A', 'code' => 'brand-a']);
        $brandB = $this->createBrand(['name' => 'Brand B', 'code' => 'brand-b']);
        $categoryA = $this->createCategory(['name' => 'Categoria A', 'code' => 'categoria-a']);
        $categoryB = $this->createCategory(['name' => 'Categoria B', 'code' => 'categoria-b']);

        $this->createProduct($categoryA, $brandA, [
            'name' => 'Producto filtrado',
            'code' => 'producto-filtrado',
            'sku' => 'SKU-0100',
        ]);

        $this->createProduct($categoryA, $brandB, [
            'name' => 'Producto otra marca',
            'code' => 'producto-otra-marca',
            'sku' => 'SKU-0101',
        ]);

        $this->createProduct($categoryB, $brandA, [
            'name' => 'Producto otra categoria',
            'code' => 'producto-otra-categoria',
            'sku' => 'SKU-0102',
        ]);

        $response = $this->get('/?marca=' . $brandA->id . '&categoria=' . $categoryA->id);

        $response
            ->assertOk()
            ->assertSee('Producto filtrado')
            ->assertDontSee('Producto otra marca')
            ->assertDontSee('Producto otra categoria');
    }

    public function test_home_page_paginates_catalog_after_eight_products(): void
    {
        $brand = $this->createBrand(['name' => 'Brand Catalogo', 'code' => 'brand-catalogo']);
        $category = $this->createCategory(['name' => 'Categoria Catalogo', 'code' => 'categoria-catalogo']);

        foreach (range(1, 9) as $index) {
            $this->createProduct($category, $brand, [
                'name' => sprintf('Producto catalogo %02d', $index),
                'code' => sprintf('producto-catalogo-%02d', $index),
                'sku' => sprintf('SKU-%04d', 200 + $index),
            ]);
        }

        $firstPage = $this->get('/');

        $firstPage
            ->assertOk()
            ->assertSee('Producto catalogo 09')
            ->assertSee('Producto catalogo 02')
            ->assertDontSee('Producto catalogo 01')
            ->assertSee('?page=2#portafolio', false);

        $secondPage = $this->get('/?page=2');

        $secondPage
            ->assertOk()
            ->assertSee('Producto catalogo 01')
            ->assertDontSee('Producto catalogo 09');
    }

    private function createBrand(array $attributes = []): ProductBrand
    {
        return ProductBrand::create(array_merge([
            'name' => 'Marca principal',
            'code' => 'marca-principal',
            'description' => 'Marca para pruebas',
            'is_active' => true,
        ], $attributes));
    }

    private function createCategory(array $attributes = []): ProductCategory
    {
        return ProductCategory::create(array_merge([
            'name' => 'Categoria principal',
            'code' => 'categoria-principal',
            'description' => 'Categoria para pruebas',
            'is_active' => true,
        ], $attributes));
    }

    private function createProduct(ProductCategory $category, ProductBrand $brand, array $attributes = []): Product
    {
        return Product::create(array_merge([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Producto demo',
            'code' => 'producto-demo',
            'sku' => 'SKU-DEMO',
            'brand' => $brand->name,
            'model' => 'Modelo demo',
            'manufacturer' => 'Fabricante demo',
            'short_description' => 'Descripcion corta para el producto publico.',
            'description' => 'Descripcion larga para el producto publico.',
            'stock_actual' => 5,
            'is_active' => true,
        ], $attributes));
    }
}
