<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory(9)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin Isza',
            'email' => 'isza@pos.com',
            'password' => Hash::make('admin123'),
            'phone' => '08986110191',
            'roles' => 'ADMIN',

        ]);
    }
}
