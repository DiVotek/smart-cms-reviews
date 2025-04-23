<?php

namespace SmartCms\Reviews\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SmartCms\Core\Models\BaseModel;
use SmartCms\Core\Traits\HasStatus;
use SmartCms\Store\Models\Product;

/**
 * ProductReview
 *
 * @property string $name
 * @property string $email
 * @property int $product_id
 * @property int $rating
 * @property string $image
 * @property string $comment
 * @property string $status
 * @property Product $product
 * @property \Datetime $created_at
 * @property \Datetime $updated_at
 */
class ProductReview extends BaseModel
{
    use HasFactory;
    use HasStatus;

    protected $guarded = [];

    protected $casts = [
        'images' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
