<?php

namespace App\Traits\Relations;

use App\Models\Lead;

trait BelongsToLead
{
    //
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
