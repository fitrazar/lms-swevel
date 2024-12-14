<script>
    new Chart($('#totalParticipantInstructor').get(0).getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Peserta', 'Mentor'],
            datasets: [{
                label: 'Total Peserta & Mentor',
                data: [{{ $totalParticipant }},
                    {{ $totalInstructor }}
                ],
                backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgba(75, 192, 192)',
                ],
                color: '#ffff',
                hoverOffset: 4
            }]
        },

    });
    new Chart($('#totalEnrollment').get(0).getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Aktif', 'Belum Aktif'],
            datasets: [{
                label: 'Total Peserta Aktif & Belum Aktif',
                data: [{{ $totalEnrollmentActive }},
                    {{ $totalEnrollmentInActive }}
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
        type: 'doughnut',
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
</script>
