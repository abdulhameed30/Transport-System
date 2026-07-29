<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           User::create([
            'name' => 'Admin Manager',
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'role' => 'manager',
            'city_id' => null
       
        ]);
    }
}
