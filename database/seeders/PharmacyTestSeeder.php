<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PharmacyTestSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        User::truncate();
        Pharmacy::truncate();
        Product::truncate();

        // ========== 1. Create Pharmacy Owners ==========
        $owners = [];
        
        $ownerData = [
            ['nom' => 'Benali', 'prenom' => 'Ahmed', 'email' => 'owner1@pharmacy.dz', 'pharmacy_name' => 'Pharmacie Hydra Centre'],
            ['nom' => 'Rebai', 'prenom' => 'Fatima', 'email' => 'owner2@pharmacy.dz', 'pharmacy_name' => 'Pharmacie El Biar'],
        ];

        foreach ($ownerData as $data) {
            $owner = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'num_tel' => '0550000000',
                'wilaya' => 'Alger',
                'commune' => 'Hydra',
                'date_naissance' => '1980-01-15',
                'role' => 'owner',
                'is_verified' => true,
                'pharmacy_id' => uniqid('pharm_'),
                'role_profile' => [
                    'nom_pharmacie' => $data['pharmacy_name'],
                ]
            ]);
            $owners[] = $owner;
        }

        // ========== 2. Create Pharmacy Sellers ==========
        $sellers = [];
        
        $sellerData = [
            ['nom' => 'Mohamed', 'prenom' => 'Ali', 'email' => 'seller1@pharmacy.dz', 'owner_idx' => 0],
            ['nom' => 'Amina', 'prenom' => 'Youssef', 'email' => 'seller2@pharmacy.dz', 'owner_idx' => 0],
            ['nom' => 'Karim', 'prenom' => 'Hassan', 'email' => 'seller3@pharmacy.dz', 'owner_idx' => 1],
        ];

        foreach ($sellerData as $data) {
            $seller = User::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'num_tel' => '0550000001',
                'wilaya' => 'Alger',
                'commune' => 'Hydra',
                'date_naissance' => '1990-01-15',
                'role' => 'seller',
                'is_verified' => true,
                'pharmacy_id' => $owners[$data['owner_idx']]->pharmacy_id,
            ]);
            $sellers[] = $seller;
        }

        // ========== 3. Create Test Clients ==========
        User::create([
            'nom' => 'Patient',
            'prenom' => 'Test',
            'email' => 'patient@test.dz',
            'password' => Hash::make('password123'),
            'num_tel' => '0550000099',
            'wilaya' => 'Alger',
            'commune' => 'Hydra',
            'date_naissance' => '1995-05-10',
            'role' => 'client',
            'is_verified' => true,
        ]);

        // ========== 4. Create Products for Each Pharmacy ==========
        $medications = [
            ['name' => 'Paracetamol 500mg', 'description' => 'Pain reliever and fever reducer', 'price' => 150.00, 'stock' => 50],
            ['name' => 'Amoxicillin 250mg', 'description' => 'Antibiotic medication', 'price' => 450.00, 'stock' => 30],
            ['name' => 'Aspirin 100mg', 'description' => 'Blood thinner and pain reliever', 'price' => 200.00, 'stock' => 80],
            ['name' => 'Ibuprofen 400mg', 'description' => 'Anti-inflammatory', 'price' => 350.00, 'stock' => 45],
            ['name' => 'Cough Syrup', 'description' => 'Respiratory support', 'price' => 500.00, 'stock' => 25],
            ['name' => 'Vitamin D3 1000IU', 'description' => 'Vitamin supplement', 'price' => 600.00, 'stock' => 100],
        ];

        foreach ($owners as $owner) {
            foreach ($medications as $med) {
                Product::create([
                    'pharmacy_id' => $owner->pharmacy_id,
                    'name' => $med['name'],
                    'description' => $med['description'],
                    'sku' => 'SKU-' . strtoupper(substr($med['name'], 0, 3)) . '-' . rand(1000, 9999),
                    'price' => $med['price'],
                    'stock' => $med['stock'],
                ]);
            }
        }

        $this->command->info('✓ Pharmacy test data seeded successfully!');
        $this->command->info('Test Credentials:');
        $this->command->line('  Owner 1: owner1@pharmacy.dz / password123');
        $this->command->line('  Owner 2: owner2@pharmacy.dz / password123');
        $this->command->line('  Seller 1: seller1@pharmacy.dz / password123');
        $this->command->line('  Seller 2: seller2@pharmacy.dz / password123');
        $this->command->line('  Seller 3: seller3@pharmacy.dz / password123');
        $this->command->line('  Patient: patient@test.dz / password123');
    }
}
