<?php

namespace Supplycart\Domains\Tests\Stubs\Domains\User\Policies;

class UserPolicy
{
    public function view($user)
    {
        return true;
    }
}