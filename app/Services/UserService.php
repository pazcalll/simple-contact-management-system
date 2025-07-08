<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getDownlines(int|string|User|null $user): Collection
    {
        if (!$user) throw new \InvalidArgumentException('User must be provided');

        if ($user instanceof User) return $user->downlines()->get();
        elseif (is_int($user) || is_string($user)) return User::where('upline_id', $user)->get();
        else throw new \InvalidArgumentException('Invalid user type provided');
    }

    public function getUpline(int|string|User|null $user): ?User
    {
        if (!$user) throw new \InvalidArgumentException('User must be provided');

        if ($user instanceof User) return $user->upline()->first();
        elseif (is_int($user) || is_string($user)) return User::find($user)?->upline()->first();
        else throw new \InvalidArgumentException('Invalid user type provided');
    }
}
