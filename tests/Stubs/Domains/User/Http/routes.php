<?php

use Illuminate\Support\Facades\Route;
use Supplycart\Domains\Tests\Stubs\Domains\User\Http\Controllers\UserController;

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/edit', [UserController::class, 'edit'])->name('users.edit');