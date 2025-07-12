<?php

namespace App\Services;

use App\Models\LeadStatus;

class LeadStatusService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return LeadStatus::get();
    }
}
