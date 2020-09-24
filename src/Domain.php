<?php

namespace Supplycart\Domains;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;

abstract class Domain
{
    protected array $observers = [];

    protected array $listeners = [];

    protected array $policies = [];

    public static function init()
    {
        $domain = new static();

        // $domain->registerRoutes();
        $domain->registerObservers();
        $domain->registerEventListeners();
        $domain->registerPolicies();
    }

    public static function registerRoutes(): void
    {
        require __DIR__ . '/Http/routes.php';
    }

    public function registerObservers(): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach ($this->observers as $model => $observer) {
            $model::observe($observer);
        }
    }

    public function registerEventListeners(): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach ($this->listeners as $event => $listener) {
            Event::listen($event, $listener);
        }
    }

    public function registerPolicies()
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}