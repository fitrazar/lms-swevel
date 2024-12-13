@section('title', 'Dashboard')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @hasrole('participant')
                <x-card.card-custom class="static glass mt-6">
                    <p class="mb-2">Selamat Datang, {{ auth()->user()->participant->name }}
                    </p>
                </x-card.card-custom>
                @if (auth()->user()->participant->enrolls->count() > 0)
                    <div class="grid grid-cols-1 md::grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                        <x-card.card-custom class="static glass chart-container">
                            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Aktif & Belum Aktif</h5>
                            <hr>
                            <canvas id="courseStatus"></canvas>
                        </x-card.card-custom>
                        <x-card.card-custom class="static glass chart-container">
                            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Selesai & Belum Selesai</h5>
                            <hr>
                            <canvas id="courseCompleted"></canvas>
                        </x-card.card-custom>
                        <x-card.card-custom class="static glass chart-container">
                            <h5 class="mb-2 text-lg font-bold tracking-tight">Completion Rates</h5>
                            <hr>
                            <canvas id="courseRate"></canvas>
                        </x-card.card-custom>
                        <x-card.card-custom class="static glass chart-container col-span-3">
                            <h5 class="mb-2 text-lg font-bold tracking-tight">Progress Kursus</h5>
                            <hr>
                            <canvas id="courseProgress"></canvas>
                        </x-card.card-custom>
                        <x-card.card-custom class="static glass chart-container col-span-3">
                            <h5 class="mb-2 text-lg font-bold tracking-tight">Kursus Selesai</h5>
                            <hr>
                            <canvas id="courseDone"></canvas>
                        </x-card.card-custom>
                    </div>
                @endif
            @endrole
            @hasrole('participant')
                @include('partials.participant')
            @endrole
        </div>
    </div>


    <x-slot name="script">
        <script>
            const role = "{{ auth()->user()->roles->pluck('name')[0] }}";
            const enroll = "{{ auth()->user()->participant->enrolls->count() }}";

            if (role == 'participant' && enroll > 0) {
                new Chart($('#courseStatus').get(0).getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Aktif', 'Belum Aktif'],
                        datasets: [{
                            label: 'Kursus Aktif & Belum Aktif',
                            data: [{{ $totalActive }},
                                {{ $totalInActive }}
                            ],
                            backgroundColor: [
                                'rgb(54, 162, 235)',
                                'rgb(255, 99, 132)',
                            ],
                            color: '#ffff',
                            hoverOffset: 4
                        }]
                    },

                });

                new Chart($('#courseCompleted').get(0).getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: ['Selesai', 'Belum Selesai'],
                        datasets: [{
                            label: 'Kursus Selesai & Belum Selesai',
                            data: [{{ $totalCompleted }},
                                {{ $totalNotCompleted }}
                            ],
                            backgroundColor: [
                                'rgb(54, 162, 235)',
                                'rgb(255, 99, 132)',
                            ],
                            color: '#ffff',
                            hoverOffset: 4
                        }]
                    },
                });
                new Chart($('#courseRate').get(0).getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Belum Dimulai', 'Sedang Berlangsung', 'Selesai'],
                        datasets: [{
                            label: 'Completion Rates',
                            data: [{{ $courseStatus['belum_dimulai'] }},
                                {{ $courseStatus['sedang_berlangsung'] }},
                                {{ $courseStatus['selesai'] }}
                            ],
                            backgroundColor: ['rgb(255, 99, 132)', '#FFCE56', 'rgb(54, 162, 235)'],
                            color: '#ffff',
                            hoverOffset: 4
                        }]
                    },
                });

                new Chart($('#courseProgress').get(0).getContext('2d'), {
                    type: 'bar',
                    options: {
                        indexAxis: 'y',
                    },
                    data: {
                        labels: {!! json_encode($totalProgress->pluck('title')) !!},
                        datasets: [{
                            axis: 'y',
                            label: 'Progress (%)',
                            data: {!! json_encode($totalProgress->pluck('progress')) !!},
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(153, 102, 255, 1)',
                            ],
                            borderWidth: 1,
                            color: '#ffff',
                            hoverOffset: 4
                        }]
                    },
                });

                new Chart($('#courseDone').get(0).getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($chartData['labels']),
                        datasets: [{
                            label: 'Kursus Selesai',
                            data: @json($chartData['data']),
                            fill: false,
                            borderWidth: 1,
                            borderColor: 'rgb(75, 192, 192)',
                            color: '#ffff',
                            tension: 0.1,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                }
                            }
                        }
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
