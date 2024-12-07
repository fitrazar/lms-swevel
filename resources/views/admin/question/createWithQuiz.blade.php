@section('title', 'Tambah Data Pertanyaan')

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

                <x-form action="{{ route('dashboard.question.store') }}" class="md:grid md:grid-cols-2 gap-4"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mt-4">
                        <div>
                            <x-input.input-label for="quiz_id" :value="__('Pilih Kuis')" />
                            <x-input.select-input id="quiz_id" name="quiz_id" class="w-full" readonly>
                                <option value="" disabled selected>Pilih Kuis</option>
                                <option value="{{ $quiz->id }}"
                                    {{ old('quiz_id', $quiz->id) == $quiz->id ? ' selected' : ' ' }}>
                                    {{ $quiz->title }}</option>
                            </x-input.select-input>
                        </div>
                    </div>
                    <div id="questions-container" class="space-y-4">

                    </div>

                    <x-button.primary-button type="button" id="add-question-btn">
                        Tambah Pertanyaan
                    </x-button.primary-button>



                    <div class="col-span-2">
                        <x-button.primary-button type="submit" id="save-btn">
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
                const form = document.querySelector('form[action="{{ route('dashboard.question.store') }}"]');
                let questionCount = 0;

                // Load questions from localStorage
                const storedQuestions = localStorage.getItem('questions');
                if (storedQuestions) {
                    JSON.parse(storedQuestions).forEach(question => {
                        addQuestionBlock(question);
                    });
                }

                addQuestionBtn.addEventListener('click', function() {
                    questionCount++;
                    addQuestionBlock({
                        index: questionCount
                    });
                });

                function addQuestionBlock(data = {}) {
                    const {
                        index,
                        question_text = '',
                        options = []
                    } = data;

                    questionCount = index || questionCount;

                    const questionBlock = document.createElement('div');
                    questionBlock.classList.add('p-4', 'border', 'rounded', 'bg-base-200', 'space-y-2', 'mt-4');
                    questionBlock.dataset.index = questionCount;

                    questionBlock.innerHTML = `
                <div>
                    <x-input.input-label for="question_${questionCount}" value="Pertanyaan" />
                    <x-input.text-area name="questions[${questionCount}][question_text]" id="question_${questionCount}" class="w-full" required>${question_text}</x-input.text-area>
                </div>

                <div class="options-container space-y-2" data-question="${questionCount}">
                    ${generateOptionsHTML(questionCount, options)}
                </div>

                <x-button.danger-button type="button" class="delete-question-btn btn-sm mt-2" data-index="${questionCount}">Hapus Pertanyaan</x-button.danger-button>
            `;

                    questionsContainer.appendChild(questionBlock);
                    saveToLocalStorage();
                }

                function generateOptionsHTML(questionIndex, options = []) {
                    let optionsHTML = '';
                    for (let i = 0; i < 4; i++) {
                        optionsHTML += `
                    <div class="flex items-center space-x-2 mb-2">
                        <input type="radio" name="questions[${questionIndex}][correct_option]" value="${i}" ${options[i]?.is_correct ? 'checked' : ''} />
                        <input type="text" name="questions[${questionIndex}][options][${i}][option_text]" placeholder="Masukkan Opsi" value="${options[i]?.option_text || ''}" class="input input-bordered w-full" required />
                    </div>
                    `;
                    }
                    return optionsHTML;
                }

                questionsContainer.addEventListener('click', function(event) {
                    const target = event.target;

                    if (target.classList.contains('delete-question-btn')) {
                        const questionIndex = target.dataset.index;
                        const questionBlock = document.querySelector(`.p-4[data-index="${questionIndex}"]`);

                        if (questionBlock) {
                            questionBlock.remove();
                            reindexQuestions();
                            saveToLocalStorage();
                        }
                    }
                });

                function reindexQuestions() {
                    const questionBlocks = questionsContainer.querySelectorAll('.p-4');
                    questionBlocks.forEach((block, index) => {
                        block.dataset.index = index + 1;
                        block.querySelector('.delete-question-btn').dataset.index = index + 1;
                    });
                    questionCount = questionBlocks.length;
                }

                function saveToLocalStorage() {
                    const questions = [];
                    const questionBlocks = questionsContainer.querySelectorAll('.p-4');

                    questionBlocks.forEach(block => {
                        const index = block.dataset.index;
                        const questionText = block.querySelector(`[name="questions[${index}][question_text]"]`)
                            .value;

                        const options = [];
                        const optionElements = block.querySelectorAll('.options-container div');
                        optionElements.forEach((optionElement, optionIndex) => {
                            const optionText = optionElement.querySelector(
                                `[name="questions[${index}][options][${optionIndex}][option_text]"]`
                            ).value;
                            const isCorrect = optionElement.querySelector(
                                `[name="questions[${index}][correct_option]"]`
                            ).checked;

                            options.push({
                                option_text: optionText,
                                is_correct: isCorrect,
                            });
                        });

                        questions.push({
                            index,
                            question_text: questionText,
                            options
                        });
                    });

                    localStorage.setItem('questions', JSON.stringify(questions));
                }

                form.addEventListener('submit', async function(e) {
                    saveToLocalStorage();
                    e.preventDefault();

                    const questions = JSON.parse(localStorage.getItem('questions') || '[]');

                    try {
                        const response = await fetch("{{ route('dashboard.question.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                questions,
                                quiz_id: document.getElementById('quiz_id').value,
                            }),
                        });

                        if (!response.ok) {
                            throw new Error(await response.text());
                        }

                        const result = await response.json();
                        localStorage.removeItem('questions');
                        alert(result.message);
                        window.location.href = "{{ route('dashboard.question.index') }}";
                    } catch (error) {
                        console.error(error);
                        alert('Terjadi kesalahan saat menyimpan data.');
                    }
                });
            });
        </script>
    </x-slot>







</x-app-layout>
