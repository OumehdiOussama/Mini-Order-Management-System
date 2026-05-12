@extends('layouts.email')

@section('subject', 'Verify your account')

@section('content')
    <h1 class="title">Welcome to the OMS family!</h1>
    <p class="text">
        We're thrilled to have you here. <strong>OMS</strong> is built to help you streamline your order management and scale your operations with ease.
    </p>
    
    <p class="text">
        To ensure the security of your account and unlock full access to our dashboard, please verify your email address using the secure code below:
    </p>
    
    <div style="background-color: #f6f9fc; border: 1px dashed #e6ebf1; border-radius: 8px; padding: 32px; text-align: center; margin: 32px 0;">
        <span style="font-size: 36px; font-weight: 800; letter-spacing: 0.2em; color: #6366f1;">{{ $otp }}</span>
        <p style="margin: 16px 0 0; font-size: 13px; color: #8898aa; font-weight: 500;">(Verification code expires in 20 minutes)</p>
    </div>

    <p class="text">
        Alternatively, you can click the button below to verify your account directly:
    </p>

    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ $verifyUrl }}" class="btn">Verify Account</a>
    </div>

    <p class="text" style="font-size: 14px; color: #8898aa;">
        If you didn't create an account with OMS, please ignore this email.
    </p>

    <div class="divider"></div>

    <h2 style="font-size: 16px; font-weight: 700; color: #32325d; margin-bottom: 12px;">What can you do with OMS?</h2>
    <ul style="padding-left: 20px; margin: 0; color: #525f7f; font-size: 14px; line-height: 1.8;">
        <li>🚀 <strong>Automate</strong> your order processing workflows</li>
        <li>📦 <strong>Track</strong> shipments in real-time with automatic updates</li>
        <li>📊 <strong>Analyze</strong> your sales performance with detailed insights</li>
        <li>👥 <strong>Manage</strong> customer relationships effortlessly</li>
    </ul>
@endsection

@section('accent')
    <div style="text-align: center;">
        <p style="margin: 0; font-size: 13px; color: #525f7f; font-weight: 500;">
            Next step: Once verified, you can set up your first product catalog and start managing orders.
        </p>
    </div>
@endsection