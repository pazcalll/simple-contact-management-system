<?php

namespace App\Services\Admin;

use App\Models\AssignedLead;
use App\Models\Lead;
use App\Models\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;

class LeadService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private ?FormRequest $formRequest = null,
    ) {}

    public function getFormRequest(): ?FormRequest
    {
        return $this->formRequest;
    }

    public function setFormRequest(?FormRequest $formRequest): static
    {
        $this->formRequest = $formRequest;
        return $this;
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

    public function updateLeadStatuses(): static
    {
        Lead::whereIn('id', $this->formRequest->post('lead_ids'))
            ->update([
                'lead_status_id' => $this->formRequest->post('lead_status_id'),
            ]);

        return $this;
    }

    public function updateMassLeadAssignee(): static
    {
        $this->deleteMassLeadAssignee($this->formRequest->post('lead_ids'));
        $leads = Lead::whereIn('id', $this->formRequest->post('lead_ids'))->get();

        if ($this->formRequest->post('manager_id')) {
            $assigneeIds = array_filter([
                $this->formRequest->post('manager_id') ?? null,
                $this->formRequest->post('supervisor_id') ?? null,
                $this->formRequest->post('team_leader_id') ?? null,
                $this->formRequest->post('staff_id') ?? null,
            ], function ($value) {
                return !is_null($value);
            });

            foreach ($leads as $key => $lead) {
                $this->createLeadAssignee($lead, $assigneeIds);
            }
        }

        return $this;
    }
}
