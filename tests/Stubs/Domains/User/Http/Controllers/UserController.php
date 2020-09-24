<?php

namespace Supplycart\Domains\Tests\Stubs\Domains\User\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Supplycart\Domains\Tests\Stubs\Domains\User\Models\User;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', User::class);

        return 'Users Index';
    }

    public function edit()
    {
        $this->authorize('edit', User::class);

        return 'Users Edit';
    }
}