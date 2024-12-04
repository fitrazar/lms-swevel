@section('title', 'Profile')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="h-full py-12 p-8">
        <x-card.card-default class="static glass pb-8">
            <div class="flex flex-col items-center -mt-20">
                @if (Auth::user()->participant)
                    @if (Auth::user()->participant->photo)
                        <img src="{{ asset('storage/participant/photo/' . Auth::user()->participant->photo) }}"
                            id="imgReal" class="w-40 border-4 border-white rounded-full">
                    @else
                        @if (Auth::user()->participant->gender == 'Laki - Laki')
                            <img src="{{ asset('assets/images/male.png') }}" id="imgReal"
                                class="w-40 border-4 bg-base-100 border-base-100 rounded-full">
                        @else
                            <img src="{{ asset('assets/images/female.png') }}" id="imgReal"
                                class="w-40 border-4 bg-base-100 border-base-100 rounded-full">
                        @endif
                    @endif
                    <img class="imgPreview w-40 border-4 bg-base-100 border-base-100 rounded-full hidden"
                        alt="image">
                    <div class="flex items-center space-x-2 mt-2">
                        <p class="md:text-2xl text-xl text-center">{{ Auth::user()->participant->name }}</p>
                    </div>
                    <p>{{ Auth::user()->participant->class }}</p>
                    <p class="text-sm">{{ Auth::user()->participant->bio ?? '-' }}</p>
                @else
                    @if (Auth::user()->instructor->photo)
                        <img src="{{ asset('storage/instructor/photo/' . Auth::user()->instructor->photo) }}"
                            id="imgReal" class="w-40 border-4 border-white rounded-full">
                    @else
                        @if (Auth::user()->instructor->gender == 'Laki - Laki')
                            <img src="{{ asset('assets/images/male.png') }}" id="imgReal"
                                class="w-40 border-4 bg-base-100 border-base-100 rounded-full">
                        @else
                            <img src="{{ asset('assets/images/female.png') }}" id="imgReal"
                                class="w-40 border-4 bg-base-100 border-base-100 rounded-full">
                        @endif
                    @endif
                    <img class="imgPreview w-40 border-4 bg-base-100 border-base-100 rounded-full hidden"
                        alt="image">
                    <div class="flex items-center space-x-2 mt-2">
                        <p class="md:text-2xl text-xl text-center">{{ Auth::user()->instructor->name }}</p>
                    </div>
                    <p class="text-sm">{{ Auth::user()->instructor->bio ?? '-' }}</p>
                @endif

            </div>
        </x-card.card-default>

        <div class="my-4 flex flex-col 2xl:flex-row space-y-4 2xl:space-y-0 2xl:space-x-4">
            <div class="w-full flex flex-col 2xl:w-1/3">
                <x-card.card-default class="flex-1 glass static p-8">
                    @if (session()->has('success'))
                        <x-alert.success :message="session('success')" />
                    @endif

                    <h4 class="text-xl font-bold">Personal Info</h4>
                    <x-form action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="oldImage"
                            value="{{ Auth::user()->participant ? Auth::user()->participant->photo : Auth::user()->instructor->photo }}">
                        <div class="mt-4">
                            <x-input.input-label for="photo" :value="__('Foto')" />
                            <x-input.input-file id="photo" class="mt-1 w-full" type="file" name="photo"
                                onchange="previewImage()" :value="old('photo')" />
                            <x-input.input-error :messages="$errors->get('photo')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="phone" :value="__('No Telpon')" />
                            <x-input.text-input id="phone" class="mt-1 w-full" type="number" name="phone"
                                :value="old(
                                    'phone',
                                    Auth::user()->participant
                                        ? Auth::user()->participant->phone ?? ''
                                        : Auth::user()->instructor->phone ?? '',
                                )" autofocus autocomplete="phone" />
                            <x-input.input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="bio" :value="__('Bio')" />
                            <x-input.text-area id="bio" class="mt-1 w-full" type="text" name="bio"
                                :value="old(
                                    'bio',
                                    Auth::user()->participant
                                        ? Auth::user()->participant->bio ?? ''
                                        : Auth::user()->instructor->bio ?? '',
                                )" autofocus autocomplete="bio" />
                            <x-input.input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-button.primary-button type="submit">
                                {{ __('Simpan') }}
                            </x-button.primary-button>
                        </div>
                    </x-form>
                </x-card.card-default>
            </div>
        </div>
    </div>
</x-app-layout>
