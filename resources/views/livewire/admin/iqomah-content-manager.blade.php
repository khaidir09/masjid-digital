<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Iqomah Content Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Himbauan Menuju Iqomah</h3>
                        @if ($this->canEdit())
                            <button wire:click="create()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Tambah Teks Baru
                            </button>
                        @endif
                    </div>

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Urutan</th>
                                    <th scope="col" class="px-6 py-3">Teks</th>
                                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                                    @if ($this->canEdit())
                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($iqomahContents as $ic)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ $ic->urutan }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $ic->teks }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($this->canEdit())
                                                <button wire:click="toggleStatus({{ $ic->id }})" class="relative inline-flex items-center h-6 rounded-full w-11 {{ $ic->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full transition {{ $ic->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                                </button>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs text-white {{ $ic->is_active ? 'bg-green-500' : 'bg-gray-500' }}">
                                                    {{ $ic->is_active ? 'Aktif' : 'Non-Aktif' }}
                                                </span>
                                            @endif
                                        </td>
                                        @if ($this->canEdit())
                                            <td class="px-6 py-4 text-center">
                                                <button wire:click="edit({{ $ic->id }})" class="text-blue-600 hover:text-blue-900 mx-1">Edit</button>
                                                <button wire:click="delete({{ $ic->id }})" onclick="confirm('Yakin ingin menghapus teks ini?') || event.stopImmediatePropagation()" class="text-red-600 hover:text-red-900 mx-1">Hapus</button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $this->canEdit() ? '4' : '3' }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data teks.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="{{ $editId ? 'update' : 'store' }}">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                {{ $editId ? 'Edit' : 'Tambah' }} Teks Iqomah
                            </h3>
                            <div class="mb-4">
                                <label for="teks" class="block text-sm font-medium text-gray-700">Teks</label>
                                <input type="text" id="teks" wire:model="teks" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('teks') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label for="urutan" class="block text-sm font-medium text-gray-700">Urutan Tampil (Opsional)</label>
                                <input type="number" id="urutan" wire:model="urutan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('urutan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Aktif Tampil di Layar</span>
                                </label>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ $editId ? 'Update' : 'Simpan' }}
                            </button>
                            <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
