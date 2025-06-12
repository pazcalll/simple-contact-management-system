<?php

namespace App\Traits\Relations;

use App\Models\LeadNote;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyLeadNotes
{
    //
    public function leadNotes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }
}
