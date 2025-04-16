<?php

namespace SmartCms\Reviews\Events\Dto;

use SmartCms\Reviews\Repositories\ProductReviewRepository;

class ProductEntityTransform
{
    public function __invoke(&$dto): void
    {
        $dto['rating'] = ProductReviewRepository::make()->getRating($dto['id']);
        $dto['reviews'] = ProductReviewRepository::make()->findByProductId($dto['id']);
    }
}
