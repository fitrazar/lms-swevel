@section('title', 'Laporan Kursus')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="flex justify-start space-x-4">


                    <x-form id="export-form" action="{{ route('dashboard.instructor.report.exportComplete') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <x-button.info-button id="export-button" type="submit">
                            <i class="fa-regular fa-file-excel"></i>
                            Export
                        </x-button.info-button>
                    </x-form>

                    <a href="{{ route('dashboard.instructor.report.exportCompletePdf') }}">
                        <x-button.primary-button id="export-pdf" type="button">
                            <i class="fa-solid fa-print"></i>
                            Export PDF
                        </x-button.primary-button>
                    </a>
                </div>

                <div class="relative overflow-x-auto mt-5">
                    <table id="completed-courses" class="table">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Total Peserta</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </x-card.card-default>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                $('#completed-courses').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('dashboard.instructor.report.complete') }}',
                    columns: [{
                            data: 'month_name',
                            title: 'Bulan'
                        },
                        {
                            data: 'total',
                            title: 'Total Peserta'
                        },
                    ],
                });
            });
        </script>

    </x-slot>
</x-app-layout>
