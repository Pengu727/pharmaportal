<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Services\VerificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Handle and display the authenticated client dashboard with search.
     */
    public function dashboard(Request $request)
    {
        $search = $request->query('search');
        $products = \App\Services\ProductSearchService::search($search, 9);

        return view('client.client_dashboard', [
            'products' => $products,
        ]);
    }

    /**
     * Handle user account registration.
     */
    public function register(Request $request)
    {
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

        app(VerificationService::class)->sendEmailOtp($user);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful. Verification email OTP sent.',
            ], 201);
        }

        // Use consistent session key 'verification_email'
        return redirect()->route('client.verify')->with('verification_email', $user->email);
    }

    /**
     * Validate the 6-digit verification PIN.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $verification = DB::connection('mongodb')
            ->table('verification_otps')
            ->where('email', $request->email)
            ->where('otp_code', (int) $request->otp)
            ->first();

        if (!$verification) {
            return redirect()->back()
                ->withErrors(['otp' => 'The verification code is incorrect.'])
                ->with('verification_email', $request->email)
                ->withInput();
        }

        // Check OTP expiration
        if (isset($verification->expires_at)) {
            $expiresAt = Carbon::parse($verification->expires_at);
            if ($expiresAt->isPast()) {
                // Delete the expired OTP
                DB::connection('mongodb')
                    ->table('verification_otps')
                    ->where('email', $request->email)
                    ->delete();

                return redirect()->back()
                    ->withErrors(['otp' => 'This verification code has expired. Please request a new one.'])
                    ->with('verification_email', $request->email)
                    ->withInput();
            }
        }

        // Mark user as verified
        User::where('email', $request->email)->update(['is_verified' => true]);

        // Delete all OTPs for this email (one-time use)
        DB::connection('mongodb')
            ->table('verification_otps')
            ->where('email', $request->email)
            ->delete();

        $user = User::where('email', $request->email)->first();
        Auth::login($user);

        return redirect()->route('client.dashboard');
    }

    /**
     * Resend verification OTP to the user's email.
     */
    public function resendOtp(Request $request)
    {
        $email = $request->input('email') ?? session('verification_email');

        if (!$email) {
            return redirect()->route('client.verify')
                ->withErrors(['email' => 'No email address provided for resend.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('client.verify')
                ->withErrors(['email' => 'No account found with this email.'])
                ->with('verification_email', $email);
        }

        if ($user->is_verified) {
            return redirect()->route('client.login')
                ->with('status', 'Your account is already verified. Please log in.');
        }

        app(VerificationService::class)->sendEmailOtp($user);

        return redirect()->route('client.verify')
            ->with('verification_email', $email)
            ->with('status', 'A new verification code has been sent to your email.');
    }

    /**
     * Authenticate and redirect based on user role.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid email or password.',
                ], 401);
            }
            return redirect()->back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

        // Block unverified clients
        if ($user->role === 'client' && !$user->is_verified) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account is not verified yet.',
                ], 403);
            }
            return redirect()->route('client.verify')
                ->with('verification_email', $user->email)
                ->withErrors(['otp' => 'Please verify your email address to log in.']);
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
                ],
            ], 200);
        }

        // Web session authentication
        Auth::login($user);

        // Role-based redirect
        return match ($user->role) {
            'owner' => redirect()->route('owner.dashboard'),
            'seller' => redirect()->route('seller.dashboard'),
            default => redirect()->route('client.dashboard'),
        };
    }
}