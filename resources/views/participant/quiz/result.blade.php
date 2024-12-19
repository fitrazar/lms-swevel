@section('title', 'Hasil Kuis')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default>
                <h1 class="text-xl font-bold">Hasil Kuis: {{ $quiz->title }} -
                    {{ $quiz->material->topic->course->title }}</h1>
                <p>Nilai Akhir: {{ $attempt->score }}</p>
                <p>Tanggal: {{ $attempt->attempt_date }}</p>
                @if ($attempt->is_late)
                    <p>Telat: {{ $attempt->difference }} Menit</p>
                @endif
                @if ($result->feedback)
                    <p>Catatan Mentor : {{ $result->feedback }}</p>
                @endif

                <div class="mt-6">
                    <h2 class="text-lg font-bold">Review Soal</h2>
                    <ol class="list-decimal pl-5 mt-3">
                        @foreach ($questions as $question)
                            <li class="mb-4">
                                <p class="font-semibold">{{ $question->question_text }}</p>
                                <div class="options">
                                    @foreach ($question->options as $option)
                                        <div class="flex items-center mb-2">
                                            <input type="radio" class="radio checked:bg-blue-500" disabled
                                                {{ in_array($option->id, $userAnswers) ? 'checked' : '' }}>
                                            <span
                                                class="ml-3 {{ in_array($option->id, $userAnswers) ? ($option->is_correct ? 'text-primary' : 'text-error') : '' }}">{{ $option->option_text }}
                                                @if (in_array($option->id, $userAnswers))
                                                    <span class="text-sm font-bold">
                                                        ({{ $option->is_correct ? 'Benar' : 'Salah' }})
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <a href="{{ route('dashboard.participant.quiz.index') }}" class="btn btn-primary mt-4">Kembali ke
                    Daftar
                    Kuis</a>
            </x-card.card-default>
        </div>
    </div>

</x-app-layout>
