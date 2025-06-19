@extends('layout.app')

@section('title', 'Laporan')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
@endpush

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Laporan dan Analitik</h2>
    <p class="text-gray-600">Visualisasi data dan unduh laporan inventaris.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Barang</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalBarang }}</p>
            </div>
            <div class="bg-blue-100 text-blue-600 rounded-full p-3"><i class="fas fa-box fa-lg"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Peminjaman ({{ $tahunIni }})</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPeminjaman }}</p>
            </div>
            <div class="bg-green-100 text-green-600 rounded-full p-3"><i class="fas fa-hand-holding fa-lg"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pengembalian ({{ $tahunIni }})</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalPengembalian }}</p>
            </div>
            <div class="bg-purple-100 text-purple-600 rounded-full p-3"><i class="fas fa-undo-alt fa-lg"></i></div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Cetak Laporan</h3>
    <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        @csrf
        <div>
            <label for="jenis_laporan" class="block text-sm font-medium text-gray-700">Jenis Laporan</label>
            <select id="jenis_laporan" name="jenis_laporan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">Pilih Jenis</option>
                <option value="barang_terpopuler">Barang Terpopuler</option>
                <option value="peminjaman_bulanan">Peminjaman Bulanan</option>
                <option value="pengembalian_bulanan">Pengembalian Bulanan</option>
                <option value="kondisi_barang">Kondisi Barang</option>
            </select>
        </div>
        <div>
            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
            <select id="bulan" name="bulan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
            <select id="tahun" name="tahun" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @for($i = date('Y'); $i >= date('Y')-5; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                <i class="fas fa-print mr-2"></i>Cetak
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="font-semibold text-gray-800">Barang Terpopuler</h3>
        <div class="h-80 mt-4"><canvas id="barangTerpopulerChart"></canvas></div>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="font-semibold text-gray-800">Kondisi Barang</h3>
        <div class="h-80 mt-4"><canvas id="kondisiBarangChart"></canvas></div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="font-semibold text-gray-800">Aktivitas Bulanan ({{ $tahunIni }})</h3>
    <div class="h-96 mt-4"><canvas id="bulananChart"></canvas></div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Data from Blade
        const barangLabels = {!! json_encode($barangTerpopuler->pluck('barang.nama_barang')) !!};
        const barangData = {!! json_encode($barangTerpopuler->pluck('jumlah_peminjaman')) !!};
        const kondisiLabels = {!! json_encode($kondisiBarang->pluck('kondisi')) !!};
        const kondisiData = {!! json_encode($kondisiBarang->pluck('jumlah')) !!};
        const bulanLabels = {!! json_encode(collect($dataPeminjaman)->pluck('bulan')) !!};
        const peminjamanData = {!! json_encode(collect($dataPeminjaman)->pluck('jumlah')) !!};
        const pengembalianData = {!! json_encode(collect($dataPengembalian)->pluck('jumlah')) !!};

        // Barang Terpopuler Chart
        new Chart(document.getElementById('barangTerpopulerChart').getContext('2d'), {
            type: 'bar',
            data: { labels: barangLabels, datasets: [{ label: 'Jumlah Peminjaman', data: barangData, backgroundColor: 'rgba(79, 70, 229, 0.8)' }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // Kondisi Barang Chart
        new Chart(document.getElementById('kondisiBarangChart').getContext('2d'), {
            type: 'pie',
            data: { labels: kondisiLabels, datasets: [{ data: kondisiData, backgroundColor: ['#10b981', '#f59e0b', '#ef4444'] }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
        });

        // Aktivitas Bulanan Chart
        new Chart(document.getElementById('bulananChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [
                    { label: 'Peminjaman', data: peminjamanData, borderColor: '#4f46e5', backgroundColor: 'rgba(79, 70, 229, 0.1)', fill: true, tension: 0.3 },
                    { label: 'Pengembalian', data: pengembalianData, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', fill: true, tension: 0.3 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
        
        // Disable/enable form fields based on report type
        document.getElementById('jenis_laporan').addEventListener('change', function() {
            const isBulanan = this.value.includes('bulanan');
            document.getElementById('bulan').disabled = !isBulanan;
            document.getElementById('tahun').disabled = this.value === 'kondisi_barang';
        });
        // Trigger change on load
        document.getElementById('jenis_laporan').dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection