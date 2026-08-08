<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleId = Role::where('name', 'super_admin_technique')->value('id');

        User::updateOrCreate(
            ['email' => 'admin@minechain.africa'],
            [
                'organization_id' => null,
                'role_id' => $roleId,
                'name' => 'Super Admin Technique',
                'password' => Hash::make('MotDePasseTest2026'),
                'status' => 'active',
            ]
        );
    }
}