<?php

namespace SmartCms\Reviews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SmartCms\Reviews\Models\ProductReview;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition()
    {
        return [
            'product_id' => 1,
            'rating' => $this->faker->numberBetween(1, 5),
            'is_approved' => $this->faker->boolean(),
        ];
    }
}
