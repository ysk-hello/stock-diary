<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'test',
                'role' => 'admin',
                'email' => 'test@test.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make('hogehoge')
            ],
            [
                'name' => 'guest',
                'role' => '',
                'email' => 'guest@test.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => Hash::make('hogehoge')
            ]
        ];

        foreach($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
