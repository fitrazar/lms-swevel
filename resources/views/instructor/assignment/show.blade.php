@section('title', 'Hasil Tugas')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                <div class="flex justify-start flex-wrap mb-3 mt-3">
                    @if ($result->score)
                        <a class="mr-3" href="{{ route('dashboard.instructor.assignment.edit', $result->id) }}">
                            <x-button.info-button>
                                Edit Nilai
                            </x-button.info-button>
                        </a>
                        <a href="{{ route('dashboard.instructor.assignment.destroy', $result->id) }}">
                            <x-button.danger-button>
                                Hapus Nilai
                            </x-button.danger-button>
                        </a>
                    @else
                        <a href="{{ route('dashboard.instructor.quiz.create', $result->id) }}">
                            <x-button.info-button>
                                Tambah Nilai
                            </x-button.info-button>
                        </a>
                    @endif
                </div>

                <h1 class="text-xl font-bold">Hasil Tugas: {{ $result->assignment->title }} -
                    {{ $result->assignment->material->topic->course->title }}</h1>
                <p>Nilai: {{ $result->score ?? 'Belum Dinilai' }}</p>
                <p>Tanggal Dinilai: {{ $result->graded_at ?? '-' }}</p>
                <p>Tanggal Dikirim: {{ $result->submitted_at }}</p>
                @if ($result->is_late)
                    <p>Telat: {{ $result->difference }}</p>
                @endif

                <div class="mt-6">
                    <h2 class="text-lg font-bold">File Tugas</h2>
                    <a target="_blank"
                        href="{{ url('/storage/assignments/' . $result->participant->id . '/' . $result->assignment->id . '/' . $result->file_url) }}">Download</a>
                </div>

                <a href="{{ route('dashboard.participant.assignment.index') }}" class="btn btn-primary mt-4">Kembali ke
                    Daftar
                    Tugas</a>
            </x-card.card-default>
        </div>
    </div>

</x-app-layout>
