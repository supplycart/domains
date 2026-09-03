<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests;

use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as Orchestra;
use Supplycart\Domains\DomainServiceProvider;
use Supplycart\Domains\Tests\Stubs\Domains\User\UserDomain;

abstract class TestCase extends Orchestra
{
    /** @return list<class-string> */
    protected function getPackageProviders($app): array
    {
        return [DomainServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app->make(Repository::class)->set('domains.modules', [
            UserDomain::class,
        ]);
    }
}
