@section('title', 'Masuk')

<x-guest-layout>
    <div class="py-12">
        <div class="w-2/3 mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <x-alert.error :errors="$errors->all()" />
            @endif

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <x-card.card-default class="static">
                <x-form action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input.input-label for="email" :value="__('Email')" />
                        <x-input.text-input id="email" class="mt-1 w-full" type="text" name="email"
                            :value="old('email')" required autofocus autocomplete="email" />
                        <x-input.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input.input-label for="password" :value="__('Password')" />

                        <x-input.text-input id="password" class="mt-1 w-full" type="password" name="password" required
                            autocomplete="current-password" />

                        <x-input.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="mt-4">
                        <x-input.input-label for="remember" class="label cursor-pointer mr-3">
                            <x-input.checkbox name="remember" id="remember" :title="__('Remember Me')" />
                        </x-input.input-label>
                    </div>

                    <div class="flex items-center justify-end mt-4">


                        <x-button.primary-button class="ms-3" type="submit">
                            {{ __('Log in') }}
                        </x-button.primary-button>
                    </div>
                </x-form>
            </x-card.card-default>
        </div>
    </div>
</x-guest-layout>
