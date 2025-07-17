<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadStatusController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Authorized;
use App\Http\Controllers\Authorized\Admin;
use App\Http\Controllers\Authorized\Manager;
use App\Http\Controllers\Authorized\Supervisor;
use App\Http\Controllers\Authorized\TeamLeader;
use App\Http\Controllers\Authorized\Staff;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function() {
});

Route::get('lead-statuses/csv', [LeadStatusController::class, 'exportCsv']);
Route::get('leads/template', [LeadController::class, 'leadsTemplate']);

Route::middleware(['auth'])->group(function () {
    Route::prefix('admins')->name('admins.')->middleware(['role:'.Role::ROLE_ADMIN])->group(function () {
        Route::prefix('json')->name('json.')->group(function () {
            Route::get('users/get-users-by-role/{role}', [Admin\UserController::class, 'getUsersByRole']);
            Route::get('users/get-users-by-upline/{upline}', [Admin\UserController::class, 'getUsersByUpline']);
        });
        Route::resource('users', Admin\UserController::class);
        Route::post('leads/mass-assignee', [Admin\LeadController::class, 'storeMassAssignee']);
        Route::post('leads/bulk-assign-leads', [Admin\LeadController::class, 'bulkAssignLeads']);
        Route::post('leads/import', [Admin\LeadController::class, 'import']);
        Route::get('customers', [Admin\LeadController::class, 'getCustomers'])->name('customers');
        Route::resource('leads', Admin\LeadController::class);
    });

    Route::prefix('managers')->name('managers.')->middleware(['role:'.Role::ROLE_MANAGER])->group(function () {
        Route::patch('leads/bulk-assign-leads', [Manager\LeadController::class, 'bulkAssignLeads']);
        Route::patch('leads/{lead}/status', [Manager\LeadController::class, 'updateStatus']);
        Route::resource('leads/{lead}/lead-notes', Manager\LeadNoteController::class);
        Route::resource('leads', Manager\LeadController::class);
    });

    Route::prefix('supervisors')->name('supervisors.')->middleware(['role:'.Role::ROLE_SUPERVISOR])->group(function () {
        Route::patch('leads/bulk-assign-leads', [Supervisor\LeadController::class, 'bulkAssignLeads']);
        Route::resource('leads', Supervisor\LeadController::class);
    });

    Route::prefix('team-leaders')->name('team-leaders.')->middleware(['role:'.Role::ROLE_TEAM_LEADER])->group(function () {
        Route::patch('leads/bulk-assign-leads', [TeamLeader\LeadController::class, 'bulkAssignLeads']);
        Route::resource('leads', TeamLeader\LeadController::class);
    });

    Route::prefix('staffs')->name('staffs.')->middleware(['role:'.Role::ROLE_STAFF])->group(function () {
        Route::resource('leads/{lead}/notes', Staff\LeadNoteController::class);
        Route::patch('leads/{lead}/status', [Staff\LeadController::class, 'updateStatus']);
        Route::patch('leads/mass-update-status', [Staff\LeadController::class, 'massUpdateStatus']);
        Route::resource('leads', Staff\LeadController::class);
    });

    Route::resource('leads/{lead}/notes', Authorized\LeadNoteController::class);
    Route::patch('leads/update-lead-status', [Authorized\LeadController::class, 'updateLeadStatus']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
