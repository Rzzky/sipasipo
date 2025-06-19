@extends('layout.app')

@section('title', 'Detail Pengembalian')

@section('content')
<div class="max-w-4xl mx-auto">
     <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pengembalian</h2>
        <a href="{{ route('pengembalian.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pengembalian
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                 <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Detail Transaksi</h3>
                 <dl class="space-y-4">
                     <div>
                         <dt class="text-sm font-medium text-gray-500">ID Pengembalian</dt>
                         <dd class="mt-1 text-sm text-gray-900 font-semibold">#{{ $pengembalian->id_pengembalian }}</dd>
                     </div>
                     <div>
                         <dt class="text-sm font-medium text-gray-500">Tanggal Kembali</dt>
                         <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d F Y') }}</dd>
                     </div>
                     <div>
                         <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                         <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d F Y') }}</dd>
                     </div>
                      <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $pengembalian->label_status == 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $pengembalian->label_status == 'menunggu' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $pengembalian->label_status == 'penting' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $pengembalian->label_status == 'ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($pengembalian->label_status) }}
                            </span>
                        </dd>
                    </div>
                 </dl>
            </div>
            
            <div>
                 <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Info Barang & Peminjam</h3>
                 <dl class="space-y-4">
                     <div>
                         <dt class="text-sm font-medium text-gray-500">Nama Barang</dt>
                         <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $pengembalian->peminjaman->barang->nama_barang ?? 'N/A' }}</dd>
                     </div>
                     <div>
                         <dt class="text-sm font-medium text-gray-500">Jumlah</dt>
                         <dd class="mt-1 text-sm text-gray-900">{{ $pengembalian->peminjaman->jumlah ?? '0' }} unit</dd>
                     </div>
                     <div>
                         <dt class="text-sm font-medium text-gray-500">Peminjam</dt>
                         <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $pengembalian->peminjaman->user->username ?? 'N/A' }}</dd>
                     </div>
                 </dl>
            </div>
            
            <div class="md:col-span-2">
                 <h3 class="text-lg font-semibold text-gray-800 border-b pb-3 mb-4">Keterangan</h3>
                 <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded-md">
                     {{ $pengembalian->keterangan ?: 'Tidak ada keterangan.' }}
                 </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
            <form action="{{ route('pengembalian.destroy', $pengembalian->id_pengembalian) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data pengembalian ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection