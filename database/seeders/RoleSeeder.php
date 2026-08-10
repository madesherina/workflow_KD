<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Creator'],
            ['role_name' => 'Verifier'],
            ['role_name' => 'Publisher'],
            ['role_name' => 'Super Admin'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }
    }
}
