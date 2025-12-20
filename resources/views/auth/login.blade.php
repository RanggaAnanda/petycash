 <x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" name="email" />
            <x-text-input id="email" class="block mt-1 w-full py-2 px-2 text-lg shadow-xl border-2 border-solid " type="test" name="email" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" name="password"/>

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full py-2 px-2 text-lg shadow-xl border-2 border-solid"
                                type="password"
                                name="password"
                                required />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Show Password -->
        <div class="block mt-4">
            <label for="showpassword" class="inline-flex items-center">
                <input id="showpassword" type="checkbox" onclick="togglePassword('password', 'eyeIcon')" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:checked:bg-indigo-600" name="showpassword">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Show Password') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
