<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
            ],
            [
                'name' => 'team leader',
                'email' => 'team-leader@trash-mail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'team-leader',
            ],
            [
                'name' => 'supervisor',
                'email' => 'supervisor@trash-mail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'supervisor',
            ],
            [
                'name' => 'manager',
                'email' => 'manager@trash-mail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'manager',
            ],
            [
                'name' => 'staff',
                'email' => 'staff@trash-mail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'staff',
            ],
        ];

        foreach ($users as $user) {
            $user = User::create($user);
            $user->assignRole($user['role']);
        }
    }
}
