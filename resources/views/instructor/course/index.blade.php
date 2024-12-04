@section('title', 'Data Kursus')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="relative overflow-x-auto mt-5">
                    <table id="courses" class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Cover
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Judul Kursus
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal Mulai
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tanggal Selesai
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
            function openDeleteModal(slug) {
                confirmDelete.onclick = function() {
                    performDelete(slug);
                };
                deleteModal.showModal();
            }


            $(document).ready(function() {


                let dataTable = $('#courses').DataTable({
                    buttons: [
                        // 'copy', 'excel', 'csv', 'pdf', 'print',
                        'colvis'
                    ],
                    processing: true,
                    search: {
                        return: true
                    },
                    serverSide: true,
                    ajax: {
                        url: '{{ route('dashboard.instructor.course.index') }}',
                    },
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
                            data: null,
                            render: function(data, type, full, meta) {
                                return `<img src="{{ asset('storage/course/${full.cover}') }}" class="w-14 h-14" />`
                            },
                            orderable: false,
                            searchable: false,
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date'
                        },
                        {
                            data: 'end_date',
                            name: 'end_date'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return `
                                <div class="flex justify-center gap-2 w-full flex-wrap">
                                    <a href="{{ url('/dashboard/material/${full.slug}/create') }}">
                                        <x-button.success-button type="button" class="btn-sm text-white"><i class="fa-solid fa-receipt"></i>+ Materi</x-button.success-button>
                                    </a>
                                </div>
                            `;
                            }
                        },
                    ]
                });
            });
        </script>
    </x-slot>
</x-app-layout>
