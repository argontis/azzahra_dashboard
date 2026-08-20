@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="page-header">
    <div class="flex items-center gap-2 text-white">
        <i data-feather="home" class="w-5 h-5"></i>
        <h1 class="text-xl font-bold tracking-wide">Dashboard</h1>
        <span class="hidden md:inline-block text-white text-xs opacity-90 ml-4 border-l border-white/20 pl-4">Welcome back, here's your business overview</span>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="relative hidden sm:block">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search..." class="pl-9 pr-4 py-2 rounded-md border-0 focus:ring-2 focus:ring-blue-400 text-gray-700 text-sm w-48 lg:w-80 shadow-sm">
        </div>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm relative">
            <i data-feather="bell" class="w-4 h-4"></i>
            <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
        </button>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm">
            <i data-feather="mail" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<div class="content">
    <div class="p-6 md:p-8 bg-gray-50/50 min-h-screen">
        <!-- General Report Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-700">General Report</h2>
            <a href="{{ route('service.index') }}" class="flex items-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                <i data-feather="refresh-ccw" class="w-4 h-4 mr-2"></i> Reload Data
            </a>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Transaksi Baru -->
            <a href="{{ route('service.antrean', 'baru') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="user-plus" class="w-6 h-6 text-blue-500"></i>
                        <div class="w-3 h-1 bg-blue-500 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['baru'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Transaksi Baru</div>
                </div>
            </a>

            <!-- Transaksi Diproses -->
            <a href="{{ route('service.antrean', 'proses') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="user-check" class="w-6 h-6 text-green-500"></i>
                        <div class="w-3 h-1 bg-green-500 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['proses'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Transaksi diproses</div>
                </div>
            </a>

            <!-- Konfirmasi -->
            <a href="{{ route('service.antrean', 'konfirmasi') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="phone-call" class="w-6 h-6 text-gray-400"></i>
                        <div class="w-3 h-1 bg-gray-400 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['konfirmasi'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Konfirmasi</div>
                </div>
            </a>

            <!-- Pelunasan -->
            <a href="{{ route('service.antrean', 'pelunasan') }}" class="block">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow h-full flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <i data-feather="credit-card" class="w-6 h-6 text-red-500"></i>
                        <div class="w-3 h-1 bg-red-500 rounded-full"></div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-2">{{ $stats['pelunasan'] }}</div>
                    <div class="text-sm text-gray-400 mt-auto">Pelunasan</div>
                </div>
            </a>

            <!-- DP Non Tunai -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="credit-card" class="w-6 h-6 text-orange-400"></i>
                    <div class="w-3 h-1 bg-orange-400 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto">DP Non Tunai</div>
            </div>

            <!-- DP Tunai -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="dollar-sign" class="w-6 h-6 text-blue-600"></i>
                    <div class="w-3 h-1 bg-blue-600 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto">DP Tunai</div>
            </div>

            <!-- BANK BCA -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="layers" class="w-6 h-6 text-indigo-900"></i>
                    <div class="w-3 h-1 bg-indigo-900 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto uppercase">BANK BCA</div>
            </div>

            <!-- BANK MANDIRI -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <i data-feather="layers" class="w-6 h-6 text-slate-500"></i>
                    <div class="w-3 h-1 bg-slate-500 rounded-full"></div>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-2">Rp. 0,-</div>
                <div class="text-sm text-gray-400 mt-auto uppercase">BANK MANDIRI</div>
            </div>

        </div>
    </div>
</div>
@endsection
