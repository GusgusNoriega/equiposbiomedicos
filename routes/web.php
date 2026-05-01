<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductAssetController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductParameterController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ProductAssetController as SiteProductAssetController;
use App\Http\Controllers\Site\ProductController as SiteProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/productos/{product}/imagen-destacada', [SiteProductAssetController::class, 'featured'])
    ->whereNumber('product')
    ->name('site.products.featured-image');

Route::get('/productos/imagenes/{productImage}', [SiteProductAssetController::class, 'image'])
    ->whereNumber('productImage')
    ->name('site.product-images.show');

Route::get('/productos/adjuntos/{productAttachment}', [SiteProductAssetController::class, 'attachment'])
    ->whereNumber('productAttachment')
    ->name('site.product-attachments.download');

Route::get('/productos/{product:code}', [SiteProductController::class, 'show'])
    ->name('site.products.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->name('login.attempt');
});

Route::post('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::view('/', 'admin.dashboard')->name('dashboard');

    Route::resource('usuarios', UserController::class)
        ->except('show')
        ->names('users')
        ->parameters(['usuarios' => 'user']);

    Route::resource('roles', RoleController::class)
        ->except('show')
        ->names('roles');

    Route::resource('permisos', PermissionController::class)
        ->except('show')
        ->names('permissions')
        ->parameters(['permisos' => 'permission']);

    Route::resource('productos-biomedicos', ProductController::class)
        ->except('show')
        ->names('products')
        ->parameters(['productos-biomedicos' => 'product']);

    Route::get('productos-biomedicos/{product}/imagen-destacada', [ProductAssetController::class, 'featured'])
        ->name('products.featured-image');

    Route::get('imagenes-productos/{productImage}', [ProductAssetController::class, 'image'])
        ->name('product-images.show');

    Route::get('adjuntos-productos/{productAttachment}', [ProductAssetController::class, 'attachment'])
        ->name('product-attachments.download');

    Route::get('marcas-productos/{productBrand}/logo', [ProductBrandController::class, 'logo'])
        ->name('product-brands.logo');

    Route::resource('marcas-productos', ProductBrandController::class)
        ->except('show')
        ->names('product-brands')
        ->parameters(['marcas-productos' => 'productBrand']);

    Route::resource('categorias-productos', ProductCategoryController::class)
        ->except('show')
        ->names('product-categories')
        ->parameters(['categorias-productos' => 'productCategory']);

    Route::resource('parametros-productos', ProductParameterController::class)
        ->except('show')
        ->names('product-parameters')
        ->parameters(['parametros-productos' => 'productParameter']);
});
