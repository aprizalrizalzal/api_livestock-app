<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\table\LivestockSeeder;
use Database\Seeders\table\LivestockSpeciesSeeder;
use Database\Seeders\table\LivestockTypeSeeder;
use Database\Seeders\table\PermissionSeeder;
use Database\Seeders\table\ProfileSeeder;
use Database\Seeders\table\RoleSeeder;
use Database\Seeders\table\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProfileSeeder::class);
        $this->call(LivestockTypeSeeder::class);
        $this->call(LivestockSpeciesSeeder::class);
        $this->call(LivestockSeeder::class);
    }
}
