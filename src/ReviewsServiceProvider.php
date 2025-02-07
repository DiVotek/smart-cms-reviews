<?php

namespace SmartCms\Reviews;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SmartCms\Reviews\Admin\Actions\Navigation\Pages;
use SmartCms\Reviews\Admin\Pages\ReviewSettings;
use SmartCms\Reviews\Events\Admin\ProductPages;
use SmartCms\Reviews\Events\Admin\ProductSubNavigation;
use SmartCms\Reviews\Events\Admin\Resources;
use SmartCms\Reviews\Events\Dto\ProductEntityTransform;
use SmartCms\Reviews\Events\Dto\ProductTransform;
use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Models\Product;

class ReviewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'reviews');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        Event::listen('cms.admin.navigation.resources', Resources::class);
        Event::listen('cms.admin.product.pages', ProductPages::class);
        Event::listen('cms.admin.product.sub_navigation', ProductSubNavigation::class);
        // Event::listen('cms.admin.navigation.settings_pages', Pages::class);
    }

    public function boot()
    {
        Product::resolveRelationUsing('reviews', function ($product) {
            return $product->hasMany(ProductReview::class);
        });
        Event::listen('cms.product-entity.transform', ProductEntityTransform::class);
        Event::listen('cms.product.transform', ProductTransform::class);
    }
}
