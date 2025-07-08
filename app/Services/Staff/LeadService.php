<?php

namespace App\Services\Staff;

use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class LeadService extends \App\Services\LeadService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Summary of create
     * @param array{
     *  name: string,
     *  email: string,
     *  mobile_number: string,
     *  utm_source?: string|null,
     *  utm_medium?: string|null,
     *  utm_campaign?: string|null
     * }
     * @return Lead
     */
    public function create(array $payload): Lead
    {
        $payload['is_private'] = true;
        $payload['lead_status_id'] = 1; // Default status ID, assuming 1 is the default status
        $lead = Lead::create($payload);
        $lead->users()->sync([Auth::id()]);

        $lead->refresh();
        $lead->load(['leadStatus', 'users']);

        return $lead;
    }
}
