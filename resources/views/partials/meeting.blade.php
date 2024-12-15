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
        <a class="badge badge-outline" href="{{ $meeting->link }}" target="_blank">
            Link
        </a>
        <div class="card-actions justify-end">
            <a href="{{ route('dashboard.meeting.edit', $meeting->id) }}">
                <x-button.info-button type="button" class="btn-sm text-white"><i
                        class="fa-regular fa-pen-to-square"></i>Edit</x-button.info-button>
            </a>
            <x-button.danger-button class="btn-sm text-white" onclick="openDeleteModal({{ $meeting->id }})">
                <i class="fa-regular fa-trash-can"></i>Hapus
            </x-button.danger-button>
        </div>
    </x-card.card-default>
@empty
    <x-alert.warning message="Uppss: Data Tidak Ditemukan" />
@endforelse
