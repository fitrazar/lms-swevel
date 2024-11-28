@section('title', 'Detail Kursus ' . $course->title)
@if (auth()->user() && auth()->user()->roles->pluck('name')[0] != 'participant')
    {{ abort(403) }}
@endif
<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="breadcrumbs text-sm p-2">
                <ul>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="#">Kursus</a></li>
                    <li>{{ $course->title }}</li>
                </ul>
            </div>

            @if (
                !auth()->user()
                    ?->participant->enrolls?->where('course_id', $course->id)->where('status', 'active')->first() && now()->lte($course->start_date))
                <x-card.card-default class="static">
                    <x-alert.warning message="Kursus Belum Dibuka" />
                </x-card.card-default>
            @else
                <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                    <div class="mt-3 col-span-2 p-2">
                        @if (session()->has('success'))
                            <x-alert.success :message="session('success')" />
                        @endif
                        @if (session()->has('error'))
                            <x-alert.warning :message="session('error')" />
                        @endif
                        <h1 class="font-bold md:text-xl text-lg">{{ $course->title }}</h1>
                        <x-card.card-default class="static">
                            <img src="{{ $course->cover ? asset('storage/course/' . $course->cover) : asset('assets/images/no-image.png') }}"
                                alt="{{ $course->title }}" class="w-full rounded">
                            <div class="md:flex justify-between items-center">
                                <div>
                                    <i class="fa-solid fa-calendar-days hidden md:inline"></i>
                                    <div class="badge badge-outline">{{ $course->start_date }}
                                    </div>
                                    <span>-</span>
                                    <div class="badge badge-outline">{{ $course->end_date }}
                                    </div>
                                </div>
                                <div>
                                    <i class="fa-solid fa-book-open hidden md:inline"></i>
                                    <div class="badge badge-outline">{{ $course->topics->count() }} Pembahasan
                                    </div>
                                </div>
                            </div>
                        </x-card.card-default>
                        <x-card.card-default class="static mt-5">
                            <h2 class="font-bold text-lg">Deskripsi</h2>
                            {!! $course->description !!}

                            Mentor
                            @foreach ($course->instructors as $instructor)
                                <div class="badge badge-primary font-bold">{{ $instructor->name }}</div>
                            @endforeach
                            <div class="divider"></div>
                            <h2 class="font-bold text-lg">Cara Pendaftaran</h2>
                            <ul>
                                <li>Klik tombol Daftar Sekarang</li>
                                <li>Pada modal yang terbuka, silahkan anda ceklis persyaratan dan persetujuannya</li>
                                <li>Tunggu akun di aktifkan oleh pihak admin</li>
                            </ul>


                            @guest
                                <a href="{{ route('login') }}" class="mt-3">
                                    <x-button.primary-button type="submit"
                                        class="btn-md text-base-100 w-full">Login</x-button.primary-button>
                                </a>
                            @else
                                @if (auth()->user()->roles->pluck('name')[0] == 'participant')
                                    @if (auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'active')->first())
                                        <a
                                            href="{{ url('/course/' . $course->slug . '/read/' . $course->topics[0]->slug) }}">
                                            <x-button.primary-button type="button"
                                                class="btn-md text-base-100 w-full">Lihat</x-button.primary-button>
                                        </a>
                                    @else
                                        @if (auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'inactive')->first())
                                            <button class="btn" disabled="disabled">Daftar Sekarang</button>
                                        @else
                                            <x-button.primary-button type="button" onclick="formRegister.showModal()"
                                                class="btn-md text-base-100 w-full">Daftar
                                                Sekarang</x-button.primary-button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="mt-3">
                                        <x-button.primary-button type="submit"
                                            class="btn-md text-base-100 w-full">Login</x-button.primary-button>
                                    </a>
                                @endif
                            @endguest

                        </x-card.card-default>
                    </div>

                    <div>
                        <x-card.card-default class="static mt-5" title="Pembahasan">
                            <div class="flex w-full flex-col mt-3">
                                @foreach ($course->topics as $topic)
                                    <div class="cursor-pointer">
                                        @if (
                                            !auth()->user()
                                                ?->participant?->enrolls?->where('course_id', $course->id)->where('status', 'active')->first())
                                            <i class="fa-solid fa-lock"></i>
                                        @else
                                            <i class="fa-solid fa-lock-open"></i>
                                        @endif
                                        Bab {{ $topic->order }} :
                                        {{ $topic->title }}
                                    </div>
                                    <div class="divider"></div>
                                @endforeach
                            </div>

                            @guest
                                <a href="{{ route('login') }}" class="mt-3">
                                    <x-button.primary-button type="submit"
                                        class="btn-md text-base-100 w-full">Login</x-button.primary-button>
                                </a>
                            @else
                                @if (auth()->user()->roles->pluck('name')[0] == 'participant')
                                    @if (auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'active')->first())
                                        <a
                                            href="{{ url('/course/' . $course->slug . '/read/' . $course->topics[0]->slug) }}">
                                            <x-button.primary-button type="button"
                                                class="btn-md text-base-100 w-full mt-4">Lihat</x-button.primary-button>
                                        </a>
                                    @else
                                        @if (auth()->user()->participant?->enrolls?->where('course_id', $course->id)->where('status', 'inactive')->first())
                                            <button class="btn" disabled="disabled">Daftar Sekarang</button>
                                        @else
                                            <x-button.primary-button type="button" onclick="formRegister.showModal()"
                                                class="btn-md text-base-100 w-full mt-4">Daftar
                                                Sekarang</x-button.primary-button>
                                        @endif
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="mt-3">
                                        <x-button.primary-button type="submit"
                                            class="btn-md text-base-100 w-full">Login</x-button.primary-button>
                                    </a>
                                @endif
                            @endguest
                        </x-card.card-default>


                        <x-card.card-default class="static mt-5" title="Kursus Lainnya">
                            <div class="flex w-full flex-col">
                                @foreach ($courses as $course)
                                    <a href="{{ url('/course/' . $course->slug) }}" class="flex items-center gap-6">
                                        <div class="avatar">
                                            <div class="w-16 rounded">
                                                <img
                                                    src="{{ $course->cover ? asset('storage/course/' . $course->cover) : asset('assets/images/no-image.png') }}" />
                                            </div>
                                        </div>
                                        <div>
                                            {{ $course->title }}
                                        </div>
                                    </a>
                                    <div class="divider"></div>
                                @endforeach
                            </div>
                            <a href="{{ route('course.index') }}">
                                <x-button.primary-button type="button" class="btn-md text-base-100 w-full mt-4">Lihat
                                    Semua</x-button.primary-button>
                            </a>
                        </x-card.card-default>
                    </div>

                </div>
            @endif
        </div>
    </div>
    <x-modal.basic id="formRegister" title="Form Persetujuan">
        <x-form action="{{ route('course.store') }}">
            @csrf
            <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                <x-input.text-input id="course_id" type="hidden" name="course_id" :value="$course->id" />
                <div class="mt-4 col-span-2">
                    <div class="form-control">
                        <label class="label cursor-pointer">
                            <input type="checkbox" class="checkbox mr-3" name="agreement" id="agreement" />
                            <span class="label-text">Saya menyetujui persayaratan pendaftaran ini dan dapat
                                saya
                                pertanggung jawabkan jika saya melakukan kesalahan atau tidak menyelesaikan kursus
                                tepat
                                waktu.</span>
                        </label>
                    </div>
                </div>

                <x-button.primary-button type="submit" class="col-span-2 mt-3">
                    {{ __('Daftar') }}
                </x-button.primary-button>
            </div>
        </x-form>
        <div class="modal-action w-full">
            <form method="dialog">
                <button class="btn w-full">Tutup</button>
            </form>
        </div>
    </x-modal.basic>
</x-guest-layout>
