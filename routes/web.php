<?php

use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admins')->middleware(['role:'.Role::ROLE_ADMIN])->group(function () {
    Route::resource('users', Admin\UserController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
