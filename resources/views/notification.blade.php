@section('title', 'Notifikasi')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div role="tablist" class="tabs tabs-lifted">
                @php
                    $groupedNotifications = auth()->user()->notifications->groupBy(fn($notification) => $notification->data['type'] ?? '-');
                @endphp

                @forelse ($groupedNotifications as $type => $notifications)
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" 
                        @if ($loop->first) checked="checked" @endif 
                        aria-label="{{ $type }}" />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                        @foreach ($notifications as $notification)
                            <div class="flex flex-wrap justify-between items-center mb-4">
                                <div>
                                    <h2 class="font-semibold">{{ $notification->data['title'] }}</h2>
                                    <p>{{ $notification->data['message'] }}</p>
                                </div>
                                <a href="{{ $notification->data['link'] }}">
                                    <x-button.primary-button>
                                        Lihat
                                    </x-button.primary-button>
                                </a>
                            </div>
                            <div class="divider"></div>
                        @endforeach
                    </div>
                @empty
                    <input type="radio" name="my_tabs_1" role="tab" class="tab" 
                        aria-label="No Notifications" checked="checked" />
                    <div role="tabpanel" class="tab-content bg-base-100 border-base-300 rounded-box p-6">
                        Notifikasi Tidak Ditemukan
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
