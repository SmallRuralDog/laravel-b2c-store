<?php

namespace SmallRuralDog\Store;

use Illuminate\Support\ServiceProvider;
use SmallRuralDog\Store\Commands\InstallCommand;
use SmallRuralDog\Store\Commands\UpdateCommand;

class StoreServiceProvider extends ServiceProvider
{

    protected $commands = [
        InstallCommand::class,
        UpdateCommand::class
    ];

    /**
     * {@inheritdoc}
     */
    public function boot(Store $extension)
    {
        if (!Store::boot()) {
            return;
        }


        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'store');
        }

        $this->publishes([
            __DIR__ . '/../config/store.php' => config_path('store.php'),
        ]);

        if ($migrations = $extension->migrations()) {
            $this->loadMigrationsFrom($migrations);
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/store')],
                'store'
            );
        }

        $this->app->booted(function () {
            Store::routes(__DIR__ . '/../routes/web.php');
        });
    }

    public function register()
    {
        $this->commands($this->commands);
    }
}