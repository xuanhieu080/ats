<?php

namespace Packages\Department\Providers;

use Illuminate\Support\ServiceProvider;
use Packages\Department\Repositories\DepartmentRepository;
use Packages\Department\Repositories\DepartmentRepositoryInterface;
use Illuminate\Support\Facades\File;


class DepartmentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulePath = __DIR__.'/../../';

        #php artisan vendor:publish --tag=config-department
        $this->publishes([
            $modulePath.'/configs/department.php' => config_path('department.php'),
        ], 'config-department');

        #php artisan make:migration create_demo_table --path=packages/Department/database/migrations
        $this->loadMigrationsFrom($modulePath . 'database/migrations');

        if (File::exists($modulePath . "routes/web.php")) {
            $this->loadRoutesFrom($modulePath . "routes/web.php");
        }

        if (File::exists($modulePath . "routes/api.php")) {
            $this->loadRoutesFrom($modulePath . "routes/api.php");
        }

        if (File::exists($modulePath . "resources/views")) {
            $this->loadViewsFrom($modulePath . "resources/views", 'Department');
        }

        $this->loadTranslationsFrom($modulePath . 'resources/lang', 'department');

        $this->publishes([
            $modulePath .'public' => public_path('vendor/department'),
        ], 'public');;
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
    }

}
