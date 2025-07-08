<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Role;
use App\Traits\Services\Lead\CanUpdateStatus;
use Illuminate\Support\Facades\Auth;

class LeadService
{
    use CanUpdateStatus;
 
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPagination($page = 1, $length = 15)
    {
        $lead = Lead::with([
                'leadStatus',
                'users' => function ($query) {
                    $query->role(Role::ROLES);
                }
            ])
            ->whereHas(
                'users',
                fn ($query) => $query->where('user_id', Auth::id())
            )
            ->orderBy('created_at', 'desc')
            ->paginate(perPage: $length, page: $page);
        return $lead;
    }

    public function getOneById($leadId)
    {
        return Lead::with(['leadStatus'])->find($leadId);
    }
}
