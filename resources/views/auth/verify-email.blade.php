@extends("layouts.auth")

@section("title", "Verify Email")

@section("content")

<div class="w-full max-w-sm p-6 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">

    <h2 class="mt-2 mb-3 text-2xl font-semibold text-center text-gray-100">
        Verify Email
    </h2>

    <p class="mb-4 text-sm text-center text-gray-400">
        Enter or paste the 6-digit code
    </p>
    
    @session('error')
          <p class="mb-4 text-xs text-center text-red-400">{{session("error")}}</p>
    @endsession
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="mb-4 text-xs text-center text-red-400">{{ $error }}</p>
        @endforeach
    @endif

    <form id="otpForm" action="{{ url('/verify-email') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- OTP INPUTS -->
        <div class="flex justify-center mb-5 space-x-2">
            @for ($i = 1; $i <= 6; $i++)
                <input
                    type="text"
                    maxlength="1"
                    name="otp[]"
                    class="text-base font-semibold text-center text-gray-100 bg-gray-700 border border-gray-600 rounded-md otp-input w-9 h-9 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    pattern="[0-9]"
                    {{ $i === 1 ? 'autofocus' : '' }}
                >
            @endfor
        </div>

        <!-- BUTTON -->
        <div class="flex justify-center">
            <button type="submit"
                id="verifyBtn"
                disabled
                class="w-full max-w-[220px] py-2 text-sm font-semibold text-white bg-blue-600 rounded-md opacity-50 cursor-not-allowed transition hover:bg-blue-700">
                Verify
            </button>
        </div>

    </form>

</div>

<script>
const inputs = document.querySelectorAll('.otp-input');
const btn = document.getElementById('verifyBtn');

function check() {
    const filled = [...inputs].every(i => i.value.length === 1);

    btn.disabled = !filled;
    btn.classList.toggle('opacity-50', !filled);
    btn.classList.toggle('cursor-not-allowed', !filled);
}

// 🔥 AUTO MOVE + TYPE
inputs.forEach((input, i) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/[^0-9]/g, '');

        if (input.value && i < inputs.length - 1) {
            inputs[i + 1].focus();
        }

        check();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && i > 0) {
            inputs[i - 1].focus();
        }
    });
});

// 🔥 PASTE FULL OTP FEATURE (IMPORTANT)
inputs[0].addEventListener('paste', (e) => {
    e.preventDefault();

    let paste = (e.clipboardData || window.clipboardData).getData('text');
    paste = paste.replace(/\D/g, '').slice(0, 6);

    inputs.forEach((input, i) => {
        input.value = paste[i] || '';
    });

    inputs[paste.length - 1]?.focus();
    check();
});
</script>

@endsection