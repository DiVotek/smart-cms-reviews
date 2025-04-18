<?php

namespace SmartCms\Reviews\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use SmartCms\Reviews\ReviewsServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/smart-cms/store/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/smart-cms/core/database/new_migrations');

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $classBaseName = class_basename($modelName);

            if (class_exists($factory = 'SmartCms\\Reviews\\Database\\Factories\\' . $classBaseName . 'Factory')) {
                return $factory;
            }

            if (class_exists($factory = 'SmartCms\\Store\\Database\\Factories\\' . $classBaseName . 'Factory')) {
                return $factory;
            }

            return null;
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            ReviewsServiceProvider::class,
            \SmartCms\Store\StoreServiceProvider::class,
            \SmartCms\Core\SmartCmsPanelManager::class,
            \SmartCms\Core\SmartCmsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        $app->singleton('_settings', function () {
            return new \SmartCms\Core\Services\Singletone\Settings;
        });
        $app->singleton('_lang', function () {
            return new \SmartCms\Core\Services\Singletone\Languages;
        });
    }
}
