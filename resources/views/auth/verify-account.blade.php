@extends("layouts.auth")

@section("title", "Verify Email")

@section("content")

<div class="w-full max-w-sm p-6 bg-gray-800 border border-gray-700 shadow-xl rounded-xl">

    <h2 class="mt-2 mb-3 text-2xl font-semibold text-center text-gray-100">
        Verify Account
    </h2>

    <p class="mb-4 text-sm text-center text-gray-400">
        Enter or paste the 6-digits OTP sent to your email or phone
    </p>

    @session('error')
        <p class="mb-4 text-xs text-center text-red-400">{{ session("error") }}</p>
    @endsession

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="mb-4 text-xs text-center text-red-400">{{ $error }}</p>
        @endforeach
    @endif

    <form id="otpForm" action="{{ url('/verify-account') }}" method="POST">
        @csrf
        <input type="hidden" name="identifier" value="{{ $identifier }}">

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

        <div class="flex justify-center">
            <button type="submit"
                id="verifyBtn"
                disabled
                class="w-full max-w-[220px] py-2 text-sm font-semibold text-white bg-blue-600 rounded-md opacity-50 cursor-not-allowed transition hover:bg-blue-700">
                Verify
            </button>
        </div>
    </form>

    <!-- open modal -->
    <p id="openModal"
       class="text-center text-gray-400 hover:text-gray-200 hover:underline cursor-pointer mt-6 text-sm">
        Or verify with another method?
    </p>

</div>

<!-- MODAL -->
<div id="modal"
     class="fixed inset-0 bg-black/60 flex items-center justify-center hidden">

    <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl shadow-xl w-full max-w-sm">

        <h3 class="text-lg font-semibold text-center text-gray-100 mb-5">
            Choose Verification Method
        </h3>

        <div class="space-y-3">

            <!-- EMAIL -->
            <form action="{{ url('send-verification-otp') }}" method="POST">
                @csrf
                <input type="hidden" name="identifier" value="{{ $identifier }}">
                <input type="hidden" name="method" value="email">

                <button class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md">
                    <i class="fa-solid fa-envelope"></i>
                    Verify with Email
                </button>
            </form>

            <!-- PHONE -->
            <form action="{{ url('send-verification-otp') }}" method="POST">
                @csrf
                <input type="hidden" name="identifier" value="{{ $identifier }}">
                <input type="hidden" name="method" value="phone">

                <button class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 rounded-md">
                    <i class="fa-solid fa-phone"></i>
                    Verify with Phone
                </button>
            </form>

        </div>

        <button id="modalClose"
                class="mt-5 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-md">
            Close
        </button>

    </div>
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

    // modal logic
    const openModal = document.getElementById('openModal');
    const modal = document.getElementById('modal');
    const closeModal = document.getElementById('modalClose');

    openModal.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
</script>

@endsection