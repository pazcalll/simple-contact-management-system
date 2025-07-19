<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@trash-mail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'upline_id' => null,
            ],
        ];

        if (config('app.env') != 'production') {
            $users = [
                ...$users,
                [
                    'name' => 'manager',
                    'email' => 'manager@trash-mail.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'role' => 'manager',
                    'upline_id' => null,
                ],
                [
                    'name' => 'supervisor',
                    'email' => 'supervisor@trash-mail.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'role' => 'supervisor',
                    'upline_id' => 2,
                ],
                [
                    'name' => 'team leader',
                    'email' => 'team-leader@trash-mail.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'role' => 'team-leader',
                    'upline_id' => 3,
                ],
                [
                    'name' => 'staff',
                    'email' => 'staff@trash-mail.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'role' => 'staff',
                    'upline_id' => 4,
                ],
            ];
        }

        foreach ($users as $user) {
            $role = $user['role'];
            unset($user['role']);
            $user = User::create($user);
            $user->assignRole($role);
        }
    }
}
