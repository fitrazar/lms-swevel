@section('title', 'Data Mentor')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="flex justify-start space-x-4">
                    <a href="{{ route('dashboard.admin.instructor.create') }}">
                        <x-button.primary-button>
                            <i class="fa-solid fa-plus"></i>
                            Tambah Data
                        </x-button.primary-button>
                    </a>

                </div>
                <div class="relative overflow-x-auto mt-5">
                    <table id="instructors" class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Mentor
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jenis Kelamin
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
            function openDeleteModal(id) {
                confirmDelete.onclick = function() {
                    performDelete(id);
                };
                deleteModal.showModal();
            }

            function performDelete(id) {
                fetch(`/dashboard/instructor/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            deleteModal.close();
                            $('#instructors').DataTable().ajax.reload();
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


                let dataTable = $('#instructors').DataTable({
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
                        url: '{{ route('dashboard.admin.instructor.index') }}',
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
                            data: 'user.email',
                            name: 'user.email'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return `
                                <div class="flex justify-center gap-2 w-full flex-wrap">
                                    <a href="{{ url('/dashboard/instructor/${full.id}/edit') }}">
                                        <x-button.info-button type="button" class="btn-sm text-white"><i class="fa-regular fa-pen-to-square"></i>Edit</x-button.info-button>
                                    </a>
                                    <x-button.danger-button class="btn-sm text-white" onclick="openDeleteModal(${full.id})">
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
