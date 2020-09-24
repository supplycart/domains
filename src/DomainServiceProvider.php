<?php

namespace Supplycart\Domains;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/domains.php', 'domains');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/domains.php' => config_path('domains.php'),
        ], 'config');

        $this->registerModules();
    }

    private function registerModules()
    {
        /** @var \Supplycart\Domains\Domain $domain */
        foreach (config('domains.modules', []) as $domain) {
            $domain::init();
        }
    }
}
