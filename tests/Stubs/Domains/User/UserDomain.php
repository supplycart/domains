<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests\Stubs\Domains\User;

use Supplycart\Domains\Domain;

final class UserDomain extends Domain
{
    public static function registerRoutes(): void
    {
        require __DIR__.'/Http/routes.php';
    }
}
