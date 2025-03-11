<?php

namespace SmartCms\Reviews\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Models\Product;

class ProductReviewFactory extends Factory
{
    protected $model = ProductReview::class;

    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'status' => $this->faker->boolean(),
            'rating' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'comment' => $this->faker->sentence(),
            'images' => [],
        ];
    }
}
