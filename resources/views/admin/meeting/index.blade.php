@section('title', 'Data Meeting')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-3 flex justify-start items-center gap-6">
                <x-form method="GET" action="{{ route('dashboard.meeting.index') }}">
                    <x-input.text-input type="search" name="search" placeholder="Cari..." :value="$search" />
                    <x-button.primary-button type="submit">Cari</x-button.primary-button>
                </x-form>

                <a href="{{ route('dashboard.meeting.create') }}">
                    <x-button.primary-button>
                        <i class="fa-solid fa-plus"></i>
                        Tambah Data
                    </x-button.primary-button>
                </a>
            </div>
            @if (session()->has('success'))
                <x-alert.success :message="session('success')" />
            @endif
            <div class="pt-10">
                <div class="grid lg:grid-cols-3 gap-6 grid-cols-1 md:grid-cols-2">
                    @include('partials.meeting', ['meetings' => $meetings])
                </div>

            </div>
            <div class="join">
                {{ $meetings->appends(['search' => $search])->links() }}
            </div>
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
            function fetchMeetings() {
                fetch('/dashboard/meeting?search={{ $search }}', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const meetingGrid = document.querySelector('.grid');
                        if (meetingGrid) {
                            meetingGrid.innerHTML = html;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching meetings:', error);
                    });
            }


            function openDeleteModal(id) {
                confirmDelete.onclick = function() {
                    performDelete(id);
                };
                deleteModal.showModal();
            }

            function performDelete(id) {
                fetch(`/dashboard/meeting/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            deleteModal.close();
                            const alertContainer = document.createElement('div');
                            alertContainer.innerHTML = `
                            <x-alert.success message=${data.message} />
                                                    `;
                            document.querySelector('.max-w-7xl').prepend(alertContainer);
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });

                            fetchMeetings();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan!');
                    });
            }
        </script>
    </x-slot>
</x-app-layout>
