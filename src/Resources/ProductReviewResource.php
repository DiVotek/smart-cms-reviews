<?php

namespace SmartCms\Reviews\Resources;

use SmartCms\Core\Resources\BaseResource;

class ProductReviewResource extends BaseResource
{
    public function prepareData($request): array
    {
        $images = [];
        $originalImages = $this->resource->images ?? [];
        foreach ($originalImages as $image) {
            $images[] = $this->validateImage($image);
        }
        return [
            'name' => $this->resource->name,
            'email' => $this->resource->email ?? '',
            'rating' => $this->resource->rating ?? 0,
            'comment' => $this->resource->comment ?? '',
            'admin_comment' => $this->resource->admin_comment ?? '',
            'images' => $images,
            'created_at' => $this->transformDate($this->resource->createdAt),
            'updated_at' => $this->transformDate($this->resource->updatedAt),
        ];
    }
}
