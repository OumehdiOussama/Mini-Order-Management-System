<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification OTP</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
</head>

<body style="background:#1f2937; color:#e5e7eb; font-family:Arial,sans-serif; padding:20px;">

<div style="max-width:600px; margin:0 auto; background:#111827; padding:20px; border-radius:8px;">

    <h2 style="color:#10b981; text-align:center;">Account Verification OTP</h2>

    <p>Hello,</p>

    <p>Thank you for registering! Your OTP for email verification is:</p>

    <h3 style="text-align:center; font-size:36px; color:#ffffff;">
        {{ $otp }}
    </h3>

    <p style="text-align:center;">Use this OTP to verify your account.</p>

    <p>If you did not create an account, please ignore this email.</p>

    <div style="text-align:center; margin-top:20px;">
        <a href="{{ $verifyUrl }}"
           style="background:#3b82f6; color:#ffffff; padding:10px 20px; text-decoration:none; border-radius:4px; font-weight:bold; display:inline-block;">
            Verify Your Account
        </a>
    </div>

    <div style="font-size:12px; color:#9ca3af; text-align:center; margin-top:20px;">
        <p>If the button does not work, copy this link:</p>
        <p>{{ $verifyUrl }}</p>
    </div>

</div>

</body>
</html>