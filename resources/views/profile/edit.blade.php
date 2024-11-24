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
                @if (Auth::user()->profile && Auth::user()->profile->photo)
                    <img src="{{ asset('storage/photo/' . Auth::user()->name . '/' . Auth::user()->profile->photo) }}"
                        id="imgReal" class="w-40 border-4 border-white rounded-full">
                @else
                    <img src="{{ asset('assets/images/male.png') }}" id="imgReal"
                        class="w-40 border-4 bg-base-100 border-base-100 rounded-full">
                @endif
                <img class="imgPreview w-40 border-4 bg-base-100 border-base-100 rounded-full hidden" alt="image">
                <div class="flex items-center space-x-2 mt-2">
                    <p class="md:text-2xl text-xl text-center">{{ Auth::user()->name }}</p>
                </div>
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
                        <input type="hidden" name="oldImage" value="{{ Auth::user()->profile?->photo }}">
                        <div class="mt-4">
                            <x-input.input-label for="photo" :value="__('Foto')" />
                            <x-input.input-file id="photo" class="mt-1 w-full" type="file" name="photo"
                                onchange="previewImage()" :value="old('photo')" />
                            <x-input.input-error :messages="$errors->get('photo')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="phone" :value="__('No Telpon')" />
                            <x-input.text-input id="phone" class="mt-1 w-full" type="number" name="phone"
                                :value="old('phone', Auth::user()->profile->phone ?? '')" autofocus autocomplete="phone" />
                            <x-input.input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="birthplace" :value="__('Tempat Lahir')" />
                            <x-input.text-input id="birthplace" class="mt-1 w-full" type="text" name="birthplace"
                                :value="old('birthplace', Auth::user()->profile->birthplace ?? '')" autofocus autocomplete="birthplace" />
                            <x-input.input-error :messages="$errors->get('birthplace')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="birthdate" :value="__('Tanggal Lahir')" />
                            <x-input.text-input id="birthdate" class="mt-1 w-full" type="date" name="birthdate"
                                :value="old('birthdate', Auth::user()->profile->birthdate ?? '')" autofocus autocomplete="birthdate" />
                            <x-input.input-error :messages="$errors->get('birthdate')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="address" :value="__('Alamat')" />
                            <x-input.text-input id="address" class="mt-1 w-full" type="text" name="address"
                                :value="old('address', Auth::user()->profile->address ?? '')" autofocus autocomplete="address" />
                            <x-input.input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input.input-label for="description" :value="__('Deskripsi Diri')" />
                            <x-input.text-area id="description" class="mt-1 w-full" type="text" name="description"
                                :value="old('description', Auth::user()->profile->description ?? '')" autofocus autocomplete="description" />
                            <x-input.input-error :messages="$errors->get('description')" class="mt-2" />
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
    <x-slot name="script">
        <script>
            function previewImage() {
                const image = document.querySelector('#photo')
                const imageReal = document.querySelector('#imgReal')
                const imgPreview = document.querySelector('.imgPreview')

                imageReal.classList.add('hidden');
                imgPreview.classList.remove('hidden');
                imgPreview.classList.add('block');

                const oFReader = new FileReader()
                oFReader.readAsDataURL(image.files[0])
                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result
                }
            }
        </script>
    </x-slot>
</x-app-layout>
