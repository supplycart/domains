<?php

namespace Supplycart\Domains\Tests\Stubs\Domains\User;

use Illuminate\Support\Facades\Route;
use Supplycart\Domains\Domain;
use Supplycart\Domains\Tests\Stubs\Domains\User\Models\User;
use Supplycart\Domains\Tests\Stubs\Domains\User\Policies\UserPolicy;

class UserDomain extends Domain
{
    protected array $policies = [
        User::class => UserPolicy::class,
    ];

    public static function registerRoutes(): void
    {
        Route::get('users', function () {
            return 'Users Index';
        })->name('users.index');
    }
}