<?php

namespace App\Services;

use App\Models\AssignedLead;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadStatus;
use App\Models\Role;
use App\Traits\Services\Lead\CanUpdateStatus;
use Illuminate\Support\Facades\Auth;

class LeadService
{
    use CanUpdateStatus;
 
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getPagination($page = 1, $length = 15, bool $isQueryOnly = false)
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
            ->isNotCustomer()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');

        if ($isQueryOnly) return $lead;

        return $lead->paginate(perPage: $length, page: $page);
    }

    public function getOneById($leadId)
    {
        return Lead::with(['leadStatus'])->find($leadId);
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

    public function deleteMassLeadAssignee(array $leadIds, array $nonRemovableUserIds = []): static
    {
        // if there are unassignable ids, we will not delete them
        if (!empty($nonRemovableUserIds)) {
            AssignedLead::whereIn('lead_id', $leadIds)
                ->whereNotIn('user_id', $nonRemovableUserIds)
                ->delete();
            return $this;
        }

        // delete all assigned leads for the given lead ids
        else AssignedLead::whereIn('lead_id', $leadIds)->delete();

        return $this;
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

    /**
     * Summary of assignLeads
     * This method actually is a more efficient way to assign leads
     * It was meant to replace updateMassLeadAssignee() due to its simplicity
     * @param array $leadIds
     * @param array $assigneeIds
     * @return LeadService
     */
    public function assignLeads(array $leadIds, array $assigneeIds): static
    {
        $this->deleteMassLeadAssignee($leadIds);
        $leads = Lead::whereIn('id', $leadIds)->get();

        foreach ($leads as $key => $lead) {
            $this->createLeadAssignee($lead, $assigneeIds);
        }
        return $this;
    }

    /**
     * (DEPRECATED)
     * Summary of updateMassLeadAssignee
     * @param array $leadIds
     * @param int|string|null $managerId
     * @param int|string|null $supervisorId
     * @param int|string|null $teamLeaderId
     * @param int|string|null $staffId
     * @param bool $isUnassign
     * @param array $nonRemovableUserIds
     * @return LeadService
     */
    public function updateMassLeadAssignee(
        array $leadIds,
        int|string|null $managerId = null,
        int|string|null $supervisorId = null,
        int|string|null $teamLeaderId = null,
        int|string|null $staffId = null,
        bool $isUnassign = false,
        array $nonRemovableUserIds = []
    ): static
    {
        if ($isUnassign) {
            $this->deleteMassLeadAssignee($leadIds, $nonRemovableUserIds);
            return $this;
        }

        if ($managerId) {
            $this->deleteMassLeadAssignee($leadIds);
            $leads = Lead::whereIn('id', $leadIds)->get();

            $assigneeIds = array_filter([
                $managerId ?? null,
                $supervisorId ?? null,
                $teamLeaderId ?? null,
                $staffId ?? null,
            ], function ($value) {
                return !is_null($value);
            });

            foreach ($leads as $key => $lead) {
                $this->createLeadAssignee($lead, $assigneeIds);
            }
        }

        return $this;
    }

    public function updateLeadStatuses(array $leadIds, int $leadStatusId): static
    {
        Lead::whereIn('id', $leadIds)->update([
            'lead_status_id' => $leadStatusId,
        ]);
        foreach ($leadIds as $key => $leadId) {
            $lead = Lead::findOrFail($leadId);
            $leadStatus = LeadStatus::findOrFail($leadStatusId);

            LeadNote::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'note' => "Lead {$lead->name} status updated to {$leadStatus->name}",
            ]);
        }
        return $this;
    }
}
