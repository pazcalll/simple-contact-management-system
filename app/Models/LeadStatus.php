<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadStatus extends Model
{
    //
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'slug',
        'group',
        'color',
        'is_active',
        'sort_order',
        'description',
    ];
}
