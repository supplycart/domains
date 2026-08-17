<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests\Stubs\Domains\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;

final class UserPolicy
{
    use HandlesAuthorization;

    public function view(?User $user): bool
    {
        return true;
    }

    public function edit(?User $user): bool
    {
        return false;
    }
}
