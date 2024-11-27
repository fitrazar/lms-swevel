@section('title', 'Data Kursus')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="flex justify-start space-x-4">
                    <a href="{{ route('dashboard.admin.course.create') }}">
                        <x-button.primary-button>
                            <i class="fa-solid fa-plus"></i>
                            Tambah Data
                        </x-button.primary-button>
                    </a>

                </div>
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

    <x-modal.basic id="deleteModal" title="Hapus Data">
        <p class="py-4">Apakah kamu yakin ingin menghapus data ini?</p>
        <div class="modal-action">
            <button class="btn btn-error" id="confirmDelete">Hapus</button>
            <button class="btn" onclick="document.getElementById('deleteModal').close()">Batal</button>
        </div>
    </x-modal.basic>

    <x-slot name="script">
        <script>
            function openDeleteModal(slug) {
                confirmDelete.onclick = function() {
                    performDelete(slug);
                };
                deleteModal.showModal();
            }

            function performDelete(slug) {
                fetch(`/dashboard/course/${slug}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            deleteModal.close();
                            $('#courses').DataTable().ajax.reload();
                            const alertContainer = document.createElement('div');
                            alertContainer.innerHTML = `
                            <x-alert.success message=${data.message} />
                                                    `;
                            document.querySelector('.max-w-7xl').prepend(alertContainer);
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan!');
                    });
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
                        url: '{{ route('dashboard.admin.course.index') }}',
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
                                    <a href="{{ url('/dashboard/course/${full.slug}/edit') }}">
                                        <x-button.info-button type="button" class="btn-sm text-white"><i class="fa-regular fa-pen-to-square"></i>Edit</x-button.info-button>
                                    </a>
                                    <x-button.danger-button class="btn-sm text-white" onclick="openDeleteModal('${full.slug}')">
                                        <i class="fa-regular fa-trash-can"></i>Hapus
                                    </x-button.danger-button>
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
