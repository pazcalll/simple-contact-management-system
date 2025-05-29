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

            AssignedLead::create([
                'user_id' => $assigneeId,
                'lead_id' => $lead->id,
            ]);
        }
    }

    public function deleteMassLeadAssignee(array $leadIds)
    {
        AssignedLead::whereIn('lead_id', $leadIds)->delete();
    }

    public function createMassLeadAssignee(array $leadIds, array $assigneeIds)
    {
        // reset the assignee data
        $this->deleteMassLeadAssignee($leadIds);

        // re-create the assignees
        $leads = Lead::whereIn('id', $leadIds)->get();
        foreach ($leads as $key => $lead) {
            $this->createLeadAssignee($lead, $assigneeIds);
        }
    }
}
