<?php

declare(strict_types=1);

namespace Supplycart\Domains;

abstract class Domain
{
    /**
     * Register the routes owned by the domain.
     *
     * Domains without routes may inherit this no-op implementation. Applications
     * may override it directly or compose their own route-registration trait.
     */
    public static function registerRoutes(): void
    {
        //
    }
}
