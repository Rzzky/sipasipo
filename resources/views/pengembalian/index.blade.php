@extends('layout.app')

@section('title', 'Daftar Pengembalian')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Daftar Pengembalian</h2>
</div>

<div class="bg-white rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <form action="{{ route('pengembalian.index') }}" method="GET">
            <div class="flex items-center space-x-4">
                <div class="flex-grow">
                    <label for="periode" class="sr-only">Filter Periode</label>
                    <select name="periode" id="periode" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Semua Periode</option>
                        <option value="minggu" {{ request('periode') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan" {{ request('periode') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun" {{ request('periode') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pengembalian as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->peminjaman->user->username ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->peminjaman->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $item->label_status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $item->label_status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $item->label_status == 'penting' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $item->label_status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($item->label_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('pengembalian.show', $item->id_pengembalian) }}" class="text-gray-500 hover:text-indigo-600 transition" title="Detail"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('pengembalian.destroy', $item->id_pengembalian) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600 transition" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-gray-500">Tidak ada data pengembalian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection