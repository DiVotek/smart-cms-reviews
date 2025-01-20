<?php

namespace SmartCms\Reviews\Repositories;

use SmartCms\Reviews\Dto\ProductReviewDto;
use SmartCms\Reviews\Models\ProductReview;

class ProductReviewRepository
{
    public static function make(): self
    {
        return new self;
    }

    public function findByProductId($productId): array
    {
        return ProductReview::query()
            ->where('is_approved', true)
            ->where('product_id', $productId)->get()->map(function (ProductReview $review) {
                return ProductReviewDto::factory($review->product_id, $review->rating, $review->is_approved, $review->data ?? [], $review->created_at)->get();
            })->toArray();
    }

    public function get(): array
    {
        return ProductReview::query()
            ->where('is_approved', true)
            ->get()->map(function (ProductReview $review) {
                return ProductReviewDto::factory($review->product_id, $review->rating, $review->is_approved, $review->data ?? [], $review->created_at)->get();
            })->toArray();
    }

    public function getRating($productId): float
    {
        return ProductReview::query()->where('is_approved', true)
            ->where('product_id', $productId)->avg('rating') ?? 0;
    }
}
