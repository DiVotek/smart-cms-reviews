<?php

namespace SmartCms\Reviews\Dto;

use DateTime;
use SmartCms\Core\Repositories\DtoInterface;
use SmartCms\Core\Traits\Dto\AsDto;

class ProductReviewDto implements DtoInterface
{
    use AsDto;

    public function __construct(
        public string $name,
        public ?string $email,
        public int $rating,
        public ?string $comment,
        public ?string $admin_comment,
        public array $images,
        public DateTime $createdAt,
        public DateTime $updatedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'admin_comment' => $this->admin_comment,
            'images' => $this->images,
            'created_at' => $this->transformDate($this->createdAt),
            'updated_at' => $this->transformDate($this->updatedAt),
        ];
    }
}
