<?php

namespace SmartCms\Reviews;

use Illuminate\Support\ServiceProvider;
use SmartCms\Core\Admin\Pages\Auth\Profile;
use SmartCms\Core\SmartCmsPanelManager;
use SmartCms\Reviews\Events\Admin\ProductPages;
use SmartCms\Reviews\Events\Admin\ProductSubNavigation;
use SmartCms\Reviews\Events\Admin\Resources;
use SmartCms\Reviews\Events\Dto\ProductEntityTransform;
use SmartCms\Reviews\Events\Dto\ProductTransform;
use SmartCms\Reviews\Events\ProfileNotifications;
use SmartCms\Reviews\Models\ProductReview;
use SmartCms\Store\Admin\Resources\ProductResource;
use SmartCms\Store\Models\Product;
use SmartCms\Store\Resources\Product\ProductEntityResource;
use SmartCms\Store\Resources\Product\ProductResource as ProductProductResource;

class ReviewsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'reviews');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        SmartCmsPanelManager::registerHook('navigation.resources', Resources::class);
        ProductResource::registerHook('sub_navigation', ProductSubNavigation::class);
        ProductResource::registerHook('pages', ProductPages::class);
    }

    public function boot()
    {
        Product::resolveRelationUsing('reviews', function ($product) {
            return $product->hasMany(ProductReview::class);
        });
        ProductProductResource::registerHook('transform.data', ProductTransform::class);
        ProductEntityResource::registerHook('transform.data', ProductEntityTransform::class);
        Profile::registerHook('telegram.notifications', ProfileNotifications::class);
        Profile::registerHook('mail.notifications', ProfileNotifications::class);
    }
}
