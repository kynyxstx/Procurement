<x-guest-layout>

    <div class="w-full sm:max-w-md p-6 bg-white rounded-lg shadow-lg text-center">

        <div
            class="w-28 h-28 bg-white border-4 border-indigo-600 rounded-full flex items-center justify-center mx-auto -mt-20 mb-8 shadow-lg">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-20 w-20 object-contain rounded-full" />
            </a>
        </div>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="sr-only">{{ __('Email') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input id="email"
                        class="block w-full pl-10 p-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="Email" />
                </div>
            </div>

            {{-- Password Input Field --}}
            <div class="mb-6">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-5 w-5 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <input id="password"
                        class="block w-full pl-10 p-2.5 text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="Password" />
                </div>
            </div>

            {{-- Remember Me & Forgot Password Links --}}
            <div class="flex items-center justify-between mb-6 text-sm">
                {{--<label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-gray-600">{{ __('Remember me') }}</span>
                </label>--}}
                <span class="ml-auto flex items-center"></span>
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-900 ml-1">
                    {{ __('Register') }}
                </a>
                </span>
                {{--@if (Route::has('password.request'))
                <a class="text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
                @endif--}}
            </div>

            {{-- Login Button --}}
            <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                {{ __('LOGIN') }}
            </button>
        </form>
    </div>

</x-guest-layout>