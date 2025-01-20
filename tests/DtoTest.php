<?php

use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Models\Product;
use SmartCms\Store\Repositories\Product\ProductEntityDto;
use SmartCms\Store\Repositories\Product\ProductRepository;

it('injects reviews relation to product model', function () {
    $product = new Product;
    $relationship = $product->reviews();
    expect($relationship)->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('injects rating to product dto', function () {
    $product = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->create(['product_id' => $product->id]);
    $productDto = ProductRepository::make()->find($product->id);
    expect($productDto)->toHaveProperty('rating');
    expect($productDto->rating)->toBeFloat();
});

it('injects rating to product entity dto', function () {
    $entity = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->create(['product_id' => $entity->id]);
    $dto = new ProductEntityDto($entity->id, $entity->name(), '', $entity->getBreadcrumbs(), $entity->images ?? [], $seo->summary ?? null, $seo->content ?? null, $entity->price, $entity->sale_price, false, '', [], [], $entity->sku);
    $dto = $dto->get();
    expect($dto)->toHaveProperty('rating');
    expect($dto->rating)->toBeFloat();
});

it('injects reviews to product entity dto', function () {
    $entity = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->create(['product_id' => $entity->id, 'is_approved' => true]);
    $dto = new ProductEntityDto($entity->id, $entity->name(), '', $entity->getBreadcrumbs(), $entity->images ?? [], $seo->summary ?? null, $seo->content ?? null, $entity->price, $entity->sale_price, false, '', [], [], $entity->sku);
    $dto = $dto->get();
    expect($dto)->toHaveProperty('reviews');
    expect($dto->reviews)->toBeArray();
    expect($dto->reviews)->toHaveCount(3);
});

it('properly calculate rating', function () {
    $entity = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->state(['is_approved' => true])->create(['product_id' => $entity->id, 'rating' => 5]);
    $dto = new ProductEntityDto($entity->id, $entity->name(), '', $entity->getBreadcrumbs(), $entity->images ?? [], $seo->summary ?? null, $seo->content ?? null, $entity->price, $entity->sale_price, false, '', [], [], $entity->sku);
    $dto = $dto->get();
    expect($dto)->toHaveProperty('rating');
    expect($dto->rating)->toBe((float) 5);
});

it('doesnt calculate rating for not aprroved reviews', function () {
    $entity = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->state(['is_approved' => false])->create(['product_id' => $entity->id, 'rating' => 5]);
    $dto = new ProductEntityDto($entity->id, $entity->name(), '', $entity->getBreadcrumbs(), $entity->images ?? [], $seo->summary ?? null, $seo->content ?? null, $entity->price, $entity->sale_price, false, '', [], [], $entity->sku);
    $dto = $dto->get();
    expect($dto)->toHaveProperty('rating');
    expect($dto->rating)->toBe((float) 0);
});

it('doesnt append not approved reviews to product entity dto', function () {
    $entity = \SmartCms\Store\Database\Factories\ProductFactory::new()->state(['status' => 1])->create();
    ProductReview::factory()->count(3)->state(['is_approved' => false])->create(['product_id' => $entity->id]);
    $dto = new ProductEntityDto($entity->id, $entity->name(), '', $entity->getBreadcrumbs(), $entity->images ?? [], $seo->summary ?? null, $seo->content ?? null, $entity->price, $entity->sale_price, false, '', [], [], $entity->sku);
    $dto = $dto->get();
    expect($dto)->toHaveProperty('reviews');
    expect($dto->reviews)->toBeArray();
    expect($dto->reviews)->toHaveCount(0);
});
