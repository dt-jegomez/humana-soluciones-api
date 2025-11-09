<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('city');
            $table->string('address');
            $table->unsignedTinyInteger('bedrooms');
            $table->unsignedTinyInteger('bathrooms');
            $table->string('consignation_type', 10);
            $table->decimal('rent_price', 14, 2)->nullable();
            $table->decimal('sale_price', 14, 2)->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('consignation_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
