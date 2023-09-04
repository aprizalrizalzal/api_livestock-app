<?php

namespace Database\Seeders\table;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            'name' => 'owner',
        ]);

        Permission::create([
            'name' => 'user',
        ]);

        Permission::create([
            'name' => 'guest',
        ]);
    }
}
