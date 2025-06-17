<?php

namespace App\Services;

use App\Models\AssignedLead;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;
use Illuminate\Validation\UnauthorizedException;

class LeadNoteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {}

    public function get(Lead $lead)
    {
        return $lead
            ->load('leadNotes.user')
            ->leadNotes;
    }

    public function create(Lead $lead, User $user, string $note)
    {
        $isUserHasLead = AssignedLead::query()
            ->where('lead_id', $lead->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isUserHasLead) throw new UnauthorizedException("Unauthorized.", 403);

        $leadNote = LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'note' => $note
        ]);

        return $leadNote;
    }
}
