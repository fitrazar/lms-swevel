<section class="mt-5">
    <div class="py-8 mt-1">
        <h2 class="text-center py-4 font-bold md:text-2xl text-lg">Kursus Saya</h2>
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
                        @endphp

                        <p>Progress</p>
                        <progress class="progress progress-primary w-56" value="{{ $progressValue }}"
                            max="100"></progress>
                        <span class="text-sm font-thin">{{ $progressValue }}%</span>
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
                        <div class="grid h-20 card bg-base-300 rounded-box place-items-center">Anda belum terdaftar di
                            kursus manapun
                        </div>
                    </div>
                </x-card.card-default>
            @endforelse
        </div>
    </div>
</section>
