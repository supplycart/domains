<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests\Unit;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Supplycart\Domains\DomainServiceProvider;

final class DomainServiceProviderTest extends TestCase
{
    public function test_cached_routes_skip_domain_configuration_entirely(): void
    {
        $application = new class extends Application
        {
            public function routesAreCached(): bool
            {
                return true;
            }

            public function runningInConsole(): bool
            {
                return false;
            }
        };

        (new DomainServiceProvider($application))->boot();

        $this->addToAssertionCount(1);
    }
}
