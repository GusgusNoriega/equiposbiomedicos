<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_parameter_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_parameter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_parameter_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('value_text')->nullable();
            $table->decimal('value_number', 16, 4)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'product_parameter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_parameter_values');
    }
};
