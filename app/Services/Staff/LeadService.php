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

    public function getPagination()
    {
        $lead = Lead::whereHas('users', fn ($query) => $query->where('user_id', auth()->id()))
            ->paginate();
        return $lead;
    }
}
