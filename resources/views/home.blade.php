@section('title', 'Beranda')

<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- batas --}}
            <div class="relative isolate hero rounded-lg p-4">
                <svg viewBox="0 0 1108 632" aria-hidden="true"
                    class="absolute top-10 -z-10 max-w-none transform-gpu blur-3xl lg:top-[calc(50%-30rem)]">
                    <path fill="url(#175c433f-44f6-4d59-93f0-c5c51ad5566d)" fill-opacity=".2"
                        d="M235.233 402.609 57.541 321.573.83 631.05l234.404-228.441 320.018 145.945c-65.036-115.261-134.286-322.756 109.01-230.655C968.382 433.026 1031 651.247 1092.23 459.36c48.98-153.51-34.51-321.107-82.37-385.717L810.952 324.222 648.261.088 235.233 402.609Z">
                    </path>
                    <defs>
                        <linearGradient id="175c433f-44f6-4d59-93f0-c5c51ad5566d" x1="1220.59" x2="-85.053"
                            y1="432.766" y2="638.714" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#4F46E5"></stop>
                            <stop offset="1" stop-color="#80CAFF"></stop>
                        </linearGradient>
                    </defs>
                </svg>

                <div class="hero-content flex-col lg:flex-row-reverse">
                    <img src="{{ asset('assets/images/illustration.png') }}" class="max-w-sm rounded-lg" />
                    <div>
                        <h1 class="text-5xl font-extrabold">Kumpulan kursus digital, design sampai networking</h1>
                        <p class="py-6">
                            Temukan kursus teknologi yang kamu inginkan sekarang.
                        </p>
                        <button class="btn btn-primary"><i class="fa-solid fa-rocket"></i> Get Started</button>
                    </div>
                </div>
            </div>

            <div class="py-12">
                <h2 class="font-bold text-center md:text-3xl text-lg">Latest Course</h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 grid-cols-1 gap-6 p-4">
                    @forelse ($latestCourse as $course)
                        <x-card.card-image title="{{ $course->title }}"
                            image="{{ $course->cover ? 'storage/course/' . $course->cover : 'assets/images/no-image.png' }}"
                            class="static">
                            <p>{{ $course->excerpt }}</p>
                            <p class="font-bold">Total Durasi <span class="badge badge-primary">{{ $course->duration }}
                                    Menit</span></p>
                            <div class="card-actions md:justify-end justify-start items-center">
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
                        <x-card.card-default class="static md:col-span-2 lg:col-span-4 col-span-1">
                            <div class="flex flex-col w-full border-opacity-50">
                                <div class="grid h-20 card bg-base-300 rounded-box place-items-center">Data Tidak
                                    Ditemukan
                                </div>
                            </div>
                        </x-card.card-default>
                    @endforelse
                </div>
                <div class="mt-3 flex justify-center">
                    <a href="{{ url('/course') }}">
                        <x-button.primary-button class="ms-3" type="button">
                            {{ __('Lihat Semua') }}
                        </x-button.primary-button>
                    </a>
                </div>
            </div>

            <div class="py-12">
                <h2 class="font-bold text-center md:text-3xl text-lg">Team</h2>
                <p class="sm:text-lg text-center">Great team behind the quality content we
                    make.
                </p>

                <ul role="list"
                    class="mt-10 mx-auto grid content-center grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 md:gap-x-6 lg:max-w-5xl lg:gap-x-8 lg:gap-y-12 xl:grid-cols-3 lg:grid-cols-3">
                    <li>
                        <div class="space-y-4 card bg-base-100 shadow p-4">
                            <img class="mx-auto h-20 w-20 shadow border rounded-full lg:h-24 lg:w-24 object-cover"
                                src="{{ asset('assets/images/male.png') }}" alt="Muhammad Fitra Fajar">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm text-center">
                                    <h1 class="font-bold text-lg">
                                        Muhammad Fitra Fajar</h1>
                                    <p class="text-sm font-normal">Project Leader</p>
                                    <div class="flex justify-center gap-4 mt-4">
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-facebook"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-instagram"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4 card bg-base-100 shadow p-4">
                            <img class="mx-auto h-20 w-20 shadow border rounded-full lg:h-24 lg:w-24 object-cover"
                                src="{{ asset('assets/images/male.png') }}" alt="Dheny Cahyono">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm text-center">
                                    <h1 class="font-bold text-lg">
                                        Dheny Cahyono</h1>
                                    <p class="text-sm font-normal">Backend Developer</p>
                                    <div class="flex justify-center gap-4 mt-4">
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-facebook"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-instagram"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4 card bg-base-100 shadow p-4">
                            <img class="mx-auto h-20 w-20 shadow border rounded-full lg:h-24 lg:w-24 object-cover"
                                src="{{ asset('assets/images/male.png') }}" alt="Khairul Fanani">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm text-center">
                                    <h1 class="font-bold text-lg">
                                        Khairul Fanani</h1>
                                    <p class="text-sm font-normal">Frontend Developer</p>
                                    <div class="flex justify-center gap-4 mt-4">
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-facebook"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-instagram"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4 card bg-base-100 shadow p-4">
                            <img class="mx-auto h-20 w-20 shadow border rounded-full lg:h-24 lg:w-24 object-cover"
                                src="{{ asset('assets/images/male.png') }}" alt="Muhammad Renaldy Saputra">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm text-center">
                                    <h1 class="font-bold text-lg">
                                        Muhammad Renaldy Saputra</h1>
                                    <p class="text-sm font-normal">System Analyst</p>
                                    <div class="flex justify-center gap-4 mt-4">
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-facebook"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-instagram"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="space-y-4 card bg-base-100 shadow p-4">
                            <img class="mx-auto h-20 w-20 shadow border rounded-full lg:h-24 lg:w-24 object-cover"
                                src="{{ asset('assets/images/male.png') }}" alt="El Deroxvilon">
                            <div class="space-y-2">
                                <div class="text-xs font-medium lg:text-sm text-center">
                                    <h1 class="font-bold text-lg">
                                        El Deroxvilon</h1>
                                    <p class="text-sm font-normal">Backend Developer</p>
                                    <div class="flex justify-center gap-4 mt-4">
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-facebook"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-instagram"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i
                                                class="fa-brands fa-linkedin"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            {{--  --}}
        </div>
    </div>
</x-guest-layout>
