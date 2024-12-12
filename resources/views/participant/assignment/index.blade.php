@section('title', 'Data Tugas')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="relative overflow-x-auto mt-5">
                    <table id="results" class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kursus
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Topik
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Tugas
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Deadline
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
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

                let dataTable = $('#results').DataTable({
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
                            data: 'assignment.material.topic.course.title',
                            name: 'assignment.material.topic.course.title'
                        },
                        {
                            data: 'assignment.material.topic.title',
                            name: 'assignment.material.topic.title'
                        },
                        {
                            data: 'assignment.title',
                            name: 'assignment.title'
                        },
                        {
                            data: 'deadline.deadline',
                            name: 'deadline.deadline',
                            // render: function(data, type, full, meta) {
                            //     console.log(full.deadline.deadline);
                            //     `<span>${full.deadline}</span>`
                            // }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {


                                return `
                                        <a href="{{ url('/dashboard/participant/assignment/${full.id}/result') }}">
                                            <x-button.secondary-button type="button" class="btn-sm text-white">
                                                <i class="fa-solid fa-eye"></i> Lihat Nilai
                                            </x-button.secondary-button>
                                        </a>
                                    `;
                            }


                        },
                    ]
                });
            });
        </script>
    </x-slot>
</x-app-layout>
