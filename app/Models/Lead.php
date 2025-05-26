<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'gtm_source',
        'gtm_medium',
        'gtm_campaign',
        'is_private',
        'lead_status_id',
    ];

    protected $appends = [
        'source',
    ];

    // accessors and mutators
    public function getSourceAttribute()
    {
        return $this->gtm_source . ' ' . $this->gtm_medium . ' ' . $this->gtm_campaign;
    }

    // relations
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_lead_pivots');
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    // scopes
    #[Scope]
    protected function isPrivate(Builder $query, bool $boolean = true)
    {
        $query->where('is_private', $boolean);
    }
}
