<?php

namespace App\Models;

use App\Traits\Relations\HasManyLeadNotes;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    //
    use HasFactory, HasManyLeadNotes;

    protected $fillable = [
        'name',
        'email',
        'mobile_number',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'is_private',
        'lead_status_id',
    ];

    protected $appends = [
        'source',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    // accessors and mutators
    public function getSourceAttribute()
    {
        return $this->gtm_source . ' ' . $this->gtm_medium . ' ' . $this->gtm_campaign;
    }

    // relations
    public function users()
    {
        return $this->belongsToMany(User::class, 'assigned_leads');
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    // scopes
    public function scopeIsPrivate(Builder $query, bool $boolean = true)
    {
        $query->where('is_private', $boolean);
    }

    public function scopeIsCustomer(Builder $query)
    {
        $query->whereHas('leadStatus', fn ($query) => $query->where('slug', 'closed_won'));
    }

    public function scopeIsNotCustomer(Builder $query)
    {
        $query->whereHas('leadStatus', fn ($query) => $query->where('slug', '!=', 'closed_won'));
    }
}
