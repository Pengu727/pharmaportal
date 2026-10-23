<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Pharmacy;
use App\Models\Product;
use Carbon\Carbon;

#[Signature('db:seed-inventory')]
#[Description('Populate the MongoDB database with realistic inventory data for existing pharmacies')]
class SeedInventoryCommand extends Command
{
    private $medications = [
        ['name' => 'Doliprane 1000mg', 'description' => 'Paracetamol used to treat pain and fever.', 'category' => 'Analgesics', 'laboratory' => 'Sanofi', 'dosage' => '1000mg', 'form' => 'Tablets', 'price_min' => 200, 'price_max' => 250],
        ['name' => 'Clamoxyl 1g', 'description' => 'Amoxicillin antibiotic used to treat bacterial infections.', 'category' => 'Antibiotics', 'laboratory' => 'GSK', 'dosage' => '1g', 'form' => 'Dispersible tablets', 'price_min' => 450, 'price_max' => 600],
        ['name' => 'Zyrtec 10mg', 'description' => 'Cetirizine used to relieve allergy symptoms.', 'category' => 'Antihistamines', 'laboratory' => 'UCB', 'dosage' => '10mg', 'form' => 'Film-coated tablets', 'price_min' => 300, 'price_max' => 350],
        ['name' => 'Voltarene 75mg', 'description' => 'Diclofenac nonsteroidal anti-inflammatory drug (NSAID).', 'category' => 'Analgesics', 'laboratory' => 'Novartis', 'dosage' => '75mg', 'form' => 'Injectable solution', 'price_min' => 250, 'price_max' => 400],
        ['name' => 'Spasfon 80mg', 'description' => 'Phloroglucinol used for the treatment of spasmodic pain.', 'category' => 'Anti spasmodique', 'laboratory' => 'Teva', 'dosage' => '80mg', 'form' => 'Tablets', 'price_min' => 180, 'price_max' => 220],
        ['name' => 'Lexomil 6mg', 'description' => 'Bromazepam used for the short-term treatment of severe anxiety.', 'category' => 'Anxiolytics', 'laboratory' => 'Roche', 'dosage' => '6mg', 'form' => 'Scored tablets', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Solupred 20mg', 'description' => 'Prednisolone corticosteroid used to reduce inflammation.', 'category' => 'Corticosteroids', 'laboratory' => 'Sanofi', 'dosage' => '20mg', 'form' => 'Effervescent tablets', 'price_min' => 400, 'price_max' => 500],
        ['name' => 'Zoloft 50mg', 'description' => 'Sertraline antidepressant of the SSRI class.', 'category' => 'Anti dépresseurs', 'laboratory' => 'Pfizer', 'dosage' => '50mg', 'form' => 'Capsules', 'price_min' => 1200, 'price_max' => 1500],
        ['name' => 'Omeprazole 20mg', 'description' => 'Proton pump inhibitor used to treat stomach issues.', 'category' => 'Inhibiteurs des pompes à Proton', 'laboratory' => 'Saidal', 'dosage' => '20mg', 'form' => 'Gastro-resistant capsules', 'price_min' => 300, 'price_max' => 450],
        ['name' => 'Augmentin 1g/125mg', 'description' => 'Amoxicillin and clavulanate potassium combination.', 'category' => 'Antibiotics', 'laboratory' => 'GSK', 'dosage' => '1g/125mg', 'form' => 'Powder', 'price_min' => 800, 'price_max' => 1100],
        ['name' => 'Aspegic 1000mg', 'description' => 'Aspirin (acetylsalicylic acid) for pain and fever.', 'category' => 'Analgesics', 'laboratory' => 'Sanofi', 'dosage' => '1000mg', 'form' => 'Powder', 'price_min' => 150, 'price_max' => 200],
        ['name' => 'Claratyne 10mg', 'description' => 'Loratadine antihistamine for allergies.', 'category' => 'Antihistamines', 'laboratory' => 'Bayer', 'dosage' => '10mg', 'form' => 'Tablets', 'price_min' => 350, 'price_max' => 450],
        ['name' => 'Xanax 0.5mg', 'description' => 'Alprazolam used to treat anxiety.', 'category' => 'Anxiolytics', 'laboratory' => 'Pfizer', 'dosage' => '0.5mg', 'form' => 'Tablets', 'price_min' => 300, 'price_max' => 400],
        ['name' => 'Smecta 3g', 'description' => 'Diosmectite used for acute diarrhea.', 'category' => 'Drug', 'laboratory' => 'Ipsen', 'dosage' => '3g', 'form' => 'Powder', 'price_min' => 250, 'price_max' => 300],
        ['name' => 'Maalox', 'description' => 'Antacid used to relieve heartburn.', 'category' => 'Drug', 'laboratory' => 'Sanofi', 'dosage' => 'N/A', 'form' => 'Chewable tablets', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Fervex', 'description' => 'Treatment of colds and flu-like symptoms.', 'category' => 'Analgesics', 'laboratory' => 'UPSA', 'dosage' => 'Adult', 'form' => 'Granules', 'price_min' => 300, 'price_max' => 400],
        ['name' => 'Rhinathiol', 'description' => 'Carbocisteine cough syrup.', 'category' => 'Drug', 'laboratory' => 'Sanofi', 'dosage' => '5%', 'form' => 'Syrup', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Amoxil 500mg', 'description' => 'Amoxicillin antibiotic.', 'category' => 'Antibiotics', 'laboratory' => 'GSK', 'dosage' => '500mg', 'form' => 'Capsules', 'price_min' => 300, 'price_max' => 450],
        ['name' => 'Motilium 10mg', 'description' => 'Domperidone used to relieve nausea and vomiting.', 'category' => 'Drug', 'laboratory' => 'Janssen', 'dosage' => '10mg', 'form' => 'Film-coated tablets', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Gaviscon', 'description' => 'Relieves symptoms of gastroesophageal reflux.', 'category' => 'Drug', 'laboratory' => 'Reckitt', 'dosage' => 'Suspension', 'form' => 'Liquid', 'price_min' => 350, 'price_max' => 500],
        ['name' => 'Zinnat 500mg', 'description' => 'Cefuroxime axetil antibiotic.', 'category' => 'Antibiotics', 'laboratory' => 'GSK', 'dosage' => '500mg', 'form' => 'Tablets', 'price_min' => 1200, 'price_max' => 1600],
        ['name' => 'Betadine 10%', 'description' => 'Povidone-iodine topical antiseptic.', 'category' => 'Drug', 'laboratory' => 'Meda', 'dosage' => '10%', 'form' => 'Solution', 'price_min' => 200, 'price_max' => 300],
        ['name' => 'Maxilase', 'description' => 'Alpha-amylase used for sore throats.', 'category' => 'Drug', 'laboratory' => 'Sanofi', 'dosage' => '200 U. CEIP/ml', 'form' => 'Syrup', 'price_min' => 250, 'price_max' => 350],
        ['name' => 'Dafalgan 1000mg', 'description' => 'Paracetamol.', 'category' => 'Analgesics', 'laboratory' => 'UPSA', 'dosage' => '1000mg', 'form' => 'Effervescent tablets', 'price_min' => 220, 'price_max' => 280],
    ];

    public function handle()
    {
        $this->info('Starting realistic inventory seeding...');

        $pharmacies = Pharmacy::all();
        if ($pharmacies->isEmpty()) {
            $this->error('No pharmacies found in the database. Please register a pharmacy first.');
            return;
        }

        $this->info("Found {$pharmacies->count()} pharmacies. Clearing existing products...");
        Product::truncate();

        $totalInserted = 0;

        foreach ($pharmacies as $pharmacy) {
            $this->info("Populating inventory for pharmacy: {$pharmacy->nom_pharmacie}");

            $numProducts = rand(10, 20);
            $shuffledMeds = $this->medications;
            shuffle($shuffledMeds);

            $selectedMeds = array_slice($shuffledMeds, 0, $numProducts);

            foreach ($selectedMeds as $med) {
                $price = rand($med['price_min'], $med['price_max']);
                $stock = rand(0, 10) === 0 ? 0 : rand(5, 100); 
                $isPublic = rand(1, 10) <= 9; 
                
                $monthsToAdd = rand(-1, 36);
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

                $totalInserted++;
            }
        }

        $this->info("✅ Successfully populated {$pharmacies->count()} pharmacies with a total of {$totalInserted} products.");
    }
}
