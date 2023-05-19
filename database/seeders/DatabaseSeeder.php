<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserActive;
use App\Models\UserBank;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Seed user_actives table
        $userActives = [
            [
                'phone_number' => false,
                'email' => false,
                'id_card' => false,
                'tax_registration_number' => false,
                'is_deleted' => false
            ],
            [
                'phone_number' => false,
                'email' => false,
                'id_card' => false,
                'tax_registration_number' => false,
                'is_deleted' => false
            ],
            [
                'phone_number' => false,
                'email' => false,
                'id_card' => false,
                'tax_registration_number' => false,
                'is_deleted' => false
            ],
        ];

        foreach ($userActives as $userActive) {
            UserActive::create($userActive);
        }

        // Seed user_banks table
        $userBanks = [
            [
                'bank_name' => 'Bank of America',
                'account_number' => '1234567890',
                'account_name' => 'John Doe',
                'is_deleted' => false,
            ],
            [
                'bank_name' => 'Chase',
                'account_number' => '0987654321',
                'account_name' => 'Jane Smith',
                'is_deleted' => false,
            ],
            [
                'bank_name' => 'Wells Fargo',
                'account_number' => '4567890123',
                'account_name' => 'Bob Johnson',
                'is_deleted' => false,
            ],
        ];

        foreach ($userBanks as $userBank) {
            UserBank::create($userBank);
        }

        // Seed users table
        $users = [
            [
                'name' => 'John Doe',
                'date_of_birth' => '1990-01-01',
                'full_name' => 'John Smith Doe',
                'gender' => 'M',
                'address' => '123 Main St, Anytown, USA',
                'phone_number' => '1234567890',
                'id_card' => '1234567890',
                'tax_registration_number' => '1234567890',
                'email' => 'investor@example.com',
                'password' => bcrypt('password'),
                'employment_status' => 'Full-time',
                'authorization_level' => '1',
                'id_user_active' => UserActive::inRandomOrder()->first()->id,
                'id_user_bank' => UserBank::inRandomOrder()->first()->id,
                'business_certificate' => null,
                'is_deleted' => false
            ],
            [
                'name' => 'Jane Smith',
                'date_of_birth' => '1985-05-05',
                'full_name' => 'Jane Ann Smith',
                'gender' => 'F',
                'address' => '456 Oak St, Anytown, USA',
                'phone_number' => '0987654321',
                'id_card' => '0987654321',
                'tax_registration_number' => '0987654321',
                'email' => 'umkm@example.com',
                'password' => bcrypt('password'),
                'employment_status' => 'Part-time',
                'authorization_level' => '2',
                'id_user_active' => UserActive::inRandomOrder()->first()->id,
                'id_user_bank' => UserBank::inRandomOrder()->first()->id,
                'business_certificate' => null,
                'is_deleted' => false
            ],
            [
                'name' => 'Bob Johnson',
                'date_of_birth' => '1975-12-31',
                'full_name' => 'Robert Johnson',
                'gender' => 'M',
                'address' => '789 Elm St, Anytown, USA',
                'phone_number' => '5555555555',
                'id_card' => '5555555555',
                'tax_registration_number' => '5555555555',
                'email' => 'reviewer@example.com',
                'password' => bcrypt('password'),
                'employment_status' => 'Full-time',
                'authorization_level' => '3',
                'id_user_active' => UserActive::inRandomOrder()->first()->id,
                'id_user_bank' => UserBank::inRandomOrder()->first()->id,
                'business_certificate' => null,
                'is_deleted' => false
            ],
        ];


        foreach ($users as $user) {
            User::create($user);
        }

    }
}
