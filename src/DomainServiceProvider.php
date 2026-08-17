<?php

declare(strict_types=1);

namespace Supplycart\Domains;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Supplycart\Domains\Console\Commands\MakeDomain;

final class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/domains.php', 'domains');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/domains.php' => config_path('domains.php'),
            ], 'domains-config');

            $this->commands([
                MakeDomain::class,
            ]);
        }

        $this->registerDomainRoutes();
    }

    private function registerDomainRoutes(): void
    {
        if ($this->app instanceof CachesRoutes && $this->app->routesAreCached()) {
            return;
        }

        /** @var mixed $configuredDomains */
        $configuredDomains = $this->app->make(Repository::class)->get('domains.modules', []);

        if (! is_array($configuredDomains)) {
            throw new InvalidArgumentException('The domains.modules configuration value must be an array.');
        }

        foreach ($configuredDomains as $domain) {
            if (! is_string($domain) || ! is_subclass_of($domain, Domain::class)) {
                throw new InvalidArgumentException('Every configured domain must extend '.Domain::class.'.');
            }

            $domain::registerRoutes();
        }
    }
}
