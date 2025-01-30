<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Models\Product;

return new class extends Migration
{
    public function up()
    {
        Schema::create(ProductReview::getDb(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('rating');
            $table->integer('status')->default(1);
            $table->boolean('is_approved')->default(true);
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }
};
