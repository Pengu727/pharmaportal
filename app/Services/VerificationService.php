<?php
namespace App\Services;

use App\Models\User;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class VerificationService
{
    /**
     * Generate a 6-digit PIN, store in MongoDB, and send via email.
     */
    public function sendEmailOtp(User $user): void
    {
        // 1. Delete any existing OTPs for this user (prevent duplicates)
        DB::connection('mongodb')->table('verification_otps')
            ->where('email', $user->email)
            ->delete();

        // 2. Generate a secure 6-digit code
        $otpCode = random_int(100000, 999999);

        // 3. Insert into ephemeral 'verification_otps' collection
        DB::connection('mongodb')->table('verification_otps')->insert([
            'user_id'    => $user->_id,
            'email'      => $user->email,
            'otp_code'   => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(15)->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString()
        ]);

        // 4. Always log for dev convenience
        logger("==================================================");
        logger("VERIFICATION PIN FOR {$user->email} IS: {$otpCode}");
        logger("==================================================");

        // 5. Send real email
        try {
            Mail::to($user->email)->send(
                new VerificationMail($otpCode, $user->prenom ?? 'User')
            );
            logger("Verification email sent successfully to {$user->email}");
        } catch (\Exception $e) {
            logger("Failed to send verification email to {$user->email}: " . $e->getMessage());
            // Don't throw — the OTP is logged and stored, user can still verify manually
        }
    }
}
