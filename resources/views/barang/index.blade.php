@extends('layout.app')

@section('title', 'Daftar Barang')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Manajemen Barang</h2>
    <a href="{{ route('barang.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
        <i class="fas fa-plus mr-2"></i> Tambah Barang
    </a>
</div>

<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan nama..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select id="filterKategori" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
            <select id="filterStatus" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Semua Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="tidak tersedia">Tidak Tersedia</option>
            </select>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white" id="barangTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($barang as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->kode_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->kategori->nama_kategori }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                           <div>{{ $item->jumlah }} <span class="text-gray-400">total</span></div>
                           <div class="text-xs">{{ $item->tersedia }} <span class="text-green-500">tersedia</span></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->kondisi == 'Baik')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                            @elseif($item->kondisi == 'Rusak Ringan')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Rusak Ringan</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rusak Berat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             @if($item->status == 'tersedia')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                           <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('barang.show', $item->id_barang) }}" class="text-gray-500 hover:text-indigo-600 transition" title="Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('barang.edit', $item->id_barang) }}" class="text-gray-500 hover:text-indigo-600 transition" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600 transition" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                           </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-10 text-gray-500">Tidak ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterKategori = document.getElementById('filterKategori');
        const filterStatus = document.getElementById('filterStatus');
        const tableRows = document.querySelectorAll('#barangTable tbody tr');

        function filterTable() {
            const search = searchInput.value.toLowerCase();
            const kategori = filterKategori.value.toLowerCase();
            const status = filterStatus.value.toLowerCase();

            tableRows.forEach(row => {
                if (!row.querySelector('td')) return; // Skip if it's the 'no data' row

                const namaBarang = row.cells[1].textContent.toLowerCase();
                const kategoriBarang = row.cells[2].textContent.toLowerCase();
                const statusBarang = row.cells[5].textContent.trim().toLowerCase();
                
                const show = namaBarang.includes(search) &&
                             (kategori === '' || kategoriBarang.includes(kategori)) &&
                             (status === '' || statusBarang.includes(status));
                
                row.style.display = show ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterKategori.addEventListener('change', filterTable);
        filterStatus.addEventListener('change', filterTable);
    });
</script>
@endpush
@endsection