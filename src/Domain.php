<?php

namespace Supplycart\Domains;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use ReflectionClass;

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
        $domain = new static;
        
        if (file_exists($domain->getDomainPath() . '/Http/routes.php')) {
            require $domain->getDomainPath() . '/Http/routes.php';
        }
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
        foreach ($this->listeners as $event => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    Event::listen($event, $listener);
                }
            } else {
                Event::listen($event, $listeners);
            }
        }
    }

    public function registerPolicies()
    {
        Gate::guessPolicyNamesUsing(function ($className) {
            $classBasename = class_basename($className);

            $policyClass = $this->getReflectionClass()->getNamespaceName() . "\Policies\\{$classBasename}Policy";

            if (class_exists($policyClass)) {
                return $policyClass;
            }

            return "App\\Policies\\{$classBasename}Policy";
        });

        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }

    public function getDomainPath(): string
    {
        return dirname($this->getReflectionClass()->getFileName());
    }

    /**
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function getReflectionClass(): \ReflectionClass
    {
        return (new ReflectionClass($this));
    }
}
