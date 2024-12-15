<script>
    const role = "{{ auth()->user()->roles->pluck('name')[0] }}";
    const enroll = "{{ auth()->user()->participant?->enrolls?->count() }}";

    if (role == 'participant' && enroll > 0) {
        new Chart($('#courseStatus').get(0).getContext('2d'), {
            type: 'doughnut',
            options: {
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const filter = index === 0 ? 'active' : 'inactive';
                        const url = `/dashboard?search=&filter=${filter}#course`;
                        window.location.href = url;
                    }
                },
            },
            data: {
                labels: ['Aktif', 'Belum Aktif'],
                datasets: [{
                    label: 'Kursus Aktif & Belum Aktif',
                    data: [{{ $totalActive }}, {{ $totalInActive }}],
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
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const courseId = {!! json_encode($totalProgress?->pluck('slug')) !!}[index];
                        const url = `/course/${courseId}`;
                        window.location.href = url;
                    }
                },
            },
            data: {
                labels: {!! json_encode($totalProgress?->pluck('title')) !!},
                datasets: [{
                    axis: 'y',
                    label: 'Progress (%)',
                    data: {!! json_encode($totalProgress?->pluck('progress')) !!},
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
