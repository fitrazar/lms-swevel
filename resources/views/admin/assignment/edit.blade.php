@section('title', 'Edit Tugas Materi')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.assignment.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>


                <x-form action="{{ route('dashboard.assignment.update', $assignment->id) }}" method="POST"
                    class="md:grid md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mt-4">
                        <x-input.input-label for="material_id" :value="__('Nama Materi Topik')" />
                        <x-input.select-input id="material_id" class="select2 mt-1 w-full" name="material_id" required
                            autofocus autocomplete="material_id">
                            <option value="" disabled selected>Pilih Materi Topik</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}"
                                    {{ old('material_id', $assignment->material->id) == $material->id ? ' selected' : ' ' }}>
                                    {{ $material->topic->course->title }} - {{ $material->topic->title }}</option>
                            @endforeach
                        </x-input.select-input>
                        <x-input.input-error :messages="$errors->get('material_id')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="title" :value="__('Judul Tugas')" />
                        <x-input.text-input id="title" class="mt-1 w-full" type="text" name="title"
                            :value="old('title', $assignment->title)" required autofocus autocomplete="title" />
                        <x-input.input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="due_date" :value="__('Batas Pengumpulan')" />
                        <x-input.text-input id="due_date" class="mt-1 w-full" type="datetime-local" name="due_date"
                            :value="old('due_date', $assignment->due_date)" required autofocus autocomplete="due_date" />
                        <x-input.input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>

                    <div class="mt-4 col-span-2">
                        <x-input.input-label for="summernote" :value="__('Konten Materi')" />
                        <x-input.text-area id="summernote" class="mt-1 w-full" type="text" name="description"
                            :value="old('description', $assignment->description)" required autofocus autocomplete="description" />
                        <x-input.input-error :messages="$errors->get('description')" class="mt-2" />
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
            $(document).ready(function() {

                $('.select2').select2();

            });
        </script>
    </x-slot>
</x-app-layout>