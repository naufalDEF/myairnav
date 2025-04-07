<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@myairnav.com',
            'password' => Hash::make('airnavmyadmin992'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Superadmin2',
            'email' => 'superadmin2@myairnav.com',
            'password' => Hash::make('superadmin2airnav992'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Superadmin3',
            'email' => 'superadmin3@myairnav.com',
            'password' => Hash::make('superadmin3airnav992'),
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Superadmin4',
            'email' => 'superadmin4@myairnav.com',
            'password' => Hash::make('superadmin4airnav992'),
            'role' => 'superadmin',
        ]);
    }
}
