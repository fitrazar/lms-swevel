@section('title', $course->title)

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (!auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'active')->first())
                <x-card.card-default class="static">
                    <x-alert.warning message="Kamu belum bisa akses menu ini." />
                </x-card.card-default>
            @else
                <div class="grid md:grid-cols-4 grid-cols-1 gap-4">
                    <ul class="menu bg-base-200 rounded-box w-56">
                        <li>
                            <h2 class="menu-title">Daftar Isi</h2>
                            <ul>
                                @foreach ($course->topics as $topic)
                                    <li>
                                        <a
                                            href="{{ url('/course/' . $course->slug . '/read/' . $topic->slug) }}">{{ $topic->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    <div class="col-span-3">
                        @if (session()->has('success'))
                            <x-alert.success :message="session('success')" />
                        @endif
                        @if (
                            $currentTopic->material->type == 'quiz' &&
                                $currentTopic->material->quiz->quizAttempts->where('participant_id', auth()->user()->participant->id)->first())
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
                                        <a href="javascript:void(0);"
                                            onclick="markAsDone('{{ route('course.done', ['topic' => $currentTopic->slug]) }}')"
                                            class="block mt-6">
                                            <x-button.primary-button type="button"
                                                class="btn-sm text-base-100">Selesai</x-button.primary-button>
                                        </a>
                                    @else
                                        <p class="block mt-6">Kamu telah menyelesaikan semua topik.</p>
                                    @endif

                                </div>
                            @endif
                        @else
                            {!! $currentTopic->material->content !!}
                            <form method="{{ $currentTopic->material->type == 'quiz' ? 'post' : '#' }}"
                                action="{{ $currentTopic->material->type == 'quiz' ? route('course.submit', ['course' => $course->slug, 'topic' => $currentTopic->slug]) : '#' }}">
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

                                    <input type="hidden" name="nextTopic" id="nextTopic"
                                        value="{{ $nextTopic->slug }}">
                                    <input type="hidden" name="exit_count" id="exitCount" value="0">

                                    <div class="mt-4">
                                        <div class="divider">
                                            Soal
                                        </div>
                                    </div>
                                    @foreach ($currentTopic->material->quiz->questions as $question)
                                        <div class="mb-4">
                                            <p class="font-bold">{{ $question->question_text }}</p>
                                            <div class="options">
                                                @foreach ($question->options as $option)
                                                    <div class="flex justify-start">
                                                        <label class="label cursor-pointer space-x-3">
                                                            <input type="radio" name="question_{{ $question->id }}"
                                                                id="{{ $option->id }}" value="{{ $option->id }}"
                                                                class="radio checked:bg-blue-500"
                                                                {{ old('question_' . $question->id) == $option->id ? 'checked' : '' }}>
                                                            <span class="label-text">{{ $option->option_text }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                @endif

                                @if ($nextTopic && $prevTopic)
                                    <div class="flex justify-start gap-4">
                                        <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                            class="block mt-6">
                                            <x-button.primary-button type="button"
                                                class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                        </a>
                                        @if ($currentTopic->material->type == 'quiz')
                                            <x-button.primary-button id="nextButton" class="block mt-6" type="submit"
                                                onclick="return confirm('Apakah Anda yakin ingin mengirim data?')"
                                                class="btn-sm text-base-100 block mt-6">Kirim
                                                Jawaban</x-button.primary-button>
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
                                            <a href="javascript:void(0);"
                                                onclick="markAsDone('{{ route('course.done', ['topic' => $currentTopic->slug]) }}')"
                                                class="block mt-6">
                                                <x-button.primary-button type="button"
                                                    class="btn-sm text-base-100">Selesai</x-button.primary-button>
                                            </a>
                                        @else
                                            <p class="block mt-6">Kamu telah menyelesaikan semua topik.</p>
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
            let type = "{{ $currentTopic->material->type }}";
            if (type == 'quiz') {
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

                    document.querySelectorAll("input[type='radio']").forEach((radio) => {
                        const groupName = radio.name;
                        const radios = document.querySelectorAll(`[name="${groupName}"]`);
                        if (![...radios].some(r => r.checked)) {
                            console.log(`question ${groupName} is not answered.`);
                            allAnswered = false;
                        }
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

</x-app-layout>
