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
    }
}
