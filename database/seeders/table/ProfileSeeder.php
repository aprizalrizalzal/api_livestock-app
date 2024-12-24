<?php

namespace Database\Seeders\table;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        Profile::create([
            'user_id' => $adminUser->id,
            'name' => 'Admin',
            'gender' => 'Male',
            'phone_number' => '087765543321',
            'phone_number_verified_at' => Carbon::now(),
            'address' => 'Lombok Timur',
        ]);

        $sellerUserSatu = User::where('email', 'seller_satu@example.com')->first();

        Profile::create([
            'user_id' => $sellerUserSatu->id,
            'name' => 'Seller Satu',
            'gender' => 'Male',
            'phone_number' => '087765543322',
            'phone_number_verified_at' => Carbon::now(),
            'address' => 'Banyu Urip, Gerung, Lombok Barat',
        ]);

        $buyerUserSatu = User::where('email', 'buyer_satu@example.com')->first();

        Profile::create([
            'user_id' => $buyerUserSatu->id,
            'name' => 'Buyer Satu',
            'gender' => 'Male',
            'phone_number' => '087765544444',
            'phone_number_verified_at' => Carbon::now(),
            'address' => 'Aik Darek, Batukliang, Lombok Tengah',
        ]);

        $sellerUserDua = User::where('email', 'seller_dua@example.com')->first();

        Profile::create([
            'user_id' => $sellerUserDua->id,
            'name' => 'Seller Dua',
            'gender' => 'Male',
            'phone_number' => '087765543222',
            'phone_number_verified_at' => Carbon::now(),
            'address' => 'Lingsar, Narmada, Lombok Barat',
        ]);

        $buyerUserDua = User::where('email', 'buyer_dua@example.com')->first();

        Profile::create([
            'user_id' => $buyerUserDua->id,
            'name' => 'Buyer Dua',
            'gender' => 'Male',
            'phone_number' => '087765544244',
            'phone_number_verified_at' => Carbon::now(),
            'address' => 'Barabali, Batukliang, Lombok Tengah',
        ]);
    }
}
