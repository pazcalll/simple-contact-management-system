<?php

namespace App\Services\Admin;

use App\Models\AssignedLead;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\User;

class LeadService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createLead(array $payload)
    {
        $leadStatus = LeadStatus::where('name', 'New')->first();
        return Lead::create([
            ...$payload,
            'utm_source' => @$payload['source'],
            'utm_medium' => @$payload['medium'],
            'utm_campaign' => @$payload['campaign'],
            'lead_status_id' => $leadStatus->id,
        ]);
    }

    public function createLeadAssignee(Lead $lead, array $assigneeIds)
    {
        foreach ($assigneeIds as $key => $assigneeId) {
            if ($assigneeId == null) continue;

            $assignee = User::find($assigneeId);
            if ($assignee) {
                AssignedLead::create([
                    'user_id' => $assignee->id,
                    'lead_id' => $lead->id,
                ]);
            }
        }
    }
}
