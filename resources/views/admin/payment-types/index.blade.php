<x-layouts.admin title="Manajemen Tipe Pembayaran">
   
    @if (session('success'))
        <div class="toast toast-bottom toast-center">
            <div class="alert alert-success">
                <span>{{ session('success') }}</span>
            </div>
        </div>
        <script>
        setTimeout(() => {
            document.querySelector('.toast')?.remove()
        }, 3000)
        </script>
    @endif

    @if (session('error'))
        <div class="toast toast-bottom toast-center">
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        </div>
        <script>
        setTimeout(() => {
            document.querySelector('.toast')?.remove()
        }, 3000)
        </script>
    @endif

    <div class="container mx-auto p-10">
        <div class="flex">
            <h1 class="text-3xl font-semibold mb-4">Manajemen Tipe Pembayaran</h1>
            <button class="btn btn-primary ml-auto" onclick="add_modal.showModal()">Tambah Tipe Pembayaran</button>
        </div>
        <div class="overflow-x-auto rounded-box bg-white p-5 shadow-xs">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="w-3/4">Nama Tipe Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentTypes as $index => $paymentType)
                        <tr>
                            <th>{{ ($paymentTypes->currentPage() - 1) * $paymentTypes->perPage() + $index + 1 }}</th>
                            <td>{{ $paymentType->nama }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary mr-2" onclick="openEditModal(this)" data-id="{{ $paymentType->id }}" data-nama="{{ $paymentType->nama }}">Edit</button>
                                <button class="btn btn-sm bg-red-500 text-white" onclick="openDeleteModal(this)" data-id="{{ $paymentType->id }}">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada tipe pembayaran tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $paymentTypes->links() }}
        </div>
    </div>

    <!-- Add Payment Type Modal -->
    <dialog id="add_modal" class="modal">
        <form method="POST" action="{{ route('admin.payment-types.store') }}" class="modal-box">
            @csrf
            <h3 class="text-lg font-bold mb-4">Tambah Tipe Pembayaran</h3>
            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Nama Tipe Pembayaran</span>
                </label>
                <input type="text" placeholder="Contoh: Transfer Bank, E-Wallet, Cash" class="input input-bordered w-full" name="nama" required />
                @error('nama')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="add_modal.close()" type="button">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Payment Type Modal -->
    <dialog id="edit_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')
            <input type="hidden" name="payment_type_id" id="edit_payment_type_id">
            <h3 class="text-lg font-bold mb-4">Edit Tipe Pembayaran</h3>
            <div class="form-control w-full mb-4">
                <label class="label mb-2">
                    <span class="label-text">Nama Tipe Pembayaran</span>
                </label>
                <input type="text" placeholder="Masukkan nama tipe pembayaran" class="input input-bordered w-full" id="edit_payment_type_name" name="nama" required />
                @error('nama')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="edit_modal.close()" type="button">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')
            <input type="hidden" name="payment_type_id" id="delete_payment_type_id">
            <h3 class="text-lg font-bold mb-4">Hapus Tipe Pembayaran</h3>
            <p>Apakah Anda yakin ingin menghapus tipe pembayaran ini?</p>
            <div class="modal-action">
                <button class="btn bg-red-500 text-white" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="button">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openEditModal(button) {
            const name = button.dataset.nama;
            const id = button.dataset.id;
            const form = document.querySelector('#edit_modal form');
            
            document.getElementById("edit_payment_type_name").value = name;
            document.getElementById("edit_payment_type_id").value = id;
            // Set action dengan parameter ID
            form.action = `/admin/payment-types/${id}`;
            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            document.getElementById("delete_payment_type_id").value = id;
            // Set action dengan parameter ID
            form.action = `/admin/payment-types/${id}`;
            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
