@extends('layout.app')

@section('title', 'Dashboard')

@push('styles')
    {{-- Chart.js sudah ada di layout utama, bisa ditambahkan style khusus di sini jika perlu --}}
    <style>
        .timeline-item:before {
            content: '';
            display: block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: white;
            border: 3px solid #6366f1; /* indigo-500 */
            position: absolute;
            left: -6px;
            top: 5px;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start mb-8">
        <div>
            <h1 id="greeting" class="text-3xl font-bold text-gray-800"></h1>
            <p class="text-gray-600 mt-1">Selamat datang kembali, {{ auth()->user()->username }}. Ini laporan aktivitas Anda hari ini.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-2 bg-white p-2 rounded-lg shadow-sm">
            <i class="fas fa-calendar-alt text-indigo-500"></i>
            <span class="text-sm font-medium text-gray-600">{{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-boxes text-blue-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Barang</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalBarang }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fas fa-tags text-green-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Kategori</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalKategori }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <i class="fas fa-hand-holding-hand text-yellow-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Peminjaman Aktif</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $peminjamanAktif }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 flex items-center space-x-4">
                    <div class="bg-purple-100 p-4 rounded-full">
                        <i class="fas fa-undo-alt text-purple-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pengembalian</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $pengembalian }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-semibold text-gray-800 mb-4">Aktivitas Peminjaman & Pengembalian (Per Bulan)</h3>
                <div class="h-80"><canvas id="monthlyActivityChart"></canvas></div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-lg">
                 <h3 class="font-semibold text-gray-800 mb-6">Aktivitas Terbaru</h3>
                 <div class="relative pl-6 border-l-2 border-gray-200 space-y-8">
                    @php 
                        $recentActivities = $recentPeminjaman->map(function($item) {
                            $item->activity_type = 'peminjaman';
                            return $item;
                        })->concat($expiringLoans->map(function($item) {
                            $item->activity_type = 'jatuh_tempo';
                            return $item;
                        }))->sortByDesc('created_at')->take(5);
                    @endphp
                    
                    @forelse($recentActivities as $activity)
                        <div class="timeline-item">
                             <div class="flex items-center space-x-3">
                                <div class="p-2 rounded-full 
                                    @if($activity->activity_type == 'peminjaman') bg-blue-100 text-blue-500
                                    @elseif($activity->activity_type == 'jatuh_tempo') bg-yellow-100 text-yellow-500
                                    @endif">
                                    <i class="fas @if($activity->activity_type == 'peminjaman') fa-sign-out-alt @else fa-clock @endif"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm text-gray-800 font-medium">
                                        <span class="font-bold">{{ $activity->user->username }}</span> 
                                        @if($activity->activity_type == 'peminjaman')
                                            meminjam <span class="font-bold">{{ $activity->barang->nama_barang }}</span>.
                                        @else
                                            peminjaman <span class="font-bold">{{ $activity->barang->nama_barang }}</span> akan jatuh tempo.
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                           </div>
                        </div>
                    @empty
                        <div class="timeline-item">
                            <p class="text-gray-500">Belum ada aktivitas terbaru.</p>
                        </div>
                    @endforelse
                 </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-semibold text-gray-800 mb-4">Perlu Perhatian Anda</h3>
                <div class="space-y-4">
                    @if($requestPeminjaman->count() > 0)
                    <a href="{{ route('request.peminjaman.index') }}" class="block p-4 rounded-lg bg-red-50 hover:bg-red-100 transition">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-red-700">Permintaan Peminjaman</p>
                            <span class="text-sm font-bold text-white bg-red-500 rounded-full px-2.5 py-0.5">{{ $requestPeminjaman->count() }}</span>
                        </div>
                        <p class="text-xs text-red-600 mt-1">Ada {{ $requestPeminjaman->count() }} permintaan baru yang perlu disetujui.</p>
                    </a>
                    @endif
                    @if($requestPengembalian->count() > 0)
                    <a href="{{ route('request.pengembalian.index') }}" class="block p-4 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
                         <div class="flex items-center justify-between">
                            <p class="font-semibold text-blue-700">Permintaan Pengembalian</p>
                            <span class="text-sm font-bold text-white bg-blue-500 rounded-full px-2.5 py-0.5">{{ $requestPengembalian->count() }}</span>
                        </div>
                        <p class="text-xs text-blue-600 mt-1">Ada {{ $requestPengembalian->count() }} permintaan yang menunggu konfirmasi.</p>
                    </a>
                    @endif
                    @if($requestPeminjaman->count() == 0 && $requestPengembalian->count() == 0)
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-green-400 text-3xl"></i>
                        <p class="text-sm text-gray-500 mt-2">Semua permintaan sudah diproses. Kerja bagus!</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-lg">
                 <h3 class="font-semibold text-gray-800 mb-4">Status Inventaris</h3>
                 <div class="h-48 flex items-center justify-center"><canvas id="inventoryStatusChart"></canvas></div>
                 <div class="mt-4 flex justify-between text-sm">
                    <div class="text-center">
                        <p class="font-bold text-green-500">{{ $barangTersedia }}</p>
                        <p class="text-gray-500">Tersedia</p>
                    </div>
                    <div class="text-center">
                        <p class="font-bold text-yellow-500">{{ $barangDipinjam }}</p>
                        <p class="text-gray-500">Dipinjam</p>
                    </div>
                 </div>
            </div>
            
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-semibold text-gray-800 mb-4">Komposisi Kategori</h3>
                <div class="h-64"><canvas id="categoryChart"></canvas></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Dynamic Greeting
    const greetingElement = document.getElementById('greeting');
    const hour = new Date().getHours();
    let greetingText = "Halo!";
    if (hour < 12) {
        greetingText = "Selamat Pagi";
    } else if (hour < 15) {
        greetingText = "Selamat Siang";
    } else if (hour < 18) {
        greetingText = "Selamat Sore";
    } else {
        greetingText = "Selamat Malam";
    }
    greetingElement.textContent = greetingText;

    // Chart.js instances
    const chartFontColor = '#6b7280'; // text-gray-500

    // 2. Monthly Activity Chart
    new Chart(document.getElementById('monthlyActivityChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [
                {
                    label: 'Peminjaman',
                    data: {!! json_encode(array_column($monthlyData, 'peminjaman')) !!},
                    borderColor: '#4f46e5', // indigo-600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: 'Pengembalian',
                    data: {!! json_encode(array_column($monthlyData, 'pengembalian')) !!},
                    borderColor: '#10b981', // emerald-500
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, color: chartFontColor } },
                x: { grid: { display: false }, ticks: { color: chartFontColor } }
            },
            plugins: { legend: { position: 'top', labels: { color: chartFontColor } } }
        }
    });

    // 3. Category Composition Chart
    new Chart(document.getElementById('categoryChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($kategoriBarchart->pluck('nama_kategori')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($kategoriBarchart->pluck('barang_count')->toArray()) !!},
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#8b5cf6'],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
    
    // 4. Inventory Status Chart (Half Doughnut)
    const totalInventory = {{ $barangTersedia }} + {{ $barangDipinjam }};
    const availablePercentage = totalInventory > 0 ? ({{ $barangTersedia }} / totalInventory) * 100 : 0;
    
    new Chart(document.getElementById('inventoryStatusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipinjam'],
            datasets: [{
                data: [{{ $barangTersedia }}, {{ $barangDipinjam }}],
                backgroundColor: ['#22c55e', '#f59e0b'], // green-500, yellow-500
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            rotation: -90,
            circumference: 180,
            cutout: '60%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} unit`;
                        }
                    }
                }
            }
        },
        plugins: [{
            id: 'centerText',
            beforeDraw: function(chart) {
                const { width, height, ctx } = chart;
                ctx.restore();
                const fontSize = (height / 114).toFixed(2);
                ctx.font = `bold ${fontSize}em sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#1f2937'; // gray-800

                const text = `${Math.round(availablePercentage)}%`;
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 1.5;

                ctx.fillText(text, textX, textY);
                ctx.save();
                
                // Sub text
                const subText = 'Tersedia';
                const subFontSize = (height / 180).toFixed(2);
                ctx.font = `${subFontSize}em sans-serif`;
                ctx.fillStyle = '#6b7280'; // gray-500
                const subTextX = Math.round((width - ctx.measureText(subText).width) / 2);
                const subTextY = textY + (fontSize * 16) * 0.7; // Adjust position
                ctx.fillText(subText, subTextX, subTextY);
            }
        }]
    });
});
</script>
@endpush