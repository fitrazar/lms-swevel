<section class="mt-5">
    <div class="py-8 mt-1">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6 p-4">
            {{-- @dd($activeCourses) --}}
            @forelse ($activeCourses as $course)
                <x-card.card-image title="{{ $course->title }}"
                    image="{{ $course->cover ? 'storage/course/' . $course->cover : 'assets/images/no-image.png' }}">
                    <p>{{ $course->excerpt }}</p>
                    <p class="font-bold mt-2">Total Duration:
                        <span class="badge badge-primary">{{ $course->duration }} Menit</span>
                    </p>
                    <div class="card-actions justify-end mt-3">
                        @if (now()->lte($course->start_date))
                            <div class="badge badge-outline">Belum Dibuka</div>
                        @else
                            <div class="badge badge-outline">Terakhir diupdate</div>
                            <div class="badge badge-outline">{{ $course->updated_at->diffForHumans() }}
                            </div>
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
                        <div class="grid h-20 card bg-base-300 rounded-box place-items-center">belum ada
                            course aktif
                        </div>
                    </div>
                </x-card.card-default>
            @endforelse
        </div>
    </div>
</section>
