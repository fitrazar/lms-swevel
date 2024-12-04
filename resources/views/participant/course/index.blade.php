@section('title', 'Kursus')

<x-guest-layout>
    <div class="py-8">

        <h2 class="font-bold text-center md:text-3xl text-lg mb-6">Courses List</h2>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Hero Section --}}
            <div class="relative isolate hero rounded-lg mb-8">
                <svg viewBox="0 0 1108 632" aria-hidden="true"
                    class="absolute top-10 -z-10 max-w-none transform-gpu blur-3xl lg:top-[calc(50%-30rem)]">
                    <path fill="url(#gradient)" fill-opacity=".2"
                        d="M235.233 402.609 57.541 321.573.83 631.05l234.404-228.441 320.018 145.945c-65.036-115.261-134.286-322.756 109.01-230.655C968.382 433.026 1031 651.247 1092.23 459.36c48.98-153.51-34.51-321.107-82.37-385.717L810.952 324.222 648.261.088 235.233 402.609Z">
                    </path>
                    <defs>
                        <linearGradient id="gradient" x1="1220.59" x2="-85.053" y1="432.766" y2="638.714"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#4F46E5"></stop>
                            <stop offset="1" stop-color="#80CAFF"></stop>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            {{-- Search Bar --}}
            <x-form method="GET" action="{{ route('course.index') }}" class="mb-8 relative">
                <div class="flex items-center space-x-4">
                    <x-input.text-input type="search" name="search" placeholder="Cari Kursus..." :value="$search"
                        class="w-full" />

                    <x-button.primary-button type="submit">Cari</x-button.primary-button>
                </div>
            </x-form>

            {{-- List Courses --}}
            <div class="py-8 mt-1"> <!-- Tambahkan kelas mt-8 untuk margin atas -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6 p-4">
                    @forelse ($courses as $course)
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
                                    <div class="badge badge-outline">{{ $course->updated_at->diffForHumans() }}</div>
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
                                <div class="grid h-20 card bg-base-300 rounded-box place-items-center">Data Tidak
                                    Ditemukan
                                </div>
                            </div>
                        </x-card.card-default>
                    @endforelse
                </div>
            </div>

            {{-- Teks Hasil Pagination di Atas --}}
            <div class="mt-6 text-center">
                <div class="join">
                    {{ $courses->appends(['search' => $search])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
