<?php

namespace SmartCms\Reviews\Dto;

use DateTime;
use SmartCms\Core\Repositories\DtoInterface;
use SmartCms\Core\Traits\Dto\AsDto;

class ProductReviewDto implements DtoInterface
{
    use AsDto;

    public function __construct(
        public string $product_id,
        public int $rating,
        public bool $is_approved,
        public array $data,
        public DateTime $createdAt
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->data->name ?? 'Anonymus user',
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'is_approved' => $this->is_approved,
            'data' => $this->data ?? [],
            'created_at' => $this->transformDate($this->createdAt),
        ];
    }
}
