<?php

namespace App\Services\Staff;

use App\Models\Lead;
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

    public function getOneById($leadId)
    {
        return Lead::find($leadId);
    }

    public function getPagination($page = 1, $length = 15)
    {
        $lead = Lead::with('leadStatus')
            ->whereHas(
                'users',
                fn ($query) => $query->where('user_id', Auth::id())
            )
            ->paginate(perPage: $length, page: $page);
        return $lead;
    }
}
