@section('title', 'Tambah Meeting Kursus')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.meeting.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>


                <x-form action="{{ route('dashboard.meeting.store') }}" class="md:grid md:grid-cols-2 gap-4"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mt-4">
                        <x-input.input-label for="course_id" :value="__('Nama Kursus')" />
                        <x-input.select-input id="course_id" class="select2 mt-1 w-full" name="course_id" required
                            autofocus autocomplete="course_id">
                            <option value="" disabled selected>Pilih Kursus</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ old('course_id') == $course->id ? ' selected' : ' ' }}>
                                    {{ $course->title }}</option>
                            @endforeach
                        </x-input.select-input>
                        <x-input.input-error :messages="$errors->get('course_id')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="link" :value="__('Link Meet')" />
                        <x-input.text-input id="link" class="mt-1 w-full" type="text" name="link"
                            :value="old('link')" required autofocus autocomplete="link" />
                        <x-input.input-error :messages="$errors->get('link')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="type" :value="__('Jenis Meet')" />
                        <x-input.text-input id="type" class="mt-1 w-full" type="text" name="type"
                            :value="old('type')" required autofocus autocomplete="type" placeholder="Google Meet" />
                        <x-input.input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="day" :value="__('Hari')" />
                        <x-input.select-input id="day" class="mt-1 w-full" name="day" required autofocus>
                            <option value="" disabled selected>Pilih Hari</option>
                            @foreach ($days as $day)
                                <option value="{{ $day }}" {{ old('day') == $day ? ' selected' : ' ' }}>
                                    {{ $day }}</option>
                            @endforeach
                        </x-input.select-input>
                        <x-input.input-error :messages="$errors->get('day')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="start_time" :value="__('Jam Masuk')" />
                        <x-input.text-input id="start_time" class="mt-1 w-full" type="time" name="start_time"
                            :value="old('start_time')" required autofocus autocomplete="start_time" placeholder="Google Meet" />
                        <x-input.input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="end_time" :value="__('Jam Selesai')" />
                        <x-input.text-input id="end_time" class="mt-1 w-full" type="time" name="end_time"
                            :value="old('end_time')" required autofocus autocomplete="end_time" placeholder="Google Meet" />
                        <x-input.input-error :messages="$errors->get('end_time')" class="mt-2" />
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
