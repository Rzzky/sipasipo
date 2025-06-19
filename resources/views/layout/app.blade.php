<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sisfo Sarpras') - Sistem Informasi Sarana Prasarana</title>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* light gray background */
        }
        .active-nav {
            color: #ffffff;
            background-color: #4f46e5; /* Indigo 600 */
        }
        .nav-link:hover {
            color: #ffffff;
            background-color: #6366f1; /* Indigo 500 */
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav x-data="{ open: false }" class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                             <i class="fas fa-boxes text-indigo-600 text-2xl"></i>
                            <span class="ml-2 text-xl font-bold text-gray-800">SISFO SARPRAS</span>
                        </div>

                        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Dashboard
                            </a>
                            <a href="{{ route('barang.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('barang.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Barang
                            </a>
                             <a href="{{ route('kategori.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('kategori.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Kategori
                            </a>
                            <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('peminjaman.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Peminjaman
                            </a>
                             <a href="{{ route('pengembalian.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('pengembalian.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Pengembalian
                            </a>
                             <div x-data="{ open: false }" class="relative flex items-center">
                                <button @click="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('request.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                    <span>Permintaan</span>
                                    <i class="fas fa-chevron-down text-xs ml-1"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute top-full mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20" style="display: none;">
                                    <div class="py-1">
                                        <a href="{{ route('request.peminjaman.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Peminjaman</a>
                                        <a href="{{ route('request.pengembalian.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengembalian</a>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('laporan.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('laporan.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Laporan
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div x-data="{ open: false }" class="ml-3 relative">
                            <div>
                                <button @click="open = !open" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span class="sr-only">Open user menu</span>
                                     <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">{{ strtoupper(substr(auth()->user()->username, 0, 2)) }}</span>
                                    </div>
                                    <span class="ml-2 text-gray-700 text-sm font-medium">{{ auth()->user()->username }}</span>
                                     <i class="fas fa-chevron-down text-xs text-gray-500 ml-1"></i>
                                </button>
                            </div>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-20" style="display: none;">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Anda</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                            <i :class="{'hidden': open, 'inline-flex': !open }" class="fas fa-bars h-6 w-6"></i>
                            <i :class="{'hidden': !open, 'inline-flex': open }" class="fas fa-times h-6 w-6"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': !open}" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Dashboard</a>
                    <a href="{{ route('barang.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('barang.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Barang</a>
                    <a href="{{ route('kategori.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('kategori.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Kategori</a>
                    <a href="{{ route('peminjaman.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('peminjaman.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Peminjaman</a>
                    <a href="{{ route('pengembalian.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('pengembalian.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Pengembalian</a>
                     <a href="{{ route('request.peminjaman.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('request.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Permintaan</a>
                    <a href="{{ route('laporan.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('laporan.*') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium">Laporan</a>
                </div>
            </div>
        </nav>

        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                             <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </main>
        
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Sistem Informasi Sarana Prasarana. All rights reserved.
            </div>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>