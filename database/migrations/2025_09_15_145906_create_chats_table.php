<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('inquiry_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['active', 'closed', 'blocked'])->default('active');
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->unique(['property_id', 'buyer_id']); // One chat per buyer per property
            $table->index(['buyer_id', 'status']);
            $table->index(['seller_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};