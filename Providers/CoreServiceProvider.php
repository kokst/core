<?php

namespace Kokst\Core\Providers;

use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            \Kokst\Core\Console\Commands\MakeModuleCommand::class,
        ]);
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'core');

        $this->publishes([
            __DIR__ . '/../Resources/views' => resource_path('views/vendor/kokst/core'),
        ]);
    }
}
