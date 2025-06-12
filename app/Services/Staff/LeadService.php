<?php

namespace App\Services\Staff;

use App\Models\Lead;

class LeadService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPagination($page = 1, $length = 15)
    {
        $lead = Lead::with('leadStatus')
            ->whereHas('users', fn ($query) => $query->where('user_id', auth()->id()))
            ->paginate(perPage: $length, page: $page);
        return $lead;
    }
}
