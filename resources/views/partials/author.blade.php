<x-card.card-custom class="static glass mt-6">
    <p class="mb-2">Selamat Datang, Author
    </p>
</x-card.card-custom>
<div class="grid grid-cols-1 md::grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
    <x-card.card-custom class="static glass chart-container">
        <h5 class="mb-2 text-lg font-bold tracking-tight">Total Peserta & Mentor</h5>
        <hr>
        <canvas id="totalParticipantInstructor"></canvas>
    </x-card.card-custom>
    <x-card.card-custom class="static glass chart-container">
        <h5 class="mb-2 text-lg font-bold tracking-tight">Total Peserta Aktif & Belum Aktif</h5>
        <hr>
        <canvas id="totalEnrollment"></canvas>
    </x-card.card-custom>
    <x-card.card-custom class="static glass chart-container">
        <h5 class="mb-2 text-lg font-bold tracking-tight">Total Peserta</h5>
        <hr>
        <canvas id="courseEnroll"></canvas>
    </x-card.card-custom>
    <x-card.card-custom class="static glass chart-container">
        <h5 class="mb-2 text-lg font-bold tracking-tight">Total Kursus
        </h5>
        <p class="font-normal text-center">{{ $totalCourse }}</p>
    </x-card.card-custom>
    <x-card.card-custom class="static glass chart-container">
        <h5 class="mb-2 text-lg font-bold tracking-tight">Total Materi
        </h5>
        <p class="font-normal text-center">{{ $totalMaterial }}</p>
    </x-card.card-custom>
    <x-card.card-custom class="static glass chart-container">
        <h5 class="mb-2 text-lg font-bold tracking-tight">Total Tugas & Kuis
        </h5>
        <div class="flex justify-between items-center text-center">
            <div>
                <p class="font-sm">Tugas</p>
                <p class="font-normal">{{ $totalAssignment }}</p>
            </div>
            <div>
                <p class="font-sm">Kuis</p>
                <p class="font-normal">{{ $totalQuiz }}</p>
            </div>
        </div>
    </x-card.card-custom>

</div>
