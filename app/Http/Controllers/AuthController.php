<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product; // Imported for pulling MongoDB catalog stocks dynamically
use App\Services\VerificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Handle and display the authenticated client dashboard with guest page search features.
     */
    public function dashboard(Request $request)
    {
        // Grab the query search string term 
        $search = $request->query('search');

        // Dynamically query your MongoDB collection matching your guest logic
        $query = Product::query();

        if (!empty($search)) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        }

        // Paginate exactly like your guest products view
        $products = $query->paginate(9);

        return view('client.client_dashboard', [
            'products' => $products
        ]);
    }

    /**
     * Handle user account registration.
     */
    public function register(Request $request)
    {
        // 1. Run Validation inline
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', 
            'num_tel' => 'required|string',
            'wilaya' => 'required|string',
            'commune' => 'required|string',
            'date_naissance' => 'required|date',
        ]);

        // 2. Persist user to MongoDB
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'num_tel' => $request->num_tel,
            'wilaya' => $request->wilaya,
            'commune' => $request->commune,
            'date_naissance' => $request->date_naissance,
            'role' => 'client',
            'is_verified' => false,
        ]);

        // 3. Fire SMTP / Email service dispatch handler logic
        app(VerificationService::class)->sendEmailOtp($user);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful. Verification email OTP sent.'
            ], 201);
        }

        // Save session identification state indicator parameters
        return redirect()->route('client.verify')->with('verification_email', $user->email);
    }

    /**
     * Validate the 6-digit verification PIN matching active record arrays.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|numeric'
        ]);

        $verification = DB::connection('mongodb')
            ->table('verification_otps')
            ->where('email', $request->email)
            ->where('otp_code', (int)$request->otp)
            ->first();

        if (!$verification) {
            return redirect()->back()->withErrors(['otp' => 'The verification code is incorrect or has expired.'])->withInput();
        }

        // Update target validation flags
        User::where('email', $request->email)->update(['is_verified' => true]);

        // Evict code entry keys
        DB::connection('mongodb')
            ->table('verification_otps')
            ->where('email', $request->email)
            ->delete();

        $user = User::where('email', $request->email)->first();
        Auth::login($user);

        return redirect()->route('client.dashboard');
    }

    /**
     * Authenticate data credentials and establish state session bindings.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password.'
                ], 401);
            }
            return redirect()->back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        // Block access if the user hasn't verified their OTP yet
        if (!$user->is_verified) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account is not verified yet. Please check your email for the OTP code.'
                ], 403);
            }
            return redirect()->route('client.verify')->with('email', $user->email)->withErrors(['otp' => 'Please verify your email address to log in.']);
        }

        if ($request->wantsJson()) {
            $fakeToken = base64_encode(random_bytes(40)); 
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'access_token' => $fakeToken,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->_id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 200);
        }

        // Web session authentication
        Auth::login($user);
        
        // Redirect right to client dashboard
        return redirect()->route('client.dashboard');
    }
}