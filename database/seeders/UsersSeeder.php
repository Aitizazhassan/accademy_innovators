<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $adminUser = User::create([
            'first_name' => 'Manooj',
            'last_name' => 'Guru',
            'name' => 'Manooj Guru',
            'email' => 'admin@accademy.com',
            'phone_number' => '2013514000',
            'password' => bcrypt('12345678'),
            'status' => 'approved',
        ]);

        // Assign the 'admin' role to the admin
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser->assignRole($adminRole);
    }
}
