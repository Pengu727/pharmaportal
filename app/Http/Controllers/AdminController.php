<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            session(['admin_name' => $admin->prenom ?? 'Admin']);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'Identifiants administratifs invalides.']);
    }

    public function dashboard()
    {
        if (Admin::count() === 0) {
            $this->seedDatabaseContext();
        }

        $pharmacies = Pharmacy::all();

        // Eager-load owner for each pharmacy for the dashboard table
        $pharmacyData = $pharmacies->map(function ($pharmacy) {
            $pharmacy->ownerUser = User::where('pharmacy_id', (string) $pharmacy->_id)
                ->where('role', 'owner')
                ->first();
            return $pharmacy;
        });

        return view('admin.dashboard', ['pharmacies' => $pharmacyData]);
    }

    public function show($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $owner = User::where('pharmacy_id', (string) $pharmacy->_id)
            ->where('role', 'owner')
            ->first();

        return view('admin.show', compact('pharmacy', 'owner'));
    }

    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a new pharmacy AND create the owner User account simultaneously.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_pharmacie' => 'required|string|max:255',
            'registre_commerce' => 'required|string|max:100',
            'heure_ouverture' => 'required|string',
            'heure_fermeture' => 'required|string',
            'adresse_complete' => 'required|string',
            'num_tel' => 'required|string',
            'wilaya' => 'required|string',
            'commune' => 'required|string',
            // Owner fields
            'owner_nom' => 'required|string|max:100',
            'owner_prenom' => 'required|string|max:100',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|string|min:6',
        ]);

        $lat = (float) $request->input('latitude', 0);
        $lng = (float) $request->input('longitude', 0);
        $url = $request->input('google_maps_link', '');

        // Server-side parsing fallback
        if (($lat === 0.0 || $lng === 0.0) && !empty($url)) {
            if (preg_match('/([0-9.-]+),([0-9.-]+)/', $url, $matches)) {
                $lat = (float) $matches[1];
                $lng = (float) $matches[2];
            }
        }

        // 1. Create Pharmacy with ONLY pharmacy data
        $pharmacy = Pharmacy::create([
            'nom_pharmacie' => $request->nom_pharmacie,
            'registre_commerce' => $request->registre_commerce,
            'heure_ouverture' => $request->heure_ouverture,
            'heure_fermeture' => $request->heure_fermeture,
            'adresse_complete' => $request->adresse_complete,
            'num_tel' => $request->num_tel,
            'wilaya' => $request->wilaya,
            'commune' => $request->commune,
            'google_maps_link' => $url,
            'location' => [
                'type' => 'Point',
                'coordinates' => [
                    $lng !== 0.0 ? $lng : 3.05125,
                    $lat !== 0.0 ? $lat : 36.76451,
                ],
            ],
        ]);

        // 2. Create the Owner as a User linked to the pharmacy
        User::create([
            'nom' => $request->owner_nom,
            'prenom' => $request->owner_prenom,
            'email' => $request->owner_email,
            'password' => Hash::make($request->owner_password),
            'num_tel' => $request->num_tel,
            'role' => 'owner',
            'pharmacy_id' => (string) $pharmacy->_id,
            'is_verified' => true,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Pharmacy and owner account created successfully.');
    }

    public function edit($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $owner = User::where('pharmacy_id', (string) $pharmacy->_id)
            ->where('role', 'owner')
            ->first();

        return view('admin.edit', compact('pharmacy', 'owner'));
    }

    public function update(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);

        $lat = (float) $request->input('latitude', 0);
        $lng = (float) $request->input('longitude', 0);
        $url = $request->input('google_maps_link', '');

        if (($lat === 0.0 || $lng === 0.0) && !empty($url)) {
            if (preg_match('/([0-9.-]+),([0-9.-]+)/', $url, $matches)) {
                $lat = (float) $matches[1];
                $lng = (float) $matches[2];
            }
        }

        $updateData = [
            'nom_pharmacie' => $request->nom_pharmacie,
            'registre_commerce' => $request->registre_commerce,
            'heure_ouverture' => $request->heure_ouverture,
            'heure_fermeture' => $request->heure_fermeture,
            'adresse_complete' => $request->adresse_complete,
            'num_tel' => $request->num_tel,
            'wilaya' => $request->wilaya ?? $pharmacy->wilaya,
            'commune' => $request->commune ?? $pharmacy->commune,
            'google_maps_link' => $url,
        ];

        if ($lat !== 0.0 && $lng !== 0.0) {
            $updateData['location'] = [
                'type' => 'Point',
                'coordinates' => [$lng, $lat],
            ];
        }

        $pharmacy->update($updateData);

        // Update owner info if provided
        $owner = User::where('pharmacy_id', (string) $pharmacy->_id)
            ->where('role', 'owner')
            ->first();

        if ($owner && $request->filled('owner_nom')) {
            $ownerUpdate = [
                'nom' => $request->owner_nom,
                'prenom' => $request->owner_prenom,
            ];
            if ($request->filled('owner_email') && $request->owner_email !== $owner->email) {
                $ownerUpdate['email'] = $request->owner_email;
            }
            $owner->update($ownerUpdate);
        }

        return redirect()->route('admin.dashboard')->with('status', 'Pharmacy updated successfully.');
    }

    /**
     * Delete a pharmacy and its associated owner account.
     */
    public function destroy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);

        // Delete associated owner and seller accounts
        User::where('pharmacy_id', (string) $pharmacy->_id)->delete();

        // Delete the pharmacy
        $pharmacy->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Pharmacy deleted successfully.');
    }

    /**
     * Seed initial admin and sample pharmacies for development.
     */
    private function seedDatabaseContext()
    {
        if (Admin::count() === 0) {
            Admin::create([
                'nom' => 'System',
                'prenom' => 'Administrator',
                'email' => 'admin@pharmaportal.dz',
                'password' => Hash::make('admin123'),
                'num_tel' => '0550000000',
            ]);
        }

        if (Pharmacy::count() === 0) {
            $names = ['Audin', 'El Biar', 'Hydra', 'Kouba', 'Chéraga'];
            $longs = [3.051250, 3.028910, 3.023412, 3.085521, 2.961230];
            $lats = [36.764515, 36.769820, 36.741250, 36.729910, 36.751120];

            for ($i = 0; $i < 5; $i++) {
                $pharmacy = Pharmacy::create([
                    'nom_pharmacie' => 'Pharmacie ' . $names[$i],
                    'registre_commerce' => '16/00-0987654B' . $i,
                    'heure_ouverture' => '07:30',
                    'heure_fermeture' => '21:00',
                    'adresse_complete' => '33, Rue Centrale Secteur ' . $names[$i] . ', ' . $names[$i] . ', Alger',
                    'num_tel' => '077014001' . $i,
                    'wilaya' => 'Alger',
                    'commune' => $names[$i] . ' Centre',
                    'google_maps_link' => '',
                    'location' => [
                        'type' => 'Point',
                        'coordinates' => [$longs[$i], $lats[$i]],
                    ],
                ]);

                // Create a proper owner User for each pharmacy
                User::create([
                    'nom' => 'Rebai',
                    'prenom' => 'Sidi ' . ($i + 1),
                    'email' => 'owner' . ($i + 1) . '@pharmacie-' . strtolower($names[$i]) . '.dz',
                    'password' => Hash::make('owner123'),
                    'num_tel' => '077014001' . $i,
                    'role' => 'owner',
                    'pharmacy_id' => (string) $pharmacy->_id,
                    'is_verified' => true,
                ]);
            }
        }
    }
}