<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->string('city', 100);
            $table->text('address');
            $table->enum('property_type', [
                'house', 'apartment', 'commercial', 'plot', 'office'
            ])->default('house');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->decimal('area_size', 10, 2)->nullable();
            $table->enum('area_unit', ['marla', 'kanal', 'sq_ft', 'sq_yard'])->default('sq_ft');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'city']);
            $table->index(['property_type', 'price']);
            $table->index('seller_id');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};