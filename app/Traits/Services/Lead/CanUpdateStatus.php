<?php

namespace App\Traits\Services\Lead;

trait CanUpdateStatus
{
    //
    public function updateLeadStatuses(array $leadIds, int|string $leadStatusId): static
    {
        // Update the lead status for the given lead IDs
        \App\Models\Lead::whereIn('id', $leadIds)
            ->update(['lead_status_id' => $leadStatusId]);

        return $this;
    }
}
