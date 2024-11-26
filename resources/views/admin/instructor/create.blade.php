@section('title', 'Tambah Data Mentor')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.admin.instructor.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>

                <x-form action="{{ route('dashboard.admin.instructor.store') }}" class="md:grid md:grid-cols-2 gap-4"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mt-4">
                        <img class="imgPreview h-auto max-w-lg mx-auto hidden" alt="image">
                        <x-input.input-label for="photo" :value="__('Foto')" />
                        <x-input.input-file id="photo" class="mt-1 w-full" type="file" name="photo"
                            :value="old('photo')" required autofocus autocomplete="photo" onchange="previewImage()" />
                        <x-input.input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="email" :value="__('Email')" />
                        <x-input.text-input id="email" class="mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="email" />
                        <x-input.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="name" :value="__('Nama Mentor')" />
                        <x-input.text-input id="name" class="mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="gender" :value="__('Jenis Kelamin')" />
                        <x-input.select-input id="gender" class="mt-1 w-full" type="text" name="gender" required
                            autofocus autocomplete="gender">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki - Laki" {{ old('gender') == 'Laki - Laki' ? ' selected' : ' ' }}>Laki -
                                Laki
                            </option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? ' selected' : ' ' }}>Perempuan
                            </option>
                        </x-input.select-input>
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="phone" :value="__('No Telpon')" />
                        <x-input.text-input id="phone" class="mt-1 w-full" type="number" name="phone"
                            :value="old('phone')" required autofocus autocomplete="phone" />
                        <x-input.input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="password" :value="__('Password')" />
                        <x-input.text-input id="password" class="mt-1 w-full" type="text" name="password"
                            :value="old('password')" required autofocus autocomplete="password" />
                        <x-input.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="col-span-2">
                        <x-button.primary-button type="submit">
                            {{ __('Simpan') }}
                        </x-button.primary-button>
                    </div>

                </x-form>
            </x-card.card-default>
        </div>
    </div>

    <x-slot name="script">
        <script>
            function previewImage() {
                const image = document.querySelector('#photo')
                const imgPreview = document.querySelector('.imgPreview')

                imgPreview.style.display = 'block'

                const oFReader = new FileReader()
                oFReader.readAsDataURL(image.files[0])
                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result
                }
            }
        </script>
    </x-slot>
</x-app-layout>
