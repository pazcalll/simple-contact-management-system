<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffs = User::factory()
            ->state(['upline_id' => 4])
            ->count(20)
            ->create();
        foreach ($staffs as $key => $staff) {
            $staff->assignRole(Role::ROLE_STAFF);
        }
    }
}
