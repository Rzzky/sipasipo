@extends('layout.app')

@section('title', 'Permintaan Peminjaman')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Permintaan Peminjaman</h2>
    <p class="text-gray-600">Setujui atau tolak permintaan peminjaman barang dari user.</p>
</div>

<div class="bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diajukan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $request->user->username ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->barang->nama_barang ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->jumlah }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($request->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at ? $request->created_at->diffForHumans() : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <form action="{{ route('request.peminjaman.approve', $request->id_peminjaman) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?')">
                                    @csrf
                                    <button type="submit" class="font-medium text-green-600 hover:text-green-800 transition">Setujui</button>
                                </form>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('request.peminjaman.reject', $request->id_peminjaman) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?')">
                                    @csrf
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-800 transition">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-gray-500">Tidak ada permintaan peminjaman baru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($requests->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $requests->links() }}
    </div>
    @endif
</div>
@endsection