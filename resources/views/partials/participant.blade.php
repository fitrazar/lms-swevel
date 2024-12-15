<x-card.card-custom class="static glass mt-6">
    <p class="mb-2">Selamat Datang, {{ auth()->user()->participant->name }}
    </p>
</x-card.card-custom>
@if (auth()->user()->participant->enrolls->count() > 0)
    <div class="grid grid-cols-1 md::grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
        <x-card.card-custom class="static glass chart-container">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Aktif & Belum Aktif</h5>
            <hr>
            <canvas id="courseStatus"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Selesai & Belum Selesai</h5>
            <hr>
            <canvas id="courseCompleted"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Completion Rates</h5>
            <hr>
            <canvas id="courseRate"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container col-span-3">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Progress Kursus</h5>
            <hr>
            <canvas id="courseProgress"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container col-span-3">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Selesai</h5>
            <hr>
            <canvas id="courseDone"></canvas>
        </x-card.card-custom>
    </div>
@endif

<section class="py-16">
    <h2 class="text-center py-4 font-bold md:text-2xl text-lg">Kursus Saya</h2>
    <div class="grid gap-4 bg-base-100 shadow rounded p-6">
        <x-form method="GET" action="{{ route('dashboard.index') }}" class="mb-8 relative">
            <div class="flex items-center space-x-4">
                <x-input.text-input type="search" name="search" placeholder="Cari Kursus..." :value="$search"
                    class="w-full" />
            </div>
            <div class="flex justify-between items-center mt-3">
                <div>
                    <p class="font-bold">Ditampilkan {{ $activeCourses->count() }} - {{ $activeCourses->total() }}</p>
                </div>
                <div class="flex justify-end items-center">
                    <select class="select select-bordered w-full max-w-xs" name="filter" id="filter">
                        <option value="" selected disabled>Pilih Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Belum Aktif</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-start items-center gap-6">
                <x-button.primary-button type="submit">Cari</x-button.primary-button>
                <a href="{{ route('dashboard.index') }}">
                    <x-button.warning-button type="submit">Reset</x-button.warning-button>
                </a>
            </div>
        </x-form>

    </div>

    <div class="py-8 mt-1" id="course">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6 p-4">
            {{-- @dd($activeCourses) --}}
            @forelse ($activeCourses as $course)
                <x-card.card-image title="{{ $course->title }}"
                    image="{{ $course->cover ? 'storage/course/' . $course->cover : 'assets/images/no-image.png' }}">
                    <p>{{ $course->excerpt }}</p>
                    <p class="font-bold mt-2">Total Duration:
                        <span class="badge badge-primary">{{ $course->duration }}</span>
                    </p>
                    @if ($enroll = auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'active')->first())
                        @php
                            $progresses = $enroll->participant
                                ->progress()
                                ->whereHas('topic', function ($query) use ($course) {
                                    $query->where('course_id', $course->id);
                                })
                                ->get();

                            if ($progresses->count() > 0) {
                                $allCompleted = $progresses->every(fn($progress) => $progress->is_completed);

                                if ($allCompleted) {
                                    $progressValue = 100;
                                } else {
                                    $lastCompletedTopicOrder = $progresses
                                        ->map(fn($progress) => $progress->topic->order)
                                        ->max();

                                    $maxOrder = $course->topics->max('order');

                                    $progressValue = $maxOrder > 0 ? ($lastCompletedTopicOrder / $maxOrder) * 100 : 0;
                                }
                            } else {
                                $progressValue = 0;
                            }

                        @endphp

                        <p>Progress</p>
                        <progress class="progress progress-primary w-56" value="{{ $progressValue }}"
                            max="100"></progress>
                        <span class="text-sm font-thin">{{ round($progressValue) }}%</span>
                    @endif

                    <div class="card-actions justify-end mt-3">
                        @if (auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'inactive')->first())
                            <div class="badge badge-outline">Belum Aktif</div>
                        @else
                            <div class="badge badge-outline">Aktif</div>
                        @endif
                    </div>
                    @if (now()->lte($course->start_date))
                        <x-button.primary-button class="btn-md text-base-100 w-full" disabled="disabled">Learn
                            Now</x-button.primary-button>
                    @else
                        <a href="{{ url('/course/' . $course->slug) }}" class="mt-4 block">
                            <x-button.primary-button class="btn-md text-base-100 w-full">
                                Learn Now
                            </x-button.primary-button>
                        </a>
                    @endif
                </x-card.card-image>
            @empty
                <x-card.card-default class="static md:col-span-2 lg:col-span-3 col-span-1">
                    <div class="flex flex-col w-full border-opacity-50">
                        <div class="grid h-20 card bg-base-300 rounded-box place-items-center">Kursus Tidak Ditemukan
                        </div>
                    </div>
                </x-card.card-default>
            @endforelse
        </div>

        <div class="mt-6 text-center">
            <div class="join">
                {{ $activeCourses->appends(['search' => $search, 'filter' => $filter])->links() }}
            </div>
        </div>
    </div>
</section>
