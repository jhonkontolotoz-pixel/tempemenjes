<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate agar aman saat di-seed ulang
        $admin   = Role::firstOrCreate(['name' => 'admin'],   ['guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'manager'], ['guard_name' => 'web']);
        $kasir   = Role::firstOrCreate(['name' => 'kasir'],   ['guard_name' => 'web']);
        $user    = Role::firstOrCreate(['name' => 'user'],    ['guard_name' => 'web']);

        // Assign default role 'user' ke semua user yang belum punya role
        User::whereNull('role_id')->each(fn($u) => $u->update(['role_id' => $user->id]));
    }
}
