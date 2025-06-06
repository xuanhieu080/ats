<?php

namespace Packages\VCore\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Packages\VCore\Commands\VCoreCommand;


class VCoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $modulePath = __DIR__.'/../../';
        $moduleName = 'VCore';
        $moduleNameLower = strtolower($moduleName);

        $this->publishes([
            $modulePath.'/configs/'.$moduleNameLower.'.php' => config_path($moduleNameLower .'.php'),
        ], 'config-' . $moduleNameLower);

        $this->loadMigrationsFrom($modulePath . '/../../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                VCoreCommand::class,
            ]);
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {

    }

}
