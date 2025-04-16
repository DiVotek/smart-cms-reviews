<?php

namespace SmartCms\Reviews\Events\Dto;

use SmartCms\Reviews\Repositories\ProductReviewRepository;

class ProductTransform
{
    public function __invoke(&$dto)
    {
        $dto['rating'] = ProductReviewRepository::make()->getRating($dto['id']);
    }
}
