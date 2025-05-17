<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // $roles = [
        //     'admin',
        //     'manager',
        //     'supervisor',
        //     'team-leader',
        //     'staff',
        // ];
        $roles = [
            [
                'name' => 'admin',
                'upline_id' => null,
            ],
            [
                'name' => 'manager',
                'upline_id' => null,
            ],
            [
                'name' => 'supervisor',
                'upline_id' => 2,
            ],
            [
                'name' => 'team-leader',
                'upline_id' => 3,
            ],
            [
                'name' => 'staff',
                'upline_id' => 4,
            ],
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role['name'],
                'upline_id' => $role['upline_id'],
            ]);
        }
    }
}
