<?php

namespace Database\Seeders\table;

use App\Models\LivestockType;
use Illuminate\Database\Seeder;

class LivestockTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LivestockType::create(
            [
                'name' => 'Sapi'
            ],
        );

        LivestockType::create(
            [
                'name' => 'Kambing'
            ],

        );

        LivestockType::create(
            [
                'name' => 'Ayam'
            ],

        );

        LivestockType::create(
            [
                'name' => 'Itik'
            ],

        );

        LivestockType::create(
            [
                'name' => 'Lain-nya'
            ],
        );
    }
}
