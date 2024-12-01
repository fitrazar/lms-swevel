@section('title', 'Data Materi')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <div class="flex justify-start space-x-4">
                    <a href="{{ route('dashboard.admin.material.create') }}">
                        <x-button.primary-button>
                            <i class="fa-solid fa-plus"></i>
                            Tambah Data
                        </x-button.primary-button>
                    </a>

                </div>

                <div class="flex justify-start space-x-4">
                    <div class="mt-4">
                        <x-input.select-input id="kursus" class="select2 mt-1 w-full" type="text" name="kursus">
                            <option value="" disabled selected>Pilih Nama Kursus</option>
                            <option value="All">Semua</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}
                                </option>
                            @endforeach
                        </x-input.select-input>
                    </div>
                </div>

                <div class="relative overflow-x-auto mt-5">
                    <table id="materials" class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Kursus
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Judul Topik
                                </th>
                                {{-- <th scope="col" class="px-6 py-3">
                                    Materi
                                </th> --}}
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
                fetch(`/dashboard/material/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            deleteModal.close();
                            $('#materials').DataTable().ajax.reload();
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

                let dataTable = $('#materials').DataTable({
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
                        url: '{{ route('dashboard.admin.material.index') }}',
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
                            data: 'topic.course.title',
                            name: 'topic.course.title',
                            orderable: false,
                            searchable: true,
                        },
                        {
                            data: 'topic.title',
                            name: 'topic.title',
                            orderable: false,
                            searchable: true,
                        },
                        // {
                        //     data: null,
                        //     render: function(data) {
                        //         function stripHtml(html) {
                        //             var div = document.createElement("div");
                        //             div.innerHTML = html;
                        //             return div.textContent || div.innerText || "";
                        //         }

                        //         function truncateText(text, wordLimit) {
                        //             const words = text.split(" ");
                        //             return words.slice(0, wordLimit).join(" ") + (words.length >
                        //                 wordLimit ? "..." : "");
                        //         }

                        //         const cleanText = stripHtml(data.content);
                        //         const truncatedText = truncateText(cleanText, 20);

                        //         return truncatedText;
                        //     },
                        //     orderable: false,
                        //     searchable: false,
                        // },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return `
                               <div class="flex justify-center gap-2 w-full flex-wrap">
                                    <a href="{{ url('/dashboard/material/${full.id}/edit') }}">
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
                $('#kursus').change(function() {
                    dataTable.ajax.reload();
                });
            });
        </script>
    </x-slot>
</x-app-layout>
