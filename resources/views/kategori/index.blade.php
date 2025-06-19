@extends('layout.app')

@section('title', 'Kategori Barang')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Kategori</h2>
        <button type="button"
                @click="isModalOpen = true; modalTitle = 'Tambah Kategori Baru'; isEdit = false; formAction = '{{ route('kategori.store') }}'; document.getElementById('kategoriForm').reset();"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            <i class="fas fa-plus mr-2"></i> Tambah Kategori
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoris as $index => $kategori)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $kategori->nama_kategori }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kategori->deksripsi ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button @click="editKategori({{ $kategori->id_kategori }}, '{{ addslashes($kategori->nama_kategori) }}', '{{ addslashes($kategori->deksripsi) }}')"
                                            class="text-indigo-600 hover:text-indigo-900 transition duration-150" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('kategori.destroy', $kategori->id_kategori) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-data="{ isModalOpen: false, modalTitle: '', isEdit: false, formAction: '' }" @keydown.escape.window="isModalOpen = false" x-cloak>
        <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div @click.away="isModalOpen = false" class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800" x-text="modalTitle"></h3>
                    <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form id="kategoriForm" :action="formAction" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div>
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div class="mt-4">
                        <label for="deksripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deksripsi" id="deksripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="isModalOpen = false" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">Batal</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
        <script>
            function editKategori(id, nama, deskripsi) {
                const modal = document.querySelector('[x-data]');
                const alpine = modal.__x;

                alpine.data.isModalOpen = true;
                alpine.data.isEdit = true;
                alpine.data.modalTitle = 'Edit Kategori';
                alpine.data.formAction = `{{ url('kategori') }}/${id}`;
                
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('nama_kategori').value = nama;
                document.getElementById('deksripsi').value = deskripsi || '';
            }
        </script>
        @endpush
    </div>
@endsection