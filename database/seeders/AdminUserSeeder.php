<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@naoolift.com'],
            [
                'name' => 'ADMIN NAOOLIFT',
                'password' => Hash::make('zkiyh782782?'),
                'is_admin' => true,
            ]
        );
    }
}
