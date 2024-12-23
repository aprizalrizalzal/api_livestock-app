<?php

namespace Database\Seeders\table;

use App\Models\Livestock;
use App\Models\LivestockSpecies;
use App\Models\LivestockType;
use App\Models\Profile;
use Illuminate\Database\Seeder;

class LivestockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellerProfileSatu = Profile::where('phone_number', '087765543322')->first();
        $sapiLivestockType = LivestockType::where('name', 'Sapi')->first();
        $baliLivestockSpecies = LivestockSpecies::where('name', 'Bali')->first();

        Livestock::create([
            'profile_id' => $sellerProfileSatu->id,
            'livestock_type_id' => $sapiLivestockType->id,
            'livestock_species_id' => $baliLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_1.jpg',
            'gender' => 'Male',
            'weight' => 11,
            'age' => 1,
            'price' => 10000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileSatu->id,
            'livestock_type_id' => $sapiLivestockType->id,
            'livestock_species_id' => $baliLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_2.jpg',
            'gender' => 'Male',
            'weight' => 12,
            'age' => 2,
            'price' => 12000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileSatu->id,
            'livestock_type_id' => $sapiLivestockType->id,
            'livestock_species_id' => $baliLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_3.jpeg',
            'gender' => 'Male',
            'weight' => 13,
            'age' => 3,
            'price' => 14000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileSatu->id,
            'livestock_type_id' => $sapiLivestockType->id,
            'livestock_species_id' => $baliLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_4.jpg',
            'gender' => 'Male',
            'weight' => 14,
            'age' => 4,
            'price' => 16000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileSatu->id,
            'livestock_type_id' => $sapiLivestockType->id,
            'livestock_species_id' => $baliLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_5.jpg',
            'gender' => 'Male',
            'weight' => 15,
            'age' => 5,
            'price' => 18000000,
            'condition' => 'Baik',
        ]);

        $sellerProfileDua = Profile::where('phone_number', '087765543222')->first();
        $kambingTestLivestockType = LivestockType::where('name', 'Kambing')->first();
        $boerTestLivestockSpecies = LivestockSpecies::where('name', 'Boer')->first();

        Livestock::create([
            'profile_id' => $sellerProfileDua->id,
            'livestock_type_id' => $kambingTestLivestockType->id,
            'livestock_species_id' => $boerTestLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_6.jpg',
            'gender' => 'Male',
            'weight' => 16,
            'age' => 6,
            'price' => 20000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileDua->id,
            'livestock_type_id' => $kambingTestLivestockType->id,
            'livestock_species_id' => $boerTestLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_7.jpeg',
            'gender' => 'Male',
            'weight' => 17,
            'age' => 7,
            'price' => 22000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileDua->id,
            'livestock_type_id' => $kambingTestLivestockType->id,
            'livestock_species_id' => $boerTestLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_8.jpg',
            'gender' => 'Male',
            'weight' => 18,
            'age' => 8,
            'price' => 24000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileDua->id,
            'livestock_type_id' => $kambingTestLivestockType->id,
            'livestock_species_id' => $boerTestLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_9.jpg',
            'gender' => 'Male',
            'weight' => 19,
            'age' => 9,
            'price' => 26000000,
            'condition' => 'Baik',
        ]);

        Livestock::create([
            'profile_id' => $sellerProfileDua->id,
            'livestock_type_id' => $kambingTestLivestockType->id,
            'livestock_species_id' => $boerTestLivestockSpecies->id,
            'photo_url' => 'photos/livestock/livestock_10.jpg',
            'gender' => 'Male',
            'weight' => 110,
            'age' => 10,
            'price' => 28000000,
            'condition' => 'Baik',
        ]);
    }
}
