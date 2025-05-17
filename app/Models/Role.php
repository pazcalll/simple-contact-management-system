<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    //
    protected $guarded = ['id'];

    final const ROLE_ADMIN = 'admin';
    final const ROLE_MANAGER = 'manager';
    final const ROLE_SUPERVISOR = 'supervisor';
    final const ROLE_TEAM_LEADER = 'team-leader';
    final const ROLE_STAFF = 'staff';
    final const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MANAGER,
        self::ROLE_SUPERVISOR,
        self::ROLE_TEAM_LEADER,
        self::ROLE_STAFF,
    ];

    public function upline()
    {
        return $this->belongsTo(Role::class);
    }
}
