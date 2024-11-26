@section('title', $course->title)

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (!auth()->user()->participant?->enrolls?->where('course_id')->first())
                <x-card.card-default class="static">
                    <x-alert.warning message="Kamu belum bisa akses menu ini." />
                </x-card.card-default>
            @else
                <div class="grid md:grid-cols-4 grid-cols-1 gap-4">
                    <ul class="menu bg-base-200 rounded-box w-56">
                        <li>
                            <h2 class="menu-title">Daftar Isi</h2>
                            <ul>
                                @foreach ($course->topics as $topic)
                                    <li>
                                        <a
                                            href="{{ url('/course/' . $course->slug . '/read/' . $topic->slug) }}">{{ $topic->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    <div class="col-span-3">
                        {!! $currentTopic->material->content !!}


                        @if ($nextTopic && $prevTopic)
                            <div class="flex justify-start gap-4">
                                <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                    class="block mt-6">
                                    <x-button.primary-button type="button"
                                        class="btn-sm text-base-100">Kembali</x-button.primary-button>
                                </a>
                                <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                    class="block mt-6">
                                    <x-button.primary-button type="button"
                                        class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                                </a>
                            </div>
                        @elseif ($nextTopic)
                            <a href="{{ url('/course/' . $course->slug . '/read/' . $nextTopic->slug) }}"
                                class="block mt-6">
                                <x-button.primary-button type="button"
                                    class="btn-sm text-base-100">Selanjutnya</x-button.primary-button>
                            </a>
                        @else
                            <a href="{{ url('/course/' . $course->slug . '/read/' . $prevTopic->slug) }}"
                                class="block mt-6">
                                <x-button.primary-button type="button"
                                    class="btn-sm text-base-100">Kembali</x-button.primary-button>
                            </a>
                            <p class="block mt-6">Kamu telah menyelesaikan semua topik.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
