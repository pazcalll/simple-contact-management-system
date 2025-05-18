<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gtm_source',
        'gtm_medium',
        'gtm_campaign',
        'status',
    ];

    protected $appends = [
        'source',
    ];

    public function getSourceAttribute()
    {
        return $this->gtm_source . ' ' . $this->gtm_medium . ' ' . $this->gtm_campaign;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'assignees');
    }
}
