<?php

namespace Supplycart\Domains\Tests\Stubs\Domains\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(?User $user)
    {
        return true;
    }

    public function edit(?User $user)
    {
        return false;
    }
}