@section('title', 'Hasil Tugas')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default>
                <h1 class="text-xl font-bold">Hasil Tugas: {{ $result->assignment->title }} -
                    {{ $result->assignment->material->topic->course->title }}</h1>
                <p>Nilai: {{ $result->score ?? 'Belum Dinilai' }}</p>
                <p>Tanggal Dinilai: {{ $result->graded_at ?? '-' }}</p>
                <p>Tanggal Dikirim: {{ $result->submitted_at }}</p>
                @if ($result->is_late)
                    <p>Telat: {{ $result->difference }}</p>
                @endif
                @if ($result->feedback)
                    <p>Catatan Mentor : {{ $result->feedback }}</p>
                @endif

                <div class="mt-6">
                    <h2 class="text-lg font-bold">File Kamu</h2>
                    <a target="_blank"
                        href="{{ url('/storage/assignments/' . Auth::user()->participant->id . '/' . $result->assignment->id . '/' . $result->file_url) }}">Download</a>
                </div>

                <a href="{{ route('dashboard.participant.assignment.index') }}" class="btn btn-primary mt-4">Kembali ke
                    Daftar
                    Tugas</a>
            </x-card.card-default>
        </div>
    </div>

</x-app-layout>
