@section('title', $course->title)

<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (
                !auth()->user()->participant?->enrolls?->where('course_id', $course->id)->first() ||
                    auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'inactive')->first())
                <x-card.card-default class="static">
                    <x-alert.warning message="Kamu belum bisa akses menu ini." />
                </x-card.card-default>
            @else
                <div class="grid md:grid-cols-4 grid-cols-1 gap-4">
                    <button onclick="toggleSidebar()"
                        class="p-4 z-50 fixed bottom-16 left-3 lg:static bg-base-100 rounded shadow-md lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>

                    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"
                        onclick="toggleSidebar()"></div>

                    <div id="sidebar"
                        class="fixed top-0 left-0 h-screen w-56 bg-base-100 z-0 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:relative lg:block lg:w-56">
                        <ul class="menu p-4 space-y-3">
                            <li class="menu-title">Daftar Isi</li>
                            @foreach ($course->topics as $topic)
                                <li>
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $topic->slug) }}">
                                        {{ $topic->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    <div class="md:col-span-3 p-4">
                        @if (session()->has('success'))
                            <div class="mb-3">
                                <x-alert.success :message="session('success')" />
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <x-alert.warning :message="session('error')" />
                        @endif
                        @if (
                            $currentTopic->material->type == 'quiz' &&
                                $currentTopic->material->quiz?->quizAttempts
                                    ?->where('participant_id', auth()->user()->participant->id)->first())
                            <x-alert.warning message="Kamu sudah menjawab quiz ini." />
                            <x-form
                                action="{{ route('course.destroy', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <x-button.warning-button type="submit" class="mt-4 btn-sm text-white"
                                    onclick="return confirm('Kamu yakin?')"><i
                                        class="fa-solid fa-repeat"></i>Ulangi</x-button.warning-button>
                            </x-form>
                            @if ($nextTopic && $prevTopic)
                                <div class="flex justify-start gap-4">
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                        class="block mt-6">
                                        <x-button.primary-button type="button"
                                            class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                    </a>
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                        class="block mt-6">
                                        <x-button.primary-button type="button"
                                            class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                    </a>
                                </div>
                            @elseif ($nextTopic)
                                <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                    class="block mt-6">
                                    <x-button.primary-button type="button"
                                        class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                </a>
                            @else
                                <div class="flex justify-start gap-4">
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                        class="block mt-6">
                                        <x-button.primary-button type="button"
                                            class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                    </a>
                                    @if (!auth()->user()->participant?->progress?->where('topic_id', $currentTopic->id)->where('is_completed', '1')->first())
                                        @if ($currentTopic->material->type == 'quiz')
                                            <x-button.primary-button id="nextButton" class="block mt-6" type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6">Kirim
                                                Jawaban</x-button.primary-button>
                                        @elseif ($currentTopic->material->type == 'assignment')
                                            <x-button.primary-button id="submitButton" class="block mt-6" type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                Tugas</x-button.primary-button>
                                        @else
                                            <a href="javascript:void(0);"
                                                onclick="markAsDone('{{ route('course.done', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}')"
                                                class="block mt-6">
                                                <x-button.primary-button type="button"
                                                    class="btn-sm text-base-100">Selesai</x-button.primary-button>
                                            </a>
                                        @endif
                                    @else
                                        @if (
                                            $currentTopic->material->type == 'quiz' &&
                                                !$currentTopic->material->quiz?->quizAttempts
                                                    ?->where('quiz_id', $currentTopic->material->quiz->id)->where('participant_id', auth()->user()->participant->id)->first())
                                            <x-button.primary-button id="nextButton" class="block mt-6" type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6">Kirim
                                                Jawaban</x-button.primary-button>
                                        @elseif (
                                            $currentTopic->material->type == 'assignment' &&
                                                !$currentTopic->material->assignment?->results
                                                    ?->where('assignment_id', $currentTopic->material->assignment->id)->first())
                                            <x-button.primary-button id="submitButton" class="block mt-6" type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                Tugas</x-button.primary-button>
                                        @else
                                            <p class="block mt-6">Kamu telah menyelesaikan semua topik.</p>
                                        @endif
                                    @endif

                                </div>
                            @endif
                        @elseif (
                            $currentTopic->material->type == 'assignment' &&
                                $currentTopic->material->assignment?->results
                                    ?->where('assignment_id', $currentTopic->material->assignment->id)->where('participant_id', auth()->user()->participant->id)->first())
                            <x-alert.warning message="Kamu sudah mengunggah tugas ini." />
                            <x-form
                                action="{{ route('course.destroyAssignment', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <x-button.warning-button type="submit" class="mt-4 btn-sm text-white"
                                    onclick="return confirm('Kamu yakin?')"><i class="fa-solid fa-repeat"></i>Upload
                                    Ulang</x-button.warning-button>
                            </x-form>
                            @if ($nextTopic && $prevTopic)
                                <div class="flex justify-start gap-4">
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                        class="block mt-6">
                                        <x-button.primary-button type="button"
                                            class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                    </a>
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                        class="block mt-6">
                                        <x-button.primary-button type="button"
                                            class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                    </a>
                                </div>
                            @elseif ($nextTopic)
                                <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                    class="block mt-6">
                                    <x-button.primary-button type="button"
                                        class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                </a>
                            @else
                                <div class="flex justify-start gap-4">
                                    <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                        class="block mt-6">
                                        <x-button.primary-button type="button"
                                            class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                    </a>
                                    @if (!auth()->user()->participant?->progress?->where('topic_id', $currentTopic->id)->where('is_completed', '1')->first())
                                        @if ($currentTopic->material->type == 'quiz')
                                            <x-button.primary-button id="nextButton" class="block mt-6"
                                                type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6">Kirim
                                                Jawaban</x-button.primary-button>
                                        @elseif ($currentTopic->material->type == 'assignment')
                                            <x-button.primary-button id="submitButton" class="block mt-6"
                                                type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                Tugas</x-button.primary-button>
                                        @else
                                            <a href="javascript:void(0);"
                                                onclick="markAsDone('{{ route('course.done', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}')"
                                                class="block mt-6">
                                                <x-button.primary-button type="button"
                                                    class="btn-sm text-base-100">Selesai</x-button.primary-button>
                                            </a>
                                        @endif
                                    @else
                                        @if (
                                            $currentTopic->material->type == 'quiz' &&
                                                !$currentTopic->material->quiz?->quizAttempts
                                                    ?->where('quiz_id', $currentTopic->material->quiz->id)->where('participant_id', auth()->user()->participant->id)->first())
                                            <x-button.primary-button id="nextButton" class="block mt-6"
                                                type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6">Kirim
                                                Jawaban</x-button.primary-button>
                                        @elseif (
                                            $currentTopic->material->type == 'assignment' &&
                                                !$currentTopic->material->assignment?->results
                                                    ?->where('assignment_id', $currentTopic->material->assignment->id)->first())
                                            <x-button.primary-button id="submitButton" class="block mt-6"
                                                type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                Tugas</x-button.primary-button>
                                        @else
                                            <p class="block mt-6">Kamu telah menyelesaikan semua topik.</p>
                                        @endif
                                    @endif

                                </div>
                            @endif
                        @else
                            {!! $currentTopic->material->content !!}
                            <form enctype="multipart/form-data" method="post"
                                action="{{ $currentTopic->material->type == 'quiz'
                                    ? route('course.submit', ['course' => $course->slug, 'topic' => $currentTopic->slug])
                                    : ($currentTopic->material->type == 'assignment'
                                        ? route('course.assignment', ['course' => $course->slug, 'topic' => $currentTopic->slug])
                                        : '#') }}">

                                @csrf

                                @if ($currentTopic->material->type == 'quiz')
                                    <h2 class="font-semibold text-2xl mt-3">
                                        {{ $currentTopic->material->quiz->title }}
                                    </h2>
                                    <p>{!! $currentTopic->material->quiz->description !!}</p>
                                    <div class="mt-4">
                                        <span class="countdown font-mono text-2xl" id="timer">
                                            <span style="--value:24;"></span> :
                                            <span style="--value:59;"></span>
                                        </span>
                                    </div>

                                    @if ($nextTopic)
                                        <input type="hidden" name="nextTopic" id="nextTopic"
                                            value="{{ $nextTopic->slug }}">
                                    @endif
                                    <input type="hidden" name="exit_count" id="exitCount" value="0">

                                    <div class="mt-4">
                                        <div class="divider">
                                            Soal
                                        </div>
                                    </div>
                                    @foreach ($currentTopic->material->quiz->questions as $question)
                                        <div class="mb-4">
                                            <p class="font-bold" data-question="question_{{ $question->id }}">
                                                {{ $question->question_text }}</p>
                                            <div class="options">
                                                @foreach ($question->options as $option)
                                                    {{-- @dd(session('answers.question_1')) --}}
                                                    <div class="flex justify-start">
                                                        <label class="label cursor-pointer space-x-3">
                                                            <input type="radio" name="question_{{ $question->id }}"
                                                                id="{{ $option->id }}" value="{{ $option->id }}"
                                                                class="radio checked:bg-blue-500"
                                                                {{ session('answers.question_' . $question->id) == $option->id ? 'checked' : '' }}>
                                                            <span class="label-text">{{ $option->option_text }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif ($currentTopic->material->type == 'assignment')
                                    <h2 class="font-semibold text-2xl mt-3">
                                        {{ $currentTopic->material->assignment->title }}
                                    </h2>
                                    @php
                                        $userDate = auth()
                                            ->user()
                                            ->participant?->enrolls?->where('course_id', $course->id)
                                            ->first();
                                        $parseDate = Carbon\Carbon::parse($userDate->activated_at);
                                        $deadline = $parseDate
                                            ->addDays((int) $currentTopic->material->assignment->deadline)
                                            ->endOfDay();
                                        $diffInDays = now()->diffInDays($deadline, false);
                                    @endphp
                                    <p>{!! $currentTopic->material->assignment->description !!}</p>
                                    @if (now()->gte($deadline))
                                        <div class="badge badge-error mt-3">
                                            {{ $deadline }}
                                        </div>
                                    @elseif ($diffInDays <= 2)
                                        <div class="badge badge-warning mt-3">
                                            {{ $deadline }}
                                        </div>
                                    @else
                                        <div class="badge badge-primary mt-3">
                                            {{ $deadline }}
                                        </div>
                                    @endif
                                    @if ($nextTopic)
                                        <input type="hidden" name="nextTopic" id="nextTopic"
                                            value="{{ $nextTopic->slug }}">
                                    @endif
                                    <div class="mt-4">
                                        <div class="divider">
                                            Upload Tugas
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <x-input.input-label for="file" :value="__('Upload Tugas')" />
                                        <x-input.input-file id="file" class="mt-1 w-full" type="file"
                                            name="file" :value="old('file')" required autofocus autocomplete="file" />
                                        <x-input.input-error :messages="$errors->get('file')" class="mt-2" />
                                    </div>
                                @endif

                                @if ($nextTopic && $prevTopic)
                                    <div class="flex justify-start gap-4">
                                        <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                            class="block mt-6">
                                            <x-button.primary-button type="button"
                                                class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                        </a>
                                        @if ($currentTopic->material->type == 'quiz')
                                            <x-button.primary-button id="nextButton" class="block mt-6"
                                                type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6">Kirim
                                                Jawaban</x-button.primary-button>
                                        @elseif ($currentTopic->material->type == 'assignment')
                                            <x-button.primary-button id="submitButton" class="block mt-6"
                                                type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                Tugas</x-button.primary-button>
                                        @else
                                            <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                                class="block mt-6">
                                                <x-button.primary-button type="button"
                                                    class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                            </a>
                                        @endif
                                    </div>
                                @elseif ($nextTopic)
                                    @if ($currentTopic->material->type == 'quiz')
                                        <x-button.primary-button id="nextButton" class="block mt-6" type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                            class="btn-sm text-base-100 block mt-6">Kirim
                                            Jawaban</x-button.primary-button>
                                    @elseif ($currentTopic->material->type == 'assignment')
                                        <x-button.primary-button id="submitButton" class="block mt-6" type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                            class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                            Tugas</x-button.primary-button>
                                    @else
                                        <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                            class="block mt-6">
                                            <x-button.primary-button type="button"
                                                class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                        </a>
                                    @endif
                                @else
                                    <div class="flex justify-start gap-4">
                                        <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                            class="block mt-6">
                                            <x-button.primary-button type="button"
                                                class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                        </a>
                                        @if (!auth()->user()->participant?->progress?->where('topic_id', $currentTopic->id)->where('is_completed', '1')->first())
                                            @if ($currentTopic->material->type == 'quiz')
                                                <x-button.primary-button id="nextButton" class="block mt-6"
                                                    type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                    class="btn-sm text-base-100 block mt-6">Kirim
                                                    Jawaban</x-button.primary-button>
                                            @elseif ($currentTopic->material->type == 'assignment')
                                                <x-button.primary-button id="submitButton" class="block mt-6"
                                                    type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                    class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                    Tugas</x-button.primary-button>
                                            @else
                                                <a href="javascript:void(0);"
                                                    onclick="markAsDone('{{ route('course.done', ['course' => $course->slug, 'topic' => $currentTopic->slug]) }}')"
                                                    class="block mt-6">
                                                    <x-button.primary-button type="button"
                                                        class="btn-sm text-base-100">Selesai</x-button.primary-button>
                                                </a>
                                            @endif
                                        @else
                                            @if (
                                                $currentTopic->material->type == 'quiz' &&
                                                    !$currentTopic->material->quiz?->quizAttempts
                                                        ?->where('quiz_id', $currentTopic->material->quiz->id)->where('participant_id', auth()->user()->participant->id)->first())
                                                <x-button.primary-button id="nextButton" class="block mt-6"
                                                    type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                    class="btn-sm text-base-100 block mt-6">Kirim
                                                    Jawaban</x-button.primary-button>
                                            @elseif (
                                                $currentTopic->material->type == 'assignment' &&
                                                    !$currentTopic->material->assignment?->results
                                                        ?->where('assignment_id', $currentTopic->material->assignment->id)->first())
                                                <x-button.primary-button id="submitButton" class="block mt-6"
                                                    type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                    class="btn-sm text-base-100 block mt-6" disabled>Kirim
                                                    Tugas</x-button.primary-button>
                                            @else
                                                <p class="block mt-6">Kamu telah menyelesaikan semua topik.</p>
                                            @endif
                                        @endif

                                    </div>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-slot name="script">
        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                const isOpen = sidebar.classList.contains('translate-x-0');

                if (isOpen) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                }
            }
        </script>
        <script>
            let type2 = "{{ $currentTopic->material->type }}";
            if (type2 == 'assignment') {
                let assignmentCompleted =
                    "{{ $currentTopic->material->assignment?->results?->where('assignment_id', $currentTopic->material->assignment->id)->where('participant_id', auth()->user()->participant->id)->first() }}";
                if (!assignmentCompleted) {
                    document.addEventListener('DOMContentLoaded', function() {
                        const fileInput = document.getElementById('file');
                        const submitButton = document.getElementById('submitButton');

                        fileInput.addEventListener('change', function() {
                            if (fileInput.files.length > 0) {
                                submitButton.disabled = false;
                            } else {
                                submitButton.disabled = true;
                            }
                        });
                    });
                }
            }
        </script>
        <script>
            let type = "{{ $currentTopic->material->type }}";
            if (type == 'quiz') {
                let quizCompleted =
                    "{{ $currentTopic->material->quiz?->quizAttempts?->where('quiz_id', $currentTopic->material->quiz->id)->where('participant_id', auth()->user()->participant->id)->first() }}";
                if (!quizCompleted) {

                    // Mengambil nilai exitCount dari session melalui route atau inisialisasi dengan 0
                    let exitCount = {{ session('exitCount', 0) }};

                    document.addEventListener('DOMContentLoaded', function() {
                        // Set nilai exitCount pada elemen form
                        document.getElementById('exitCount').value = exitCount;
                    });

                    document.addEventListener('visibilitychange', function(event) {
                        if (document.visibilityState !== 'visible') {
                            exitCount++;
                            // Menyimpan exitCount ke session menggunakan Ajax
                            updateExitCountSession(exitCount);
                            document.getElementById('exitCount').value = exitCount;
                            document.title = 'Hayo lagi nyontek ya';
                            console.log('exitCount', exitCount);
                        } else {
                            document.title = '{{ $course->title }}';
                        }
                    });

                    // Fungsi untuk memperbarui exitCount di session
                    function updateExitCountSession(count) {
                        axios.post('{{ route('course.exitcount') }}', {
                                exitCount: count
                            })
                            .then(response => {
                                // Berhasil menyimpan exitCount ke session, jika diperlukan
                                console.log('Exit count updated in session');
                            })
                            .catch(error => {
                                console.error('Error updating exit count in session:', error);
                            });
                    }

                    document.querySelectorAll("input[type='radio']").forEach((radio) => {
                        radio.addEventListener("change", (event) => {
                            const question = event.target.name;
                            const checked = event.target.id;

                            fetch('/update-answer', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .content,
                                    },
                                    body: JSON.stringify({
                                        question,
                                        checked
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Answer saved:', data);
                                })
                                .catch(error => {
                                    console.error('Error saving answer:', error);
                                });
                        });
                    });


                    function updateNextButtonState() {
                        const nextButton = document.getElementById('nextButton');
                        nextButton.disabled = !allQuestionsAnswered();
                    }

                    document.querySelector('form').addEventListener('submit', function(event) {
                        document.getElementById('exitCount').value = exitCount;
                    });

                    document.querySelectorAll("input").forEach((element) => {
                        element.addEventListener('input', updateNextButtonState);
                        element.addEventListener('change', updateNextButtonState);
                    });

                    document.addEventListener('DOMContentLoaded', updateNextButtonState);

                    function allQuestionsAnswered() {
                        let allAnswered = true;
                        const answeredQuestions = [];

                        document.querySelectorAll("input[type='radio']").forEach((radio) => {
                            const groupName = radio.name;
                            const radios = document.querySelectorAll(`[name="${groupName}"]`);
                            const questionElement = document.querySelector(`[data-question="${groupName}"]`)

                            if (![...radios].some(r => r.checked)) {
                                console.log(`question ${groupName} is not answered.`);
                                allAnswered = false;
                            }
                            // } else {
                            //     const selected = [...radios].find(r => r.checked);
                            //     const answerText = selected.nextElementSibling.textContent.trim();

                            //     updateAnswerSession(groupName, selected.id);

                            //     answeredQuestions.push({
                            //         question: questionElement.textContent.trim(),
                            //         answer: answerText,
                            //     });
                            // }
                        });

                        return allAnswered;
                    }

                    let quizTime = "{{ $currentTopic?->material?->quiz?->duration }}" || 0;

                    const quizStartTime = new Date("{{ $startTime }}").getTime(); // Waktu mulai dari server
                    const quizDuration = quizTime * 60 * 1000; // Durasi dalam milidetik

                    function startCountdown() {
                        const minuteSpan = document.querySelector("#timer span:first-child");
                        const secondSpan = document.querySelector("#timer span:last-child");

                        const interval = setInterval(() => {
                            const now = new Date().getTime();
                            const elapsed = now - quizStartTime;
                            const remaining = quizDuration - elapsed;

                            if (remaining <= 0) {
                                clearInterval(interval);
                                minuteSpan?.style.setProperty('--value', 0);
                                secondSpan?.style.setProperty('--value', 0);
                                document.getElementById('nextButton').disabled = true; // Disable submit button
                                alert("Waktu habis!");
                            } else {
                                const minutes = Math.floor(remaining / (1000 * 60));
                                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
                                minuteSpan?.style.setProperty('--value', minutes);
                                secondSpan?.style.setProperty('--value', seconds);
                            }
                        }, 1000);
                    }

                    document.addEventListener('DOMContentLoaded', startCountdown);
                }
            }

            function markAsDone(url) {
                axios.post(url, {}, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            alert(response.data.message);

                            const doneButton = document.querySelector('[onclick*="markAsDone"]');
                            if (doneButton) {
                                doneButton.remove();
                            }
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Terjadi kesalahan, coba lagi.');
                    });
            }
        </script>
    </x-slot>

</x-guest-layout>
