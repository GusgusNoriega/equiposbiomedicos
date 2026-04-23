<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('featured_image_disk')->nullable()->after('description');
            $table->string('featured_image_path')->nullable()->after('featured_image_disk');
            $table->string('featured_image_name')->nullable()->after('featured_image_path');
            $table->string('featured_image_mime_type')->nullable()->after('featured_image_name');
            $table->unsignedBigInteger('featured_image_size')->nullable()->after('featured_image_mime_type');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image_disk',
                'featured_image_path',
                'featured_image_name',
                'featured_image_mime_type',
                'featured_image_size',
            ]);
        });
    }
};
