<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Data ringkasan utama
        $totalBarang = Barang::count();
        $totalKategori = Kategori::count();
        $barangTersedia = Barang::sum('tersedia');
        $barangDipinjam = Barang::sum('dipinjam');
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $pengembalian = Pengembalian::where('label_status', 'selesai')->count();

        // Data untuk Timeline Aktivitas Terbaru
        $recentPeminjaman = Peminjaman::with(['user', 'barang'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Data untuk Peminjaman Jatuh Tempo
        $expiringLoans = Peminjaman::with(['user', 'barang'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '>=', Carbon::now())
            ->where('tanggal_kembali', '<=', Carbon::now()->addDays(7))
            ->orderBy('tanggal_kembali', 'asc')
            ->limit(5)
            ->get();

        // Data untuk Chart Komposisi Kategori
        $kategoriBarchart = Kategori::withCount('barang')
            ->orderBy('barang_count', 'desc')
            ->get();

        // Data untuk Chart Aktivitas Bulanan
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $peminjamanCount = Peminjaman::whereYear('tanggal_pinjam', $month->year)
                ->whereMonth('tanggal_pinjam', $month->month)
                ->count();
            $pengembalianCount = Pengembalian::whereYear('tanggal_kembali', $month->year)
                ->whereMonth('tanggal_kembali', $month->month)
                ->where('label_status', 'selesai')
                ->count();
            $monthlyData[] = [
                'month' => $month->format('M'),
                'peminjaman' => $peminjamanCount,
                'pengembalian' => $pengembalianCount
            ];
        }

        // Data untuk Panel "Perlu Perhatian Anda"
        $requestPeminjaman = Peminjaman::where('status', 'menunggu')->get();
        // Query diperbaiki dari 'status' menjadi 'label_status'
        $requestPengembalian = Pengembalian::where('label_status', 'menunggu')->get();
        
        $isAdmin = auth()->user()->role === 'admin';

        return view('dashboard', compact(
            'totalBarang',
            'totalKategori',
            'barangTersedia',
            'barangDipinjam',
            'peminjamanAktif',
            'pengembalian',
            'recentPeminjaman',
            'expiringLoans',
            'kategoriBarchart',
            'monthlyData',
            'requestPeminjaman',
            'requestPengembalian',
            'isAdmin'
        ));
    }
}