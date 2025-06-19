@extends('layout.app')

@section('title', 'Detail Barang')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Detail Barang</h2>
        <a href="{{ route('barang.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Barang
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900">{{ $barang->nama_barang }}</h3>
            <p class="text-sm text-gray-500">{{ $barang->kode_barang }}</p>
        </div>

        <div class="border-t border-gray-200 px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $barang->kategori->nama_kategori }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $barang->lokasi }}</dd>
            </div>
             <div>
                <dt class="text-sm font-medium text-gray-500">Kondisi</dt>
                <dd class="mt-1 text-sm">
                    @if($barang->kondisi == 'Baik')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Baik</span>
                    @elseif($barang->kondisi == 'Rusak Ringan')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Rusak Ringan</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rusak Berat</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                 <dd class="mt-1 text-sm">
                    @if($barang->status == 'tersedia')
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tersedia</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Tersedia</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Jumlah Total</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $barang->jumlah }} unit</dd>
            </div>
             <div>
                <dt class="text-sm font-medium text-gray-500">Stok</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $barang->tersedia }} tersedia, {{ $barang->dipinjam }} dipinjam</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Keterangan</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $barang->keterangan ?: 'Tidak ada keterangan.' }}</dd>
            </div>
             <div class="md:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $barang->updated_at->format('d F Y, H:i') }}</dd>
            </div>
        </div>
        
        <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
             <a href="{{ route('barang.edit', $barang->id_barang) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus barang ini?')">
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