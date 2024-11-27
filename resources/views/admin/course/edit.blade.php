@section('title', 'Edit Data Kursus')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.admin.course.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>

                <x-form action="{{ route('dashboard.admin.course.update', $course->slug) }}"
                    class="md:grid md:grid-cols-2 gap-4" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="oldImage" value="{{ $course->cover }}">
                    <div class="mt-4">
                        @if ($course->cover)
                            <div class="avatar">
                                <div class="w-32 rounded-xl">
                                    <img src="{{ asset('storage/course/' . $course->cover) }}" />
                                </div>
                            </div>
                        @endif
                        <img class="imgPreview h-auto max-w-lg mx-auto hidden" alt="image">
                        <x-input.input-label for="cover" :value="__('Cover')" />
                        <x-input.input-file id="cover" class="mt-1 w-full" type="file" name="cover"
                            :value="old('cover', $course->cover)" autofocus autocomplete="cover" onchange="previewImage()" />
                        <x-input.input-error :messages="$errors->get('cover')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="title" :value="__('Judul')" />
                        <x-input.text-input id="title" class="mt-1 w-full" type="text" name="title"
                            :value="old('title', $course->title)" required autofocus autocomplete="title" />
                        <x-input.input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- Hidden Input --}}
                    <x-input.text-input id="slug" class="mt-1 w-full" type="hidden" name="slug"
                        :value="old('slug', $course->slug)" required autofocus autocomplete="slug" />

                    <div class="mt-4">
                        <x-input.input-label for="instructors" :value="__('Mentor')" />
                        <x-input.select-input id="instructors" class="select2 mt-1 w-full" name="instructors[]" required
                            autofocus autocomplete="instructors" multiple>
                            @foreach ($instructors as $instructors)
                                @if (in_array($instructors->id, $course->instructors->pluck('id')->toArray()) || old('instructors') == $instructors->id)
                                    <option value="{{ $instructors->id }}" selected>
                                        {{ $instructors->name }}</option>
                                @else
                                    <option value="{{ $instructors->id }}">
                                        {{ $instructors->name }}</option>
                                @endif
                            @endforeach
                        </x-input.select-input>
                        <x-input.input-error :messages="$errors->get('instructors')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="start_date" :value="__('Tanggal Mulai')" />
                        <x-input.text-input id="start_date" class="mt-1 w-full" type="date" name="start_date"
                            :value="old('start_date', $course->start_date)" required autofocus autocomplete="start_date" />
                        <x-input.input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="end_date" :value="__('Tanggal Selesai')" />
                        <x-input.text-input id="end_date" class="mt-1 w-full" type="date" name="end_date"
                            :value="old('end_date', $course->end_date)" required autofocus autocomplete="end_date" />
                        <x-input.input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="duration" :value="__('Total Durasi')" />
                        <x-input.text-input id="duration" class="mt-1 w-full" type="number" name="duration"
                            :value="old('duration', $course->duration)" placeholder="900" />
                        <x-input.input-error :messages="$errors->get('duration')" class="mt-2" />
                    </div>

                    <div class="mt-4 col-span-2">
                        <x-input.input-label for="summernote" :value="__('Deksripsi')" />
                        <x-input.text-area id="summernote" class="mt-1 w-full" type="text" name="description"
                            :value="old('description', $course->description)" required autofocus autocomplete="description" />
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
            const title = document.querySelector("#title");
            const slug = document.querySelector("#slug");

            title.addEventListener("keyup", function() {
                let preslug = title.value;
                preslug = preslug.replace(/[^a-zA-Z0-9\s]/g, "");
                preslug = preslug.replace(/ /g, "-");
                slug.value = preslug.toLowerCase();
            });

            function previewImage() {
                const image = document.querySelector('#cover')
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
