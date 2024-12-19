@section('title', 'Laporan Progress Peserta')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="flex justify-start space-x-4">


                    <x-form id="export-form" action="{{ route('dashboard.instructor.report.exportProgress') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <x-button.info-button id="export-button" type="submit">
                            <i class="fa-regular fa-file-excel"></i>
                            Export
                        </x-button.info-button>
                    </x-form>

                    <a href="{{ route('dashboard.instructor.report.exportProgressPdf') }}">
                        <x-button.primary-button id="export-pdf" type="button">
                            <i class="fa-solid fa-print"></i>
                            Export PDF
                        </x-button.primary-button>
                    </a>
                </div>

                <div class="relative overflow-x-auto mt-5">
                    <table id="progresses" class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Kursus
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Peserta
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Progress
                                </th>
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


                let dataTable = $('#progresses').DataTable({
                    buttons: [
                        // 'copy', 'excel', 'csv', 'pdf', 'print',
                        'colvis'
                    ],
                    processing: true,
                    search: {
                        return: true
                    },
                    serverSide: true,
                    ajax: '{{ url()->current() }}',
                    columns: [{
                            data: null,
                            name: 'no',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'course_title',
                            name: 'course_title'
                        },
                        {
                            data: 'participant_name',
                            name: 'participant_name'
                        },
                        {
                            data: 'progress',
                            name: 'progress'
                        },
                    ]
                });
            });
        </script>
    </x-slot>
</x-app-layout>
