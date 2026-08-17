# Laravel Domains

A lightweight, route-focused domain registry for Laravel 13.

The package keeps domain bootstrapping deliberately small: it registers each
domain's routes, while Laravel handles events, model observers, and authorization
policies through its native discovery and attribute APIs. When routes are cached,
the configured domains are not loaded at all.

## Requirements

- PHP 8.5 or later
- Laravel 13

## Installation

Install the package and publish its configuration:

```bash
composer require supplycart/domains:^2.0
php artisan vendor:publish --tag=domains-config
```

## Creating a domain

Generate a domain with Artisan:

```bash
php artisan make:domain User
```

This creates the domain under `app/Domains/User`, including its domain class,
model, policy, controller, and routes file.

Register the generated domain class in `config/domains.php`:

```php
<?php

declare(strict_types=1);

use App\Domains\User\User;

return [
    'modules' => [
        User::class,
    ],
];
```

The generated domain owns its route registration:

```php
<?php

declare(strict_types=1);

namespace App\Domains\User;

use Supplycart\Domains\Domain;

final class User extends Domain
{
    public static function registerRoutes(): void
    {
        require __DIR__.'/Http/routes.php';
    }
}
```

Domains without routes can inherit the default no-op implementation.

### Queued scaffolding

Use the `--queues` option to also generate an event, a discoverable listener,
and a queued job:

```bash
php artisan make:domain User --queues
```

Discover domain listeners from `bootstrap/app.php`:

```php
->withEvents(discover: [
    __DIR__.'/../app/Domains/*/Listeners',
])
```

The generated model is linked to its policy with Laravel's `#[UsePolicy]`
attribute. Register model observers with Laravel's `#[ObservedBy]` attribute.

## Production optimization

Run Laravel's optimizer during deployment or image creation:

```bash
php artisan optimize
```

Once routes are cached, the package skips the configured domain list entirely.

## Upgrading from version 1

Version 2 removes domain-level `$listeners`, `$observers`, `$policies`, `init()`,
and reflection-based route discovery. When upgrading:

- Move listeners to Laravel event discovery.
- Register observers with `#[ObservedBy]`.
- Register policies with `#[UsePolicy]`.
- Implement `registerRoutes()` explicitly in domains that own routes.
