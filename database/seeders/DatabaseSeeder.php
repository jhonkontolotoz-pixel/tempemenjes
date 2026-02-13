<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles dulu
        $this->call(RoleSeeder::class);

        $adminRole = \App\Models\Role::where('name', 'admin')->first();

        // Buat user admin (firstOrCreate agar tidak duplikat)
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'role_id'  => $adminRole?->id,
            ]
        );

        // Buat user demo lainnya
        User::firstOrCreate(
            ['email' => 'manager@demo.com'],
            [
                'name'     => 'Manager Demo',
                'password' => Hash::make('password'),
                'role_id'  => \App\Models\Role::where('name', 'manager')->first()?->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@demo.com'],
            [
                'name'     => 'Kasir Demo',
                'password' => Hash::make('password'),
                'role_id'  => \App\Models\Role::where('name', 'kasir')->first()?->id,
            ]
        );
    }
}