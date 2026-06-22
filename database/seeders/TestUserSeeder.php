<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear out old test users first so we don't duplicate
        User::truncate();

        // 1. Pharmacy Owner Profile Document
        User::create([
            'nom' => 'Benali',
            'prenom' => 'Mohamed',
            'email' => 'owner@pharmacy.com',
            'password' => Hash::make('password123'),
            'num_tel' => '0550112233',
            'wilaya' => 'Algiers',
            'commune' => 'Birkhadem',
            'date_naissance' => '1985-04-12',
            'is_verified' => true,
            'role_profile' => [
                'role' => 'pharmacy_owner',
                'pharmacy_name' => 'El Chifa Pharmacy',
                'license_number' => 'PH-2026-991A'
            ]
        ]);

        // 2. Pharmacy Seller Profile Document
        User::create([
            'nom' => 'Zidane',
            'prenom' => 'Sarah',
            'email' => 'seller@pharmacy.com',
            'password' => Hash::make('password123'),
            'num_tel' => '0661445566',
            'wilaya' => 'Algiers',
            'commune' => 'Kouba',
            'date_naissance' => '1998-09-23',
            'is_verified' => true,
            'role_profile' => [
                'role' => 'seller',
                'assigned_pharmacy_id' => 'mock_id_123'
            ]
        ]);

        // 3. Client Profile Document
        User::create([
            'nom' => 'Mansouri',
            'prenom' => 'Anis',
            'email' => 'client@example.com',
            'password' => Hash::make('password123'),
            'num_tel' => '0770998877',
            'wilaya' => 'Blida',
            'commune' => 'Ouled Yaïch',
            'date_naissance' => '2002-11-05',
            'is_verified' => false, // Pending verification
            'role_profile' => [
                'role' => 'client',
            ]
        ]);
    }
}