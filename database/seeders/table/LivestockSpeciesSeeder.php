<?php

namespace Database\Seeders\table;

use App\Models\LivestockSpecies;
use App\Models\LivestockType;
use Illuminate\Database\Seeder;

class LivestockSpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sapiLiveStockType = LivestockType::where('name', 'Sapi')->first();

        LivestockSpecies::create(
            [
                'livestock_type_id' => $sapiLiveStockType->id,
                'name' => 'Bali',
            ],
        );

        LivestockSpecies::create(
            [
                'livestock_type_id' => $sapiLiveStockType->id,
                'name' => 'Madura',
            ],
        );

        LivestockSpecies::create(
            [
                'livestock_type_id' => $sapiLiveStockType->id,
                'name' => 'Limousin',
            ]
        );

        $kambingLiveStockType = LiveStockType::where('name', 'Kambing')->first();

        LivestockSpecies::create(
            [
                'livestock_type_id' => $kambingLiveStockType->id,
                'name' => 'Boer',
            ],
        );

        LivestockSpecies::create(
            [
                'livestock_type_id' => $kambingLiveStockType->id,
                'name' => 'Etawa',
            ],
        );

        LivestockSpecies::create(
            [
                'livestock_type_id' => $kambingLiveStockType->id,
                'name' => 'Kacang',
            ]
        );
    }
}
