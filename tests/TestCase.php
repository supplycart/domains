<?php

namespace Supplycart\Domains\Tests;

use Supplycart\Domains\Tests\Stubs\Domains\User\UserDomain;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Supplycart\Domains\DomainServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('domains.modules', [
            UserDomain::class,
        ]);
    }

}