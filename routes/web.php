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

Route::prefix('admins')->name('admins.')->middleware(['role:'.Role::ROLE_ADMIN])->group(function () {
    Route::prefix('json')->name('json')->group(function () {
        Route::get('users/get-users-by-role/{role}', [Admin\UserController::class, 'getUsersByRole']);
    });
    Route::resource('users', Admin\UserController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
