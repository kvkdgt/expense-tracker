<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'ketu@karmtrack.in'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Ketul@2000'),
                'theme_preference' => 'dark',
                'is_admin' => true,
            ]
        );
    }
}

