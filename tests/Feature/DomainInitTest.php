<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests\Feature;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Supplycart\Domains\DomainServiceProvider;
use Supplycart\Domains\Tests\TestCase;

final class DomainInitTest extends TestCase
{
    public function test_domain_routes_can_be_registered(): void
    {
        $this->assertTrue(Route::has('users.index'));
    }

    public function test_invalid_domain_configuration_is_rejected(): void
    {
        $app = $this->app;
        self::assertNotNull($app);
        $app->make(Repository::class)->set('domains.modules', [self::class]);

        $this->expectException(InvalidArgumentException::class);

        (new DomainServiceProvider($app))->boot();
    }

    public function test_non_array_domain_configuration_is_rejected(): void
    {
        $app = $this->app;
        self::assertNotNull($app);
        $app->make(Repository::class)->set('domains.modules', 'invalid');

        $this->expectException(InvalidArgumentException::class);

        (new DomainServiceProvider($app))->boot();
    }
}
