<?php

namespace SmartCms\Reviews\Repositories;

use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Reviews\Resources\ProductReviewResource;

class ProductReviewRepository
{
    public static function make(): self
    {
        return new self;
    }

    public function findByProductId($productId): array
    {
        return ProductReview::query()
            ->where('product_id', $productId)->get()->map(function (ProductReview $review) {
                return ProductReviewResource::make($review)->get();
            })->toArray();
    }

    public function get(): array
    {
        return ProductReview::query()
            ->get()->map(function (ProductReview $review) {
                return ProductReviewResource::make($review)->get();
            })->toArray();
    }

    public function getRating($productId): float
    {
        return ProductReview::query()
            ->where('product_id', $productId)->avg('rating') ?? 0;
    }
}
