<?php

namespace App\Models;

use App\Traits\Relations\BelongsToLead;
use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class LeadNote extends Model
{
    use BelongsToLead, BelongsToUser;

    //
    protected $fillable = [
        'user_id',
        'lead_id',
        'note',
    ];
}
