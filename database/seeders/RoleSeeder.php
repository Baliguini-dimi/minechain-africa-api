<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin_technique', 'level' => 100],
            ['name' => 'super_admin_gouvernemental', 'level' => 90],
            ['name' => 'admin_organisation', 'level' => 70],
            ['name' => 'superviseur', 'level' => 50],
            ['name' => 'agent_checkpoint', 'level' => 30],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                array_merge($role, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}