<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Pharmacy;
use App\Models\Product;
use Carbon\Carbon;

#[Signature('db:seed-additional-products')]
#[Description('Append 20 additional unique products to every existing pharmacy')]
class SeedAdditionalProductsCommand extends Command
{
    private $additionalMedications = [
        ['name' => 'Humex Rhume', 'description' => 'Cold and flu relief medication.', 'category' => 'Analgesics', 'laboratory' => 'Urgo', 'dosage' => 'Day/Night', 'form' => 'Capsules', 'price_min' => 450, 'price_max' => 550],
        ['name' => 'Advil 400mg', 'description' => 'Ibuprofen for pain relief and inflammation.', 'category' => 'Analgesics', 'laboratory' => 'Pfizer', 'dosage' => '400mg', 'form' => 'Tablets', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Biseptine', 'description' => 'Local antiseptic for superficial skin lesions.', 'category' => 'Drug', 'laboratory' => 'Bayer', 'dosage' => '250ml', 'form' => 'Spray', 'price_min' => 300, 'price_max' => 400],
        ['name' => 'Loperamide 2mg', 'description' => 'Anti-diarrheal medication.', 'category' => 'Drug', 'laboratory' => 'Saidal', 'dosage' => '2mg', 'form' => 'Capsules', 'price_min' => 150, 'price_max' => 250],
        ['name' => 'Magnesium B6', 'description' => 'Supplement for fatigue and muscle cramps.', 'category' => 'Vitamins', 'laboratory' => 'Biogaran', 'dosage' => '470mg/5mg', 'form' => 'Tablets', 'price_min' => 400, 'price_max' => 600],
        ['name' => 'Vitamin C 1000mg', 'description' => 'Ascorbic acid for immune system support.', 'category' => 'Vitamins', 'laboratory' => 'UPSA', 'dosage' => '1000mg', 'form' => 'Effervescent tablets', 'price_min' => 350, 'price_max' => 450],
        ['name' => 'Euphytose', 'description' => 'Herbal medicine for mild anxiety and sleep disorders.', 'category' => 'Vitamins', 'laboratory' => 'Bayer', 'dosage' => 'Plant extract', 'form' => 'Tablets', 'price_min' => 500, 'price_max' => 700],
        ['name' => 'Daflon 500mg', 'description' => 'Treatment for venous circulation disorders.', 'category' => 'Drug', 'laboratory' => 'Servier', 'dosage' => '500mg', 'form' => 'Film-coated tablets', 'price_min' => 800, 'price_max' => 1000],
        ['name' => 'Kardegic 75mg', 'description' => 'Acetylsalicylic acid for cardiovascular prevention.', 'category' => 'Cardiovascular', 'laboratory' => 'Sanofi', 'dosage' => '75mg', 'form' => 'Powder', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Tahor 20mg', 'description' => 'Atorvastatin for lowering cholesterol.', 'category' => 'Cardiovascular', 'laboratory' => 'Pfizer', 'dosage' => '20mg', 'form' => 'Tablets', 'price_min' => 1500, 'price_max' => 1800],
        ['name' => 'Meteospasmyl', 'description' => 'For the symptomatic treatment of digestive pain with bloating.', 'category' => 'Drug', 'laboratory' => 'Mayoly Spindler', 'dosage' => 'Alverine/Simeticone', 'form' => 'Capsules', 'price_min' => 450, 'price_max' => 600],
        ['name' => 'Aerius 5mg', 'description' => 'Desloratadine antihistamine for allergies.', 'category' => 'Antihistamines', 'laboratory' => 'MSD', 'dosage' => '5mg', 'form' => 'Tablets', 'price_min' => 400, 'price_max' => 550],
        ['name' => 'Ventoline 100mcg', 'description' => 'Salbutamol inhaler for asthma relief.', 'category' => 'Respiratory', 'laboratory' => 'GSK', 'dosage' => '100mcg', 'form' => 'Inhaler', 'price_min' => 450, 'price_max' => 600],
        ['name' => 'Tardyferon 80mg', 'description' => 'Iron supplement for anemia.', 'category' => 'Vitamins', 'laboratory' => 'Pierre Fabre', 'dosage' => '80mg', 'form' => 'Tablets', 'price_min' => 300, 'price_max' => 450],
        ['name' => 'Levothyrox 50mcg', 'description' => 'Levothyroxine for hypothyroidism.', 'category' => 'Endocrinology', 'laboratory' => 'Merck', 'dosage' => '50mcg', 'form' => 'Tablets', 'price_min' => 200, 'price_max' => 350],
        ['name' => 'Glucophage 1000mg', 'description' => 'Metformin for type 2 diabetes management.', 'category' => 'Endocrinology', 'laboratory' => 'Merck', 'dosage' => '1000mg', 'form' => 'Film-coated tablets', 'price_min' => 350, 'price_max' => 500],
        ['name' => 'Locabiotal', 'description' => 'Fusafungine antibacterial spray for mouth and throat.', 'category' => 'Drug', 'laboratory' => 'Servier', 'dosage' => '50mg', 'form' => 'Spray', 'price_min' => 500, 'price_max' => 700],
        ['name' => 'Sterimar', 'description' => 'Isotonic seawater nasal spray.', 'category' => 'Respiratory', 'laboratory' => 'Sofibel', 'dosage' => '100ml', 'form' => 'Spray', 'price_min' => 600, 'price_max' => 800],
        ['name' => 'Biafine', 'description' => 'Emulsion for the treatment of superficial burns.', 'category' => 'Dermatology', 'laboratory' => 'Johnson & Johnson', 'dosage' => '93g', 'form' => 'Cream', 'price_min' => 400, 'price_max' => 550],
        ['name' => 'Hexomedine', 'description' => 'Topical antiseptic for skin infections.', 'category' => 'Dermatology', 'laboratory' => 'Sanofi', 'dosage' => '1/1000', 'form' => 'Solution', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Pivalone 1%', 'description' => 'Tixocortol nasal spray for allergic rhinitis.', 'category' => 'Respiratory', 'laboratory' => 'Pfizer', 'dosage' => '1%', 'form' => 'Suspension', 'price_min' => 350, 'price_max' => 450],
        ['name' => 'Imodium 2mg', 'description' => 'Loperamide used for treating diarrhea.', 'category' => 'Drug', 'laboratory' => 'Janssen', 'dosage' => '2mg', 'form' => 'Capsules', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Debridat 100mg', 'description' => 'Trimebutine for irritable bowel syndrome.', 'category' => 'Drug', 'laboratory' => 'Pfizer', 'dosage' => '100mg', 'form' => 'Tablets', 'price_min' => 300, 'price_max' => 450],
        ['name' => 'Ginkor Fort', 'description' => 'Ginkgo biloba extract for venous insufficiency.', 'category' => 'Drug', 'laboratory' => 'Beaufour Ipsen', 'dosage' => 'Extract', 'form' => 'Capsules', 'price_min' => 600, 'price_max' => 800],
        ['name' => 'Arnica Montana 9CH', 'description' => 'Homeopathic remedy for muscle pain and bruising.', 'category' => 'Homeopathy', 'laboratory' => 'Boiron', 'dosage' => '9CH', 'form' => 'Granules', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Nurofen 200mg', 'description' => 'Ibuprofen for targeted pain relief.', 'category' => 'Analgesics', 'laboratory' => 'Reckitt Benckiser', 'dosage' => '200mg', 'form' => 'Tablets', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Oramox 500mg', 'description' => 'Amoxicillin antibiotic.', 'category' => 'Antibiotics', 'laboratory' => 'Saidal', 'dosage' => '500mg', 'form' => 'Capsules', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Diclofenac 50mg', 'description' => 'NSAID used for inflammation and pain.', 'category' => 'Analgesics', 'laboratory' => 'Saidal', 'dosage' => '50mg', 'form' => 'Tablets', 'price_min' => 150, 'price_max' => 250],
        ['name' => 'Atarax 25mg', 'description' => 'Hydroxyzine used as an antihistamine and anxiolytic.', 'category' => 'Antihistamines', 'laboratory' => 'UCB', 'dosage' => '25mg', 'form' => 'Tablets', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Flagyl 500mg', 'description' => 'Metronidazole antibiotic and antiprotozoal.', 'category' => 'Antibiotics', 'laboratory' => 'Sanofi', 'dosage' => '500mg', 'form' => 'Tablets', 'price_min' => 350, 'price_max' => 500],
        ['name' => 'Seroplex 10mg', 'description' => 'Escitalopram antidepressant (SSRI).', 'category' => 'Anti dépresseurs', 'laboratory' => 'Lundbeck', 'dosage' => '10mg', 'form' => 'Tablets', 'price_min' => 1500, 'price_max' => 1800],
        ['name' => 'Rhinadvil', 'description' => 'Ibuprofen and pseudoephedrine for cold symptoms.', 'category' => 'Analgesics', 'laboratory' => 'Pfizer', 'dosage' => '200mg/30mg', 'form' => 'Tablets', 'price_min' => 450, 'price_max' => 600],
    ];

    public function handle()
    {
        $this->info('Starting appending of 20 unique products per pharmacy...');

        $pharmacies = Pharmacy::all();
        if ($pharmacies->isEmpty()) {
            $this->error('No pharmacies found in the database. Please register a pharmacy first.');
            return;
        }

        $this->info("Found {$pharmacies->count()} pharmacies. Adding 20 new products to each...");

        $totalInserted = 0;

        foreach ($pharmacies as $pharmacy) {
            $this->info("Processing pharmacy: {$pharmacy->nom_pharmacie}");

            $shuffledMeds = $this->additionalMedications;
            shuffle($shuffledMeds);

            $insertedForThisPharmacy = 0;

            foreach ($shuffledMeds as $med) {
                if ($insertedForThisPharmacy >= 20) {
                    break;
                }

                // Check if product already exists in this pharmacy
                $exists = Product::where('pharmacy_id', $pharmacy->_id)
                    ->where('name', $med['name'])
                    ->exists();

                if ($exists) {
                    continue; // Skip if duplicate
                }

                $price = rand($med['price_min'], $med['price_max']);
                $stock = rand(0, 10) === 0 ? 0 : rand(5, 100); 
                $isPublic = rand(1, 10) <= 9; // Most products are public
                
                $monthsToAdd = rand(-1, 36); // Some expired, mostly in future
                $expirationDate = Carbon::now()->addMonths($monthsToAdd)->startOfDay();
                
                $sku = strtoupper(substr($med['category'], 0, 3)) . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

                Product::create([
                    'pharmacy_id' => $pharmacy->_id,
                    'name' => $med['name'],
                    'description' => $med['description'],
                    'category' => $med['category'],
                    'dosage' => $med['dosage'],
                    'laboratory' => $med['laboratory'],
                    'is_public' => $isPublic,
                    'visibility_status' => $isPublic ? 'public' : 'private',
                    'price' => (float) $price,
                    'stock' => (int) $stock,
                    'min_threshold' => 10,
                    'expiration_date' => $expirationDate,
                    'sku' => $sku,
                    'metadata' => [
                        'pharmaceutical_form' => $med['form'],
                        'origin' => 'Algérie'
                    ],
                ]);

                $insertedForThisPharmacy++;
                $totalInserted++;
            }
            
            $this->info("Added {$insertedForThisPharmacy} unique products to {$pharmacy->nom_pharmacie}.");
        }

        $this->info("✅ Successfully updated {$pharmacies->count()} pharmacies with a total of {$totalInserted} new products.");
    }
}
