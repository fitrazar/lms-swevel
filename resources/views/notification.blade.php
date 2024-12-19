@section('title', 'Notifikasi')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div role="tablist" class="tabs tabs-lifted">
                @forelse (auth()->user()->notifications as $notification)
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" checked="checked"
                        aria-label="{{ $notification?->data['type'] ?? '-' }}" />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                        <h1 class="font-bold text-lg">{{ $notification->data['title'] }}</h1>
                        <div class="flex flex-wrap justify-between items-center">
                            <p>
                                {{ $notification->data['message'] }}
                            </p>
                            <a href="{{ $notification->data['link'] }}">
                                <x-button.primary-button>
                                    Lihat
                                </x-button.primary-button>
                            </a>
                        </div>
                        <div class="divider"></div>
                    </div>
                @empty
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" aria-label="Tab 1"
                        checked="checked" />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                        Notifikasi Tidak Ditemukan
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
