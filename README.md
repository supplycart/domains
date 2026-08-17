# Laravel Domains

A small, route-focused domain module registry for Laravel 13 applications.

Version 2 deliberately leaves events, model observers, and authorization policies
to Laravel's native discovery and attribute APIs. As a result, the package does no
per-domain runtime initialization once routes have been cached.

## Installation

```bash
composer require supplycart/domains:^2.0
php artisan vendor:publish --tag=domains-config
```

Register each domain class in `config/domains.php`:

```php
<?php

declare(strict_types=1);

use App\Domains\User\UserDomain;

return [
    'modules' => [
        UserDomain::class,
    ],
];
```

Each domain extends `Supplycart\Domains\Domain` and overrides the static route
hook when it owns routes:

```php
<?php

declare(strict_types=1);

namespace App\Domains\User;

use Supplycart\Domains\Domain;

final class UserDomain extends Domain
{
    public static function registerRoutes(): void
    {
        require __DIR__.'/Http/routes.php';
    }
}
```

Domains without routes may inherit the no-op implementation.

## Generate a domain

```bash
php artisan make:domain User
```

To also create an event, discoverable listener, and queued job:

```bash
php artisan make:domain User --queues
```

The generated model uses Laravel's `#[UsePolicy]` attribute. Applications should
register observers with `#[ObservedBy]` and discover domain listeners from
`bootstrap/app.php`:

```php
->withEvents(discover: [
    __DIR__.'/../app/Domains/*/Listeners',
])
```

## Production optimization

Run Laravel's optimizer as part of deployment or image creation:

```bash
php artisan optimize
```

When routes are cached, this package skips the configured domain list entirely.

## Upgrading from version 1

Version 2 removes domain-level `$listeners`, `$observers`, `$policies`, `init()`,
and reflection-based route discovery. Move listeners to Laravel event discovery,
use `#[ObservedBy]` and `#[UsePolicy]`, and implement `registerRoutes()` explicitly.
