<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-900">
        <div class="bg-gray-800 p-8 rounded-lg shadow-md w-full sm:max-w-md">
            <h2 class="text-center text-white text-3xl font-bold">BLEJA</h2>
            <p class="text-center text-gray-400 mb-6">Login to your account</p>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div>
                    <x-input-label for="username" :value="__('Username')" class="text-gray-300" />
                    <x-text-input id="username" class="block mt-1 w-full bg-gray-700 text-white border-gray-600" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-400" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-300" />
                    
                    <div class="relative">
                        <x-text-input id="password" class="block w-full bg-gray-700 text-white border-gray-600 pr-10" type="password" name="password" required autocomplete="current-password" />
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <span id="eyeIcon">👁️</span>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded bg-gray-700 border-gray-600 text-indigo-500 focus:ring-indigo-400" name="remember">
                        <span class="ms-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-400 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                        {{ __('Log In') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.getElementById("eyeIcon");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.textContent = "🙈";
    } else {
        passwordField.type = "password";
        eyeIcon.textContent = "👁️";
    }
}
</script>