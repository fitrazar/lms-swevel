@section('title', 'Data Pendaftaran')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="flex justify-start space-x-4">
                    @role('author')
                        <x-form action="{{ route('dashboard.enrollment.updateAll') }}">
                            @csrf
                            <div class="relative">
                                <x-button.success-button>Aktifkan Semua</x-button.success-button>
                            </div>
                        </x-form>
                    @endrole


                    <x-form id="export-form" action="{{ route('dashboard.enrollment.export') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="kursusExport" name="kursusExport" value="">
                        <x-button.info-button id="export-button" type="submit">
                            <i class="fa-regular fa-file-excel"></i>
                            Export
                        </x-button.info-button>
                    </x-form>
                </div>

                <div class="mt-4">
                    <x-input.select-input id="kursus" class="mt-1 w-full" type="text" name="kursus">
                        <option value="" disabled selected>Pilih Kursus</option>
                        <option value="All">Semua
                        </option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}
                            </option>
                        @endforeach
                    </x-input.select-input>
                </div>

                <div class="relative overflow-x-auto mt-5">
                    <table id="enrollments" class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Peserta
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kursus
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
                $('#export-button').on('click', function() {
                    let kursus = $('#kursus').val();

                    $('#export-form #kursusExport').val(kursus);
                });

                let dataTable = $('#enrollments').DataTable({
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
                        url: '{{ route('dashboard.enrollment.index') }}',
                        data: function(d) {
                            d.kursus = $('#kursus').val();
                        }
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
                            data: 'participant.name',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: 'course.title',
                            orderable: true,
                            searchable: true,
                        },
                        {
                            data: null,
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                if (full.status == 'active') {
                                    if ("{{ Auth::user()->roles?->pluck('name')[0] == 'author' }}") {
                                        return `<div class="badge badge-primary">Aktif</div>
                                                <x-form action="{{ url('/dashboard/enrollment/${full.id}') }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button.danger-button type="submit" class="btn-sm text-white" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i>Hapus</x-button.danger-button>
                                                </x-form>
                                        `;
                                    } else {
                                        return `<div class="badge badge-primary">Aktif</div>
                                        `;
                                    }
                                } else {
                                    let action;
                                    if ("{{ Auth::user()->roles?->pluck('name')[0] == 'author' }}") {
                                        action = `
                                                <x-form action="{{ url('/dashboard/enrollment/${full.id}') }}" style="display: inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <x-button.info-button type="submit" class="btn-sm text-white">Aktifkan</x-button.danger-button>
                                                </x-form>
                                                <x-form action="{{ url('/dashboard/enrollment/${full.id}') }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-button.danger-button type="submit" class="btn-sm text-white" onclick="return confirm('Are you sure?')">Hapus</x-button.danger-button>
                                                </x-form>
                                            `;
                                    } else {
                                        action = `<div class="badge badge-warning">Belum Aktif</div>`;
                                    }
                                    return action;
                                }
                            }
                        },
                    ]
                });
                $('#kursus').change(function() {
                    dataTable.ajax.reload();
                });
            });
        </script>
    </x-slot>
</x-app-layout>
