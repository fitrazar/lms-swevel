@section('title', 'Hasil Kuis')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <div class="flex justify-start flex-wrap mb-3 mt-3">
                    @if ($result->feedback)
                        <a class="mr-3"
                            href="{{ route('dashboard.instructor.quiz.editFeedback', [$attempt->quiz->id, $result->id]) }}">
                            <x-button.info-button>
                                Edit Catatan
                            </x-button.info-button>
                        </a>
                        <a
                            href="{{ route('dashboard.instructor.quiz.deleteFeedback', [$attempt->quiz->id, $result->id]) }}">
                            <x-button.danger-button>
                                Hapus Catatan
                            </x-button.danger-button>
                        </a>
                    @else
                        <a href="{{ route('dashboard.instructor.quiz.feedback', [$attempt->quiz->id, $result->id]) }}">
                            <x-button.info-button>
                                Tambah Catatan
                            </x-button.info-button>
                        </a>
                    @endif
                </div>

                <h1 class="text-xl font-bold">Hasil Kuis: {{ $attempt->quiz->title }}</h1>
                <p>Nilai Akhir: {{ $attempt->score }}</p>
                <p>Nama Peserta: {{ $attempt->participant->name }}</p>
                <p>Tanggal Kuis: {{ $attempt->attempt_date }}</p>

                <div class="mt-6">
                    <h2 class="text-lg font-bold">Review Soal</h2>
                    <ol class="list-decimal pl-5 mt-3">
                        @foreach ($questions as $question)
                            <li class="mb-4">
                                <p class="font-semibold">{{ $question->question_text }}</p>
                                <div class="options">
                                    @foreach ($question->options as $option)
                                        <div class="flex items-center mb-2">
                                            <input type="radio"
                                                class="radio checked:{{ in_array($option->id, $userAnswers) ? ($option->is_correct ? 'text-blue-500' : 'text-error') : '' }}"
                                                disabled {{ in_array($option->id, $userAnswers) ? 'checked' : '' }}>
                                            <span
                                                class="ml-3 {{ in_array($option->id, $userAnswers) ? ($option->is_correct ? 'text-blue-500' : 'text-error') : '' }}">{{ $option->option_text }}
                                                @if (in_array($option->id, $userAnswers))
                                                    <span class="text-sm font-bold">
                                                        ({{ $option->is_correct ? 'Benar' : 'Salah' }})
                                                    </span>
                                                @endif
                                            </span>
                                            {{-- <span class="ml-3">{{ $option->option_text }}
                                                @if (in_array($option->id, $userAnswers))
                                                    <span class="text-sm font-bold">
                                                        ({{ $option->is_correct ? 'Benar' : 'Salah' }})
                                                    </span>
                                                @endif
                                            </span> --}}
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <a href="{{ route('dashboard.instructor.quiz.result') }}" class="btn btn-primary mt-4">Kembali</a>
            </x-card.card-default>
        </div>
    </div>
</x-app-layout>
