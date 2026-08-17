<?php

declare(strict_types=1);

namespace Supplycart\Domains\Tests\Stubs\Domains\User\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Supplycart\Domains\Tests\Stubs\Domains\User\Models\User;

final class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(): string
    {
        $this->authorize('view', User::class);

        return 'Users Index';
    }

    public function edit(): string
    {
        $this->authorize('edit', User::class);

        return 'Users Edit';
    }
}
