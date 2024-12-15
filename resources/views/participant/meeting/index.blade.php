@section('title', 'Data Meeting')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->participant->enrolls->count() > 0)
                <div class="mb-3 flex justify-start items-center gap-6">
                    <x-form method="GET" action="{{ route('dashboard.participant.meeting.index') }}">
                        <x-input.text-input type="search" name="search" placeholder="Cari..." :value="$search" />
                        <x-button.primary-button type="submit">Cari</x-button.primary-button>
                    </x-form>
                </div>
                <div class="pt-10">
                    <div class="grid lg:grid-cols-3 gap-6 grid-cols-1 md:grid-cols-2">
                        @forelse ($meetings as $meeting)
                            <x-card.card-default class="static">
                                <h2 class="text-left font-bold text-lg">
                                    {{ $meeting->course->title }} ({{ $meeting->schedules->day }})
                                </h2>
                                <span class="text-xs">{{ $meeting->schedules->start_time }} -
                                    {{ $meeting->schedules->end_time }}</span>
                                <p class="text-justify">
                                    {{ $meeting->type }}
                                </p>

                                <div class="card-actions justify-end">
                                    <a class="badge badge-outline" href="{{ $meeting->link }}" target="_blank">
                                        Link
                                    </a>
                                </div>
                            </x-card.card-default>
                        @empty
                            <x-alert.warning message="Uppss: Data Tidak Ditemukan" />
                        @endforelse

                    </div>

                </div>
                <div class="join">
                    {{ $meetings->appends(['search' => $search])->links() }}
                </div>
            @else
                <x-card.card-default class="static">
                    <div class="flex flex-col w-full border-opacity-50">
                        <div class="grid h-20 card bg-base-300 rounded-box place-items-center">Kamu Belum Mendaftar
                            Kursus
                        </div>
                    </div>
                </x-card.card-default>
            @endif
        </div>
    </div>


</x-app-layout>
