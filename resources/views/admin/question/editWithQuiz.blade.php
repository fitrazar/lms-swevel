@section('title', 'Edit Data Pertanyaan')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <a href="{{ route('dashboard.question.index') }}">
                    <x-button.info-button>
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </x-button.info-button>
                </a>

                <x-form action="{{ route('dashboard.quiz.updateWithQuiz', $quiz->slug) }}" method="POST"
                    class="md:grid md:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')

                    <div class="mt-4">
                        <div>
                            <x-input.input-label for="quiz_id" :value="__('Pilih Kuis')" />
                            <x-input.select-input id="quiz_id" name="quiz_id" class="w-full" readonly>
                                <option value="{{ $quiz->id }}" selected>{{ $quiz->title }}</option>
                            </x-input.select-input>
                        </div>
                    </div>
                    <div id="questions-container" class="space-y-4">
                        <!-- Existing Questions -->
                        @foreach ($questions as $question)
                            <div class="p-4 border rounded bg-base-200 space-y-2 mt-4 existing-question"
                                data-index="{{ $loop->index }}" data-id="{{ $question->id }}">
                                <div>
                                    <x-input.input-label for="question_{{ $loop->index }}" value="Pertanyaan" />
                                    <x-input.text-area name="questions[{{ $loop->index }}][question_text]"
                                        id="question_{{ $loop->index }}" class="w-full" :value="$question->question_text" />
                                </div>

                                <div>
                                    <x-input.input-label for="type_{{ $loop->index }}" value="Tipe Pertanyaan" />
                                    <x-input.select-input name="questions[{{ $loop->index }}][type]"
                                        id="type_{{ $loop->index }}" class="w-full question-type"
                                        data-index="{{ $loop->index }}">
                                        <option value="single_choice"
                                            {{ $question->type == 'single_choice' ? 'selected' : '' }}>Pilihan Tunggal
                                        </option>
                                        <option value="multiple_choice"
                                            {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda
                                        </option>
                                        <option value="matching" {{ $question->type == 'matching' ? 'selected' : '' }}>
                                            Menjodohkan</option>
                                        <option value="essay" {{ $question->type == 'essay' ? 'selected' : '' }}>Essay
                                        </option>
                                    </x-input.select-input>
                                </div>

                                <div>
                                    <x-input.input-label for="point_{{ $loop->index }}" value="Poin" />
                                    <x-input.text-input type="number" name="questions[{{ $loop->index }}][point]"
                                        id="point_{{ $loop->index }}" class="w-full question-point"
                                        value="{{ $question->point }}" />
                                </div>

                                <div class="options-container space-y-2" data-question="{{ $loop->index }}">
                                    <!-- Populate options dynamically -->
                                    @if ($question->type == 'single_choice' || $question->type == 'multiple_choice')
                                        @foreach ($question->options as $optionIndex => $option)
                                            <div class="flex items-center space-x-2 mb-2">
                                                @if ($question->type == 'single_choice')
                                                    <input type="radio"
                                                        name="questions[{{ $loop->parent->index }}][options][is_correct]"
                                                        value="{{ $optionIndex }}"
                                                        {{ $option->is_correct ? 'checked' : '' }} class="radio" />
                                                @elseif ($question->type == 'multiple_choice')
                                                    <input type="checkbox"
                                                        name="questions[{{ $loop->parent->index }}][options][{{ $optionIndex }}][is_correct]"
                                                        value="1" {{ $option->is_correct ? 'checked' : '' }}
                                                        class="checkbox" />
                                                @endif
                                                <input type="text"
                                                    name="questions[{{ $loop->parent->index }}][options][{{ $optionIndex }}][option_text]"
                                                    placeholder="Masukkan Opsi" value="{{ $option->option_text }}"
                                                    class="input input-bordered w-full" />
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <x-button.danger-button type="button" class="delete-question-btn btn-sm mt-2"
                                    data-id="{{ $question->id }}">Hapus Pertanyaan</x-button.danger-button>
                            </div>
                        @endforeach
                    </div>

                    <x-button.primary-button type="button" id="add-question-btn">
                        Tambah Pertanyaan
                    </x-button.primary-button>

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
            document.addEventListener('DOMContentLoaded', function() {
                const questionsContainer = document.getElementById('questions-container');
                const addQuestionBtn = document.getElementById('add-question-btn');
                let questionCount = {{ $questions->count() }};

                addQuestionBtn.addEventListener('click', function() {
                    questionCount++;
                    addQuestionBlock(questionCount);
                });

                function addQuestionBlock(index) {
                    const questionBlock = document.createElement('div');
                    questionBlock.classList.add('p-4', 'border', 'rounded', 'bg-base-200', 'space-y-2', 'mt-4');
                    questionBlock.dataset.index = index;

                    questionBlock.innerHTML = `
                        <div>
                            <x-input.input-label for="question_${index}" value="Pertanyaan" />
                            <x-input.text-area name="questions[${index}][question_text]" id="question_${index}" class="w-full"></x-input.text-area>
                        </div>
                        <div>
                            <x-input.input-label for="type_${index}" value="Tipe Pertanyaan" />
                            <x-input.select-input name="questions[${index}][type]" id="type_${index}" class="w-full question-type" data-index="${index}">
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option value="single_choice">Pilihan Tunggal</option>
                                <option value="multiple_choice">Pilihan Ganda</option>
                                <option value="matching">Menjodohkan</option>
                                <option value="essay">Essay</option>
                            </x-input.select-input>
                        </div>
                        <div>
                            <x-input.input-label for="point_${index}" value="Poin" />
                            <x-input.text-input type="number" name="questions[${index}][point]" id="point_${index}" class="w-full question-point" />
                        </div>
                        <div class="options-container space-y-2" data-question="${index}"></div>
                        <x-button.danger-button type="button" class="delete-question-btn btn-sm mt-2" data-index="${index}">Hapus Pertanyaan</x-button.danger-button>
                    `;
                    questionsContainer.appendChild(questionBlock);
                }

                questionsContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('delete-question-btn')) {
                        event.target.closest('.p-4').remove();
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
