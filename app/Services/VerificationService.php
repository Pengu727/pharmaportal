<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VerificationService
{
    /**
     * Generate a 6-digit PIN, cache it in MongoDB, and log it.
     */
    public function sendEmailOtp(User $user): void
    {
        // 1. Generate a secure 6-digit code
        $otpCode = random_int(100000, 999999);

        // 2. Insert into an ephemeral 'verification_otps' collection
        DB::connection('mongodb')->table('verification_otps')->insert([
            'user_id'    => $user->_id,
            'email'      => $user->email,
            'otp_code'   => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(15)->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString()
        ]);

        // 3. Log it locally for testing (instead of configuring a real mail server right now)
        logger("==================================================");
        logger("VERIFICATION PIN FOR {$user->email} IS: {$otpCode}");
        logger("==================================================");
    }
}
