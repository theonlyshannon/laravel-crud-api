<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => 'superadmin@app.com',
            'password' => bcrypt('password'),
        ]);

        $user->superAdmin()->create([
            'code' => 'SA001',
            'name' => 'Super Admin',
        ]);

        $user->assignRole('super-admin');
    }
}
