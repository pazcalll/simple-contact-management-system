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
        return Lead::with(['leadStatus'])->find($leadId);
    }

    public function getPagination($page = 1, $length = 15)
    {
        $lead = Lead::with('leadStatus')
            ->whereHas(
                'users',
                fn ($query) => $query->where('user_id', Auth::id())
            )
            ->orderBy('created_at', 'desc')
            ->paginate(perPage: $length, page: $page);
        return $lead;
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
