<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
</head>
<body style="margin:0;padding:0;background-color:#f0fdf4;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f0fdf4;padding:40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellspacing="0" cellpadding="0" border="0" style="background-color:#ffffff;border-radius:16px;box-shadow:0 4px 24px rgba(0,0,0,0.06);overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color:#059669;padding:28px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:bold;letter-spacing:-0.5px;"><img src="{{ asset('images/pharmaportal.png') }}" alt="PharmaPortal Logo" style="height: 24px; width: 24px; vertical-align: middle; margin-right: 8px;"> PharmaPortal</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:32px 32px 24px;">
                            <h2 style="margin:0 0 8px;color:#1e293b;font-size:20px;font-weight:bold;">Welcome, {{ $userName }}!</h2>
                            <p style="margin:0 0 24px;color:#64748b;font-size:14px;line-height:1.6;">
                                Thank you for registering on PharmaPortal. To complete your account setup, please enter the verification code below on the verification page.
                            </p>

                            <!-- OTP Code Box -->
                            <div style="background-color:#f8fafc;border:2px solid #e2e8f0;border-radius:12px;padding:24px;text-align:center;margin:0 0 24px;">
                                <p style="margin:0 0 8px;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:1px;font-weight:bold;">Your Verification Code</p>
                                <p style="margin:0;color:#059669;font-size:36px;font-weight:bold;letter-spacing:8px;font-family:monospace;">{{ $otpCode }}</p>
                            </div>

                            <p style="margin:0 0 8px;color:#64748b;font-size:13px;line-height:1.5;">
                                <strong>⏱ This code expires in 15 minutes.</strong>
                            </p>
                            <p style="margin:0;color:#94a3b8;font-size:12px;line-height:1.5;">
                                If you did not create an account on PharmaPortal, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding:16px 32px 24px;border-top:1px solid #f1f5f9;">
                            <p style="margin:0;color:#94a3b8;font-size:11px;text-align:center;">
                                © {{ date('Y') }} PharmaPortal — Algiers, Algeria
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
