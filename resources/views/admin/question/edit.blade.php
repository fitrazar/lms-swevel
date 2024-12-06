@section('title', 'Edit Pertanyaan')

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

                <x-form action="{{ route('dashboard.question.update', $question->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Pertanyaan -->
                    <div>
                        <x-input.input-label for="question_text" :value="__('Pertanyaan')" />
                        <x-input.text-area id="question_text" class="w-full" name="question_text" required
                            :value="old('question_text', $question->question_text)" />
                        <x-input.input-error :messages="$errors->get('question_text')" class="mt-2" />
                    </div>

                    <!-- Opsi Jawaban -->
                    <div id="options-container" class="space-y-2">
                        @foreach ($question->options as $index => $option)
                            <div class="flex items-center space-x-2">
                                <input type="radio" name="correct_option" value="{{ $index }}"
                                    {{ $option->is_correct ? 'checked' : '' }} class="radio" />
                                <input type="text" name="options[{{ $index }}][option_text]"
                                    value="{{ old('options.' . $index . '.option_text', $option->option_text) }}"
                                    class="input input-bordered w-full" placeholder="Masukkan opsi" required />
                                <input type="hidden" name="options[{{ $index }}][id]"
                                    value="{{ $option->id }}" />
                            </div>
                        @endforeach
                    </div>

                    <div>
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
            document.addEventListener('DOMContentLoaded', function() {});
        </script>
    </x-slot>
</x-app-layout>
