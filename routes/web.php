<?php

use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Authorized\Admin;
use App\Http\Controllers\Authorized\Staff;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function() {
    Route::post('leads/update-lead-status', [Admin\LeadController::class, 'updateLeadStatus']);
});

Route::prefix('admins')->name('admins.')->middleware(['role:'.Role::ROLE_ADMIN])->group(function () {
    Route::prefix('json')->name('json.')->group(function () {
        Route::get('users/get-users-by-role/{role}', [Admin\UserController::class, 'getUsersByRole']);
        Route::get('users/get-users-by-upline/{upline}', [Admin\UserController::class, 'getUsersByUpline']);
    });
    Route::resource('users', Admin\UserController::class);
    Route::post('leads/mass-assignee', [Admin\LeadController::class, 'storeMassAssignee']);
    Route::post('leads/bulk-assign-leads', [Admin\LeadController::class, 'bulkAssignLeads']);
    Route::resource('leads', Admin\LeadController::class);
});

Route::prefix('staffs')->name('staffs.')->middleware(['role:'.Role::ROLE_STAFF])->group(function () {
    Route::resource('leads/{lead}/notes', Staff\LeadNoteController::class);
    Route::patch('leads/{lead}/status', [Staff\LeadController::class, 'updateStatus']);
    Route::resource('leads', Staff\LeadController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
