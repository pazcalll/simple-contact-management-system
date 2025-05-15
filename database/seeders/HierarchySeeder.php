<?php

namespace Database\Seeders;

use App\Models\Hierarchy;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $teamLeader = User::role(Role::ROLE_TEAM_LEADER)->first();
        $supervisor = User::role(Role::ROLE_SUPERVISOR)->first();
        $manager = User::role(Role::ROLE_MANAGER)->first();
        $staff = User::role(Role::ROLE_STAFF)->first();

        $hierarchies = [
            [
                'upline_id' => $supervisor->id,
                'downline_id' => $teamLeader->id,
            ],
            [
                'upline_id' => $manager->id,
                'downline_id' => $supervisor->id,
            ],
            [
                'upline_id' => $teamLeader->id,
                'downline_id' => $staff->id,
            ]
        ];

        foreach ($hierarchies as $hierarchy) {
            Hierarchy::create($hierarchy);
        }

        $staffs = User::role(Role::ROLE_STAFF)->get();
        foreach ($staffs as $key => $staff) {
            Hierarchy::create([
                'upline_id' => $teamLeader->id,
                'downline_id' => $staff->id
            ]);
        }
    }
}
