@section('title', 'Tambah Nilai')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.instructor.assignment.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>


                <x-form action="{{ route('dashboard.instructor.assignment.store', $result->id) }}"
                    class="md:grid md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    @csrf

                    <div class="mt-4">
                        <x-input.input-label for="score" :value="__('Nilai')" />
                        <x-input.text-input id="score" class="mt-1 w-full" type="number" name="score"
                            :value="old('score')" required autofocus autocomplete="score" />
                        <x-input.input-error :messages="$errors->get('score')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="feedback" :value="__('Catatan')" />
                        <x-input.text-input id="feedback" class="mt-1 w-full" type="text" name="feedback"
                            :value="old('feedback')" required autofocus autocomplete="feedback" />
                        <x-input.input-error :messages="$errors->get('feedback')" class="mt-2" />
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
