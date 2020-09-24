<?php

namespace Supplycart\Domains;

use Illuminate\Support\Facades\Event;

abstract class Domain
{
    protected static array $observers = [];

    protected static array $listeners = [];

    public static function init()
    {
        static::routes();
        static::observers();
        static::listeners();
    }

    public static function routes(): void
    {

    }

    public static function observers(): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach (static::$observers as $model => $observer) {
            $model::observe($observer);
        }
    }

    public static function listeners(): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach (static::$listeners as $event => $listener) {
            Event::listen($event, $listener);
        }
    }
}