<?php

use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Database\Factories\ProductFactory;
use SmartCms\Store\Models\Product;
use SmartCms\Store\Resources\Product\ProductEntityResource;
use SmartCms\Store\Resources\Product\ProductResource;

it('injects reviews relation to product model', function () {
    $product = new Product;
    $relationship = $product->reviews();
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('injects rating to product dto', function () {
    $product = ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->create(['product_id' => $product->id]);
    $productDto = ProductResource::make($product)->get();
    expect($productDto)->toHaveProperty('rating');
    expect($productDto->rating)->toBeFloat();
});

it('injects rating to product entity dto', function () {
    $entity = ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->create(['product_id' => $entity->id]);
    $dto = ProductEntityResource::make($entity)->get();
    expect($dto)->toHaveProperty('rating');
    expect($dto->rating)->toBeFloat();
});

it('injects reviews to product entity dto', function () {
    $entity = ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->create(['product_id' => $entity->id, 'status' => 1]);
    $dto = ProductEntityResource::make($entity)->get();
    expect($dto)->toHaveProperty('reviews');
    expect($dto->reviews)->toBeArray();
    expect($dto->reviews)->toHaveCount(3);
});

it('properly calculate rating', function () {
    $entity = ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->state(['status' => 1])->create(['product_id' => $entity->id, 'rating' => 5]);
    $dto = ProductEntityResource::make($entity)->get();
    expect($dto)->toHaveProperty('rating');
    expect($dto->rating)->toBe((float) 5);
});

it('doesnt calculate rating for disabled reviews', function () {
    $entity = ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->state(['status' => 0])->create(['product_id' => $entity->id, 'rating' => 5]);
    $dto = ProductEntityResource::make($entity)->get();
    expect($dto)->toHaveProperty('rating');
    expect($dto->rating)->toBe((float) 0);
});

it('doesnt append disabled reviews to product entity dto', function () {
    $entity = ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->state(['status' => 0])->create(['product_id' => $entity->id]);
    $dto = ProductEntityResource::make($entity)->get();
    expect($dto)->toHaveProperty('reviews');
    expect($dto->reviews)->toBeArray();
    expect($dto->reviews)->toHaveCount(0);
});
