@extends('layout.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Detail Peminjaman</h2>
        <a href="{{ route('peminjaman.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Peminjaman
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
             <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Informasi Peminjaman</h3>
             <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                 <div>
                     <dt class="text-sm font-medium text-gray-500">Status</dt>
                     <dd class="mt-1">
                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $peminjaman->status == 'dikembalikan' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $peminjaman->status == 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $peminjaman->status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $peminjaman->status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $peminjaman->status == 'menunggu_pengembalian' ? 'bg-purple-100 text-purple-800' : '' }}">
                            {{ str_replace('_', ' ', ucfirst($peminjaman->status)) }}
                        </span>
                     </dd>
                 </div>
                 <div>
                     <dt class="text-sm font-medium text-gray-500">Label</dt>
                     <dd class="mt-1">
                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $peminjaman->label_status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $peminjaman->label_status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $peminjaman->label_status == 'penting' ? 'bg-red-100 text-red-800' : '' }}
                             {{ $peminjaman->label_status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($peminjaman->label_status) }}
                        </span>
                     </dd>
                 </div>
                 <div>
                     <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                     <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</dd>
                 </div>
                 @if($peminjaman->tanggal_kembali)
                 <div>
                     <dt class="text-sm font-medium text-gray-500">Tanggal Kembali</dt>
                     <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') }}</dd>
                 </div>
                 @endif
                 <div class="sm:col-span-2">
                     <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                     <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->keterangan ?: 'Tidak ada keterangan.' }}</dd>
                 </div>
             </dl>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Barang</h3>
                <dl>
                    <dt class="text-sm font-medium text-gray-500">Nama</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $peminjaman->barang->nama_barang ?? 'N/A' }}</dd>
                    <dt class="mt-4 text-sm font-medium text-gray-500">Jumlah</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $peminjaman->jumlah }} unit</dd>
                </dl>
            </div>
             <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Peminjam</h3>
                <dl>
                    <dt class="text-sm font-medium text-gray-500">Username</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $peminjaman->user->username ?? 'N/A' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <div class="mt-8 flex justify-end space-x-4">
        @if($peminjaman->status == 'menunggu')
            <form action="{{ route('request.peminjaman.reject', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak permintaan ini?')">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-md"><i class="fas fa-times mr-2"></i>Tolak</button>
            </form>
            <form action="{{ route('request.peminjaman.approve', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui permintaan ini?')">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition shadow-md"><i class="fas fa-check mr-2"></i>Setujui</button>
            </form>
        @endif

        @if($peminjaman->status == 'dipinjam')
             <form action="{{ route('peminjaman.kembalikan', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Ajukan pengembalian untuk barang ini?')">
                @csrf
                @method('PUT')
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-md"><i class="fas fa-undo-alt mr-2"></i>Ajukan Pengembalian</button>
            </form>
        @endif
    </div>
</div>
@endsection