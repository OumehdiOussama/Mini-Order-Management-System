@extends('layouts.email')

@section('subject', 'Reset your password')

@section('content')
    <h1 class="title">Reset your password</h1>
    <p class="text">
        Hello, <br><br>
        We received a request to reset the password for your <strong>OMS</strong> account. Click the button below to choose a new password.
    </p>

    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ $resetUrl }}" class="btn">Set New Password</a>
    </div>

    <p class="text" style="font-size: 14px; color: #8898aa;">
        For your security, this link will expire in 1 hour. If you didn't request a password reset, you can safely ignore this email.
    </p>

    <div class="divider"></div>

    <p class="text" style="font-size: 12px; color: #8898aa; margin-bottom: 0;">
        If you're having trouble clicking the button, copy and paste this URL into your browser: <br>
        <span style="word-break: break-all; color: #6366f1;">{{ $resetUrl }}</span>
    </p>
@endsection

@section('accent')
    <div style="text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #525f7f; font-weight: 500;">
            Secure Account Tip: Use a unique password with at least 10 characters and special symbols.
        </p>
    </div>
@endsection