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

    /**
     * Get all uplines (ancestors) of a user in the hierarchy.
     *
     * @param int|string|User|null $user
     * @return Collection
     */
    public function getAllUplines(int|string|User|null $user): Collection
    {
        if (!$user) throw new \InvalidArgumentException('User must be provided');

        // Get the User instance
        if ($user instanceof User) {
            $currentUser = $user;
        } elseif (is_int($user) || is_string($user)) {
            $currentUser = User::find($user);
            if (!$currentUser) return new Collection();
        } else {
            throw new \InvalidArgumentException('Invalid user type provided');
        }

        $uplines = new Collection();
        while ($currentUser->upline_id) {
            $upline = User::find($currentUser->upline_id);
            if (!$upline) break;
            $uplines->push($upline);
            $currentUser = $upline;
        }

        return $uplines;
    }
}
