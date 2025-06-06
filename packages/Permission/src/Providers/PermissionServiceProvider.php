<?php

namespace Packages\Permission\Providers;

use Illuminate\Support\ServiceProvider;
use Packages\Permission\Repositories\PermissionRepository;
use Packages\Permission\Repositories\PermissionRepositoryInterface;
use Illuminate\Support\Facades\File;


class PermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulePath = __DIR__.'/../../';

        #php artisan vendor:publish --tag=config-permission
        $this->publishes([
            $modulePath.'/configs/permission.php' => config_path('permission.php'),
        ], 'config-permission');

        #php artisan make:migration create_demo_table --path=packages/Permission/database/migrations
        $this->loadMigrationsFrom($modulePath . 'database/migrations');

        if (File::exists($modulePath . "routes/web.php")) {
            $this->loadRoutesFrom($modulePath . "routes/web.php");
        }

        if (File::exists($modulePath . "routes/api.php")) {
            $this->loadRoutesFrom($modulePath . "routes/api.php");
        }

        if (File::exists($modulePath . "resources/views")) {
            $this->loadViewsFrom($modulePath . "resources/views", 'Permission');
        }

        $this->loadTranslationsFrom($modulePath . 'resources/lang', 'permission');

        $this->publishes([
            $modulePath .'public' => public_path('vendor/permission'),
        ], 'public');;
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

}
