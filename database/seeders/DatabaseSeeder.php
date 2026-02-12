<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil RoleSeeder dulu agar tabel roles terisi
        $this->call(RoleSeeder::class);

        // Buat user admin dan assign role_id = 1 (admin)
        $admin = User::factory()->create([
            'name'  => 'Administrator',
            'email' => 'admin@admin.com',
        ]);

        // Cari role admin (pastikan sudah di-seed oleh RoleSeeder)
        $roleAdmin = \App\Models\Role::where('name', 'admin')->first();
        if ($roleAdmin) {
            $admin->role()->associate($roleAdmin)->save();
        }

        // Optional: assign default role 'user' ke semua user lain
        // (jika sudah ada user lain)
    }
}
