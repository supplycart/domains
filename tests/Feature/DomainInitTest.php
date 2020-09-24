<?php

namespace Supplycart\Domains\Tests\Feature;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Supplycart\Domains\Tests\Stubs\Domains\User\Models\User;
use Supplycart\Domains\Tests\Stubs\Domains\User\Policies\UserPolicy;
use Supplycart\Domains\Tests\TestCase;

class DomainInitTest extends TestCase
{
    public function test_domain_routes_can_be_registered()
    {
        $this->assertTrue(Route::has('users.index'));
    }

    public function test_domain_policies_can_be_registered()
    {
        $this->assertEquals(UserPolicy::class, get_class(Gate::getPolicyFor(User::class)));

        $this->get('users')->assertSuccessful()->assertSee('Users Index');
        $this->get('users/edit')->assertForbidden()->assertDontSee('Users Index');
    }
}
