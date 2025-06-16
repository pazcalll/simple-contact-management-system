<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\User;

class LeadNoteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(Lead $lead, User $user, string $note)
    {
        $leadNote = LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'note' => $note
        ]);

        return $leadNote;
    }
}
