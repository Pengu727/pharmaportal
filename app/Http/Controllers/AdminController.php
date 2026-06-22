<?php

namespace App\Http\Controllers;

use App\Models\Admin; 
use App\Models\Pharmacy;
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
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['error' => 'Identifiants administratifs invalides.']);
    }

    public function dashboard()
    {
        if (Admin::count() === 0 || Pharmacy::count() === 0) {
            $this->seedDatabaseContext();
        }

        $pharmacies = Pharmacy::all();
        return view('admin.dashboard', compact('pharmacies'));
    }

    public function show($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        return view('admin.show', compact('pharmacy'));
    }

    public function create()
    {
        return view('admin.create');
    }

    // FLAT STORAGE: Saved directly to root document fields
    public function store(Request $request)
    {
        Pharmacy::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'num_tel' => $request->num_tel,
            'wilaya' => $request->wilaya,
            'commune' => $request->commune,
            'date_naissance' => $request->date_naissance,
            'role' => 'owner',
            'is_verified' => true,
            'nom_pharmacie' => $request->nom_pharmacie,
            'registre_commerce' => $request->registre_commerce,
            'heure_ouverture' => $request->heure_ouverture,
            'heure_fermeture' => $request->heure_fermeture,
            'adresse_complete' => $request->adresse_complete,
            'google_maps_link' => $request->google_maps_link,
            'location' => [
                'type' => 'Point',
                'coordinates' => [
                    (float)$request->input('longitude', 3.05125),
                    (float)$request->input('latitude', 36.76451)
                ]
            ]
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function edit($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        return view('admin.edit', compact('pharmacy'));
    }

    // FLAT UPDATE: Extracted from nesting
    public function update(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        
        $pharmacy->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'num_tel' => $request->num_tel,
            'wilaya' => $request->wilaya,
            'commune' => $request->commune,
            'nom_pharmacie' => $request->nom_pharmacie,
            'registre_commerce' => $request->registre_commerce,
            'heure_ouverture' => $request->heure_ouverture,
            'heure_fermeture' => $request->heure_fermeture,
            'adresse_complete' => $request->adresse_complete,
            'google_maps_link' => $request->google_maps_link,
        ]);

        return redirect()->route('admin.dashboard');
    }

    private function seedDatabaseContext()
    {
        if (Admin::count() === 0) {
            Admin::create([
                'nom' => 'System',
                'prenom' => 'Administrator',
                'email' => 'admin@pharmaportal.dz',
                'password' => Hash::make('admin123'), 
                'num_tel' => '0550000000'
            ]);
        }

        if (Pharmacy::count() === 0) {
            $names = ['Audin', 'El Biar', 'Hydra', 'Kouba', 'Chéraga'];
            $longs = [3.051250, 3.028910, 3.023412, 3.085521, 2.961230];
            $lats = [36.764515, 36.769820, 36.741250, 36.729910, 36.751120];

            for ($i = 0; $i < 5; $i++) {
                Pharmacy::create([
                    'nom' => 'Rebai',
                    'prenom' => 'Sidi ' . ($i + 1),
                    'email' => 'contact@pharmacie-' . strtolower($names[$i]) . '.dz',
                    'num_tel' => '077014001' . $i,
                    'wilaya' => 'Alger',
                    'commune' => $names[$i] . ' Centre',
                    'date_naissance' => '1972-11-05',
                    'role' => 'owner',
                    'is_verified' => true,
                    'nom_pharmacie' => 'Pharmacie ' . $names[$i],
                    'registre_commerce' => '16/00-0987654B' . $i,
                    'heure_ouverture' => '07:30',
                    'heure_fermeture' => '21:00',
                    'adresse_complete' => '33, Rue Centrale Secteur ' . $names[$i] . ', ' . $names[$i] . ', Alger',
                    'location' => [
                        'type' => 'Point',
                        'coordinates' => [$longs[$i], $lats[$i]]
                    ]
                ]);
            }
        }
    }
}