<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name'     => 'Juan Dela Cruz',
                'username' => 'juandela2000',
                'email'    => 'juandela2000@example.com',
                'password' => 'wZB7WYn@nnzrGw4',
                'role'     => 'user',
            ],
            [
                'name'     => 'LANDBANK Admin',
                'username' => 'admin',
                'email'    => 'admin@landbank.gov.ph',
                'password' => 'admin12345',
                'role'     => 'admin',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrNew(['username' => $account['username']]);
            $user->forceFill([
                'name'              => $account['name'],
                'email'             => $account['email'],
                'password'          => Hash::make($account['password']),
                'role'              => $account['role'],
                'email_verified_at' => now(),
            ])->save();
        }
    }
}