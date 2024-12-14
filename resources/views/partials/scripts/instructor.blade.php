<script>
    const role = "{{ auth()->user()->roles->pluck('name')[0] }}";
    const enroll = "{{ auth()->user()->instructor?->courses?->count() }}";

    if (role == 'instructor' && enroll > 0) {
        new Chart($('#courseStatus').get(0).getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Belum Aktif'],
                datasets: [{
                    label: 'Peserta Aktif & Belum Aktif',
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
        new Chart($('#courseEnroll').get(0).getContext('2d'), {
            type: 'pie',
            data: {
                labels: @json($chartDataParticipant->pluck('label')),
                datasets: [{
                    label: 'Total Peserta',
                    data: @json($chartDataParticipant->pluck('value')),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    color: '#ffff',
                    hoverOffset: 4
                }]
            },

        });

        new Chart($('#courseEnroll2').get(0).getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($topCourse?->pluck('title')) !!},
                datasets: [{
                    label: 'Total Peserta',
                    data: {!! json_encode($topCourse?->pluck('total_participants')) !!},
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
