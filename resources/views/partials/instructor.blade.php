<x-card.card-custom class="static glass mt-6">
    <p class="mb-2">Selamat Datang, {{ auth()->user()->instructor->name }}
    </p>
</x-card.card-custom>
@if (auth()->user()->instructor?->courses?->count() > 0)
    <div class="grid grid-cols-1 md::grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
        <x-card.card-custom class="static glass chart-container">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Peserta Aktif & Belum Aktif</h5>
            <hr>
            <canvas id="courseStatus"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container col-span-2">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Total Peserta</h5>
            <hr>
            <canvas id="courseEnroll"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container col-span-3">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Total Peserta (Bar)</h5>
            <hr>
            <canvas id="courseEnroll2"></canvas>
        </x-card.card-custom>
        <x-card.card-custom class="static glass chart-container col-span-3">
            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Selesai</h5>
            <hr>
            <canvas id="courseDone"></canvas>
        </x-card.card-custom>

    </div>
@endif
