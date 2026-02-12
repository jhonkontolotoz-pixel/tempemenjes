<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $kasir = Role::create(['name' => 'kasir']);
        $user = Role::create(['name' => 'user']);

        // Assign role ke user pertama (biasanya admin)
        $userAdmin = User::first();
        if ($userAdmin) {
            $userAdmin->role()->associate($admin)->save();
        }

        // (Opsional) assign role ke semua user lain dengan default 'user'
        User::whereNull('role_id')->each(function ($u) use ($user) {
            $u->role()->associate($user)->save();
        });
    }
}