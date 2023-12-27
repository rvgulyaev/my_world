<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Zz1234567'),
            'remember_token' => Str::random(10),
        ])->assignRole('admin');
        User::create([
            'name' => 'Moderator',
            'email' => 'moderator@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Zz1234567'),
            'remember_token' => Str::random(10),
        ])->assignRole('moderator');
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Zz1234567'),
            'remember_token' => Str::random(10),
        ])->assignRole('user');
    }
}
