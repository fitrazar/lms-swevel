@section('title', 'Tambah Materi Kursus')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.material.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>


                <x-form action="{{ route('dashboard.material.store') }}" class="md:grid md:grid-cols-2 gap-4"
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
                        <x-input.input-label for="title_topic" :value="__('Judul Sub Bab / Topik')" />
                        <x-input.text-input id="title_topic" class="mt-1 w-full" type="text" name="title_topic"
                            :value="old('title_topic')" required autofocus autocomplete="title_topic" />
                        <x-input.input-error :messages="$errors->get('title_topic')" class="mt-2" />
                    </div>

                    {{-- Hidden Input --}}
                    <x-input.text-input id="slug" class="mt-1 w-full" type="hidden" name="slug"
                        :value="old('slug')" required autofocus autocomplete="slug" />

                    <div class="mt-4">
                        <x-input.input-label for="order_topic" :value="__('Urutan Sub Bab')" />
                        <x-input.select-input id="order_topic" class="select2 mt-1 w-full" name="order_topic" required
                            autofocus autocomplete="order">
                            <option value="" disabled selected>Pilih Urutan Sub Bab</option>
                            @for ($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ old('order_topic') == $i ? ' selected' : ' ' }}>
                                    {{ $i }}</option>
                            @endfor
                        </x-input.select-input>
                        <x-input.input-error :messages="$errors->get('order_topic')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="type" :value="__('Jenis Materi')" />
                        <x-input.select-input id="type" class="mt-1 w-full" name="type" required autofocus
                            autocomplete="type">
                            <option value="" disabled selected>Pilih Jenis Materi</option>
                            <option value="document" {{ old('type') == 'document' ? ' selected' : ' ' }}>
                                Kalimat Deksriptif</option>
                            <option value="video" {{ old('type') == 'video' ? ' selected' : ' ' }}>
                                Video</option>
                            <option value="assignment" {{ old('type') == 'assignment' ? ' selected' : ' ' }}>
                                Tugas</option>
                            <option value="quiz" {{ old('type') == 'quiz' ? ' selected' : ' ' }}>
                                Quiz</option>
                        </x-input.select-input>
                        <x-input.input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div class="mt-4 col-span-2">
                        <x-input.input-label for="summernote" :value="__('Konten Materi')" />
                        <x-input.text-area id="summernote" class="mt-1 w-full" type="text" name="content"
                            :value="old('content')" required autofocus autocomplete="content" />
                        <x-input.input-error :messages="$errors->get('content')" class="mt-2" />
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
                const courses = @json($courses);

                function updateOrderOptions(selectedCourseId) {
                    const selectedCourse = courses.find(course => course.id == selectedCourseId);
                    const usedOrders = selectedCourse.topics.map(topic => topic.order);

                    $('#order_topic').find('option').each(function() {
                        if (usedOrders.includes(parseInt($(this).val()))) {
                            $(this).attr('disabled', 'disabled');
                        } else {
                            $(this).removeAttr('disabled');
                        }
                    });
                }

                $('#course_id').on('change', function() {
                    const selectedCourseId = $(this).val();
                    updateOrderOptions(selectedCourseId);
                });

                const initialCourseId = $('#course_id').val();
                if (initialCourseId) {
                    updateOrderOptions(initialCourseId);
                }

                $('.select2').select2();

                const title = document.querySelector("#title_topic");
                const slug = document.querySelector("#slug");

                title.addEventListener("keyup", function() {
                    let preslug = title.value;
                    preslug = preslug.replace(/ /g, "-");
                    slug.value = preslug.toLowerCase();
                });
            });
        </script>
    </x-slot>
</x-app-layout>
