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
        Schema::dropIfExists(ProductReview::getDb());
        Schema::create(ProductReview::getDb(), function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('status')->default(1);
            $table->string('name');
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->string('email')->nullable();
            $table->text('admin_comment')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }
};
