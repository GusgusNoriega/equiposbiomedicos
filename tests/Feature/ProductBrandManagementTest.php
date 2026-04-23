<?php

namespace Tests\Feature;

use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductBrandManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_product_brand_with_logo(): void
    {
        Storage::fake('local');

        $response = $this->actingAs($this->adminUser())->post(route('admin.product-brands.store'), [
            'name' => 'Philips',
            'code' => 'philips',
            'description' => 'Marca lider para monitoreo y diagnostico.',
            'is_active' => '1',
            'logo' => UploadedFile::fake()->image('philips-logo.png'),
        ]);

        $response->assertRedirect(route('admin.product-brands.index'));

        $brand = ProductBrand::query()->firstOrFail();

        $this->assertSame('Philips', $brand->name);
        $this->assertTrue($brand->hasLogo());
        Storage::disk('local')->assertExists($brand->logo_path);
    }

    public function test_product_creation_uses_the_selected_brand_catalog_entry(): void
    {
        $brand = ProductBrand::query()->create([
            'name' => 'Mindray',
            'code' => 'mindray',
            'description' => 'Marca de prueba.',
            'is_active' => true,
        ]);

        $category = ProductCategory::query()->create([
            'name' => 'Monitorizacion',
            'code' => 'monitorizacion',
            'description' => 'Categoria de prueba.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser())->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'Monitor de signos vitales',
            'code' => 'monitor-signos-vitales',
            'sku' => 'MSV-100',
            'model' => 'uMEC12',
            'manufacturer' => 'Mindray Bio-Medical',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Monitor de signos vitales',
            'brand_id' => $brand->id,
            'brand' => 'Mindray',
        ]);
    }

    private function adminUser(): User
    {
        $role = Role::query()
            ->where('code', 'administrador-biomedico')
            ->firstOrFail();

        return User::factory()->create([
            'role_id' => $role->id,
        ]);
    }
}
