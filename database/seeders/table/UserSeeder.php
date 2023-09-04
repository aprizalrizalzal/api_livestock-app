<?php

namespace Database\Seeders\table;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ]);

        $userAdmin->assignRole('admin');
        $userAdmin->givePermissionTo('owner');

        $userSellerSatu = User::create([
            'name' => 'Seller Satu',
            'email' => 'seller_satu@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ]);

        $userSellerSatu->assignRole('seller');
        $userSellerSatu->givePermissionTo('user');

        $userBuyerSatu = User::create([
            'name' => 'Buyer Satu',
            'email' => 'buyer_satu@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ]);

        $userBuyerSatu->assignRole('buyer');
        $userBuyerSatu->givePermissionTo('user');

        $userSellerDua = User::create([
            'name' => 'Seller Dua',
            'email' => 'seller_dua@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ]);

        $userSellerDua->assignRole('seller');
        $userSellerDua->givePermissionTo('user');

        $userBuyerDua = User::create([
            'name' => 'Buyer Dua',
            'email' => 'buyer_dua@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => bcrypt('password'),
        ]);

        $userBuyerDua->assignRole('buyer');
        $userBuyerDua->givePermissionTo('user');
    }
}
