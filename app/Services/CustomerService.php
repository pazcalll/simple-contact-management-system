<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPagination($page = 1, $length = 15, bool $isQueryOnly = false)
    {
        // Implementation for pagination of customers
        $query = Lead::query()
            ->isCustomer()
            ->with(['leadStatus', 'users.roles'])
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        if (@!Auth::user()->hasRole(Role::ROLE_ADMIN)) {
            $query->whereHas('users', function ($query) {
                $query->where('users.id', Auth::id());
            });
        }

        if ($isQueryOnly) return $query;

        return $query->paginate(perPage: $length, page: $page);
    }
}
