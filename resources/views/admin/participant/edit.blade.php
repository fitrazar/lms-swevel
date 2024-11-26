@section('title', 'Edit Data Peserta')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.admin.participant.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>

                <x-form action="{{ route('dashboard.admin.participant.update', $participant->id) }}"
                    class="md:grid md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="oldImage" value="{{ $participant->photo }}">
                    @if ($participant->photo)
                        <div class="avatar">
                            <div class="w-12 rounded-xl">
                                <img src="{{ asset('storage/participant/photo/' . $participant->photo) }}" />
                            </div>
                        </div>
                    @endif
                    <div class="mt-4">
                        <x-input.input-label for="photo" :value="__('Foto')" />
                        <x-input.input-file id="photo" class="mt-1 w-full" type="file" name="photo"
                            :value="old('photo', $participant->photo)" />
                        <x-input.input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="name" :value="__('Nama Siswa')" />
                        <x-input.text-input id="name" class="mt-1 w-full" type="text" name="name"
                            :value="old('name', $participant->name)" required autofocus autocomplete="name" />
                        <x-input.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="gender" :value="__('Jenis Kelamin')" />
                        <x-input.select-input id="gender" class="mt-1 w-full" type="text" name="gender" required
                            autofocus autocomplete="gender">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki - Laki"
                                {{ old('gender', $participant->gender) == 'Laki - Laki' ? ' selected' : ' ' }}>Laki -
                                Laki
                            </option>
                            <option value="Perempuan"
                                {{ old('gender', $participant->gender) == 'Perempuan' ? ' selected' : ' ' }}>
                                Perempuan</option>
                        </x-input.select-input>
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="phone" :value="__('No Telpon')" />
                        <x-input.text-input id="phone" class="mt-1 w-full" type="number" name="phone"
                            :value="old('phone', $participant->phone)" required autofocus autocomplete="phone" />
                        <x-input.input-error :messages="$errors->get('phone')" class="mt-2" />
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

</x-app-layout>
