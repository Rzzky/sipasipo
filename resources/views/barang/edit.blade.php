@extends('layout.app')

@section('title', 'Edit Barang')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Edit Barang</h2>
        <a href="{{ route('barang.index') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Barang
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700">Kode Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="id_kategori" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ old('id_kategori', $barang->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <hr>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Total <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', $barang->jumlah) }}" min="0" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                 <div>
                    <label for="tersedia" class="block text-sm font-medium text-gray-700">Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" name="tersedia" id="tersedia" value="{{ old('tersedia', $barang->tersedia) }}" min="0" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="dipinjam" class="block text-sm font-medium text-gray-700">Dipinjam <span class="text-red-500">*</span></label>
                    <input type="number" name="dipinjam" id="dipinjam" value="{{ old('dipinjam', $barang->dipinjam) }}" min="0" required
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <hr>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                     <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                    <select name="kondisi" id="kondisi" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi', $barang->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi', $barang->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div>
                     <label for="status" class="block text-sm font-medium text-gray-700">Status Ketersediaan <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="tersedia" {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="tidak tersedia" {{ old('status', $barang->status) == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('keterangan', $barang->keterangan) }}</textarea>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('barang.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">Batal</a>
                <button type="submit" class="bg-indigo-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Update Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection