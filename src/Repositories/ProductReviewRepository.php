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
            ->where('product_id', $productId)->get()->map(function (ProductReview $review) {
                return ProductReviewDto::factory($review->name, $review->email, $review->rating, $review->comment, $review->admin_comment, $review->images, $review->created_at, $review->updated_at)->get();
            })->toArray();
    }

    public function get(): array
    {
        return ProductReview::query()
            ->get()->map(function (ProductReview $review) {
                return ProductReviewDto::factory($review->name, $review->email, $review->rating, $review->comment, $review->admin_comment, $review->images, $review->created_at, $review->updated_at)->get();
            })->toArray();
    }

    public function getRating($productId): float
    {
        return ProductReview::query()
            ->where('product_id', $productId)->avg('rating') ?? 0;
    }
}
