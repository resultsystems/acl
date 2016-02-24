<?php

namespace ResultSystems\Acl;

use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Service provider boot
     */
    public function boot()
    {
        $this->publishConfiguration();
        $this->publishMigrations();
    }
    /**
     * Service provider registration
     */
    public function register()
    {
        $this->registerBindings();
    }
    /**
     * Register IoC Bindings
     */
    protected function registerBindings()
    {
        $this->mergeConfigFrom(__DIR__ . '/Resources/acl.php', 'acl');
    }

    /**
     * Publish dashboard configuration.
     */
    protected function publishConfiguration()
    {
        $this->publishes([
            __DIR__ . '/Resources/acl.php' => config_path('acl.php'),
        ], 'config');
    }

    /**
     * Publish migration file.
     */
    private function publishMigrations()
    {
        $this->publishes([__DIR__ . '/Resources/migrations/' => base_path('database/migrations')], 'migrations');
    }

    /**
     *
     * @return array
     */
    public function provides()
    {
        return ['acl'];
    }
}
